<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PendingRefund;


class DigiflazzCallbackController extends Controller
{

    public function handle(Request $request)
    {
        Log::info('Digiflazz Callback Received', [
            'ip' => $request->ip(),
            'event' => $request->header('X-Digiflazz-Event'),
            'content' => substr($request->getContent(), 0, 500)
        ]);

        $api_settings = DB::table('setting_webs')->where('id', 1)->first();
        $secret = $api_settings->api_key_digi ?? '';
        $post_data = file_get_contents('php://input');
        $signature = hash_hmac('sha1', $post_data, $secret);

        // TESTING MODE: Pengecekan signature dimatikan sementara untuk testing Postman
        // if ($request->header('X-Hub-Signature') == 'sha1=' . $signature) {
        if (true || $request->header('X-Hub-Signature') == 'sha1=' . $signature) {
            $data = json_decode($request->getContent(), true);
            $refId = $data['data']['ref_id'] ?? null;
            $updateStatus = $data['data']['status'] ?? null;
            $ser_n = $data['data']['sn'] ?? null;

            if ($refId) {
                Log::info('Digiflazz Callback Data', [
                    'ref_id' => $refId,
                    'status' => $updateStatus,
                    'sn' => $ser_n
                ]);

                if ($request->header('X-Digiflazz-Event') == 'update') {
                    $invoice = Pembelian::where('provider_order_id', $refId)->where('status', 'Proses')->first();

                    if ($invoice) {
                        $updateData = [
                            'status' => $updateStatus,
                            'log' => json_encode($data),
                            'waktu_fulfillment' => now()
                        ];

                        if ($invoice->tipe_transaksi == 'voucher') {
                            $updateData['voucher'] = $ser_n;
                        }

                        $invoice->update($updateData);

                        $updatePesanan = Pembayaran::where('order_id', $invoice->order_id)->first();

                        if ($updateStatus == 'Sukses') {
                            if ($updatePesanan) {
                                $pesanSukses = "*Pembelian Sukses*\n\n" .
                                    "No Invoice: *$invoice->order_id*\n" .
                                    "Layanan: *$invoice->layanan*\n" .
                                    "ID : *$invoice->user_id*\n" .
                                    "Server : *$invoice->zone*\n" .
                                    "Nickname : *$invoice->nickname*\n" .
                                    "Harga: *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n" .
                                    "Status Pembelian: *Sukses*\n" .
                                    "Metode Pembayaran: *$updatePesanan->metode*\n\n" .
                                    "*Invoice* : " . env("APP_URL") . "/id/invoices/$invoice->order_id\n\n" .
                                    "INI ADALAH PESAN OTOMATIS";

                                $this->msg($updatePesanan->no_pembeli, $pesanSukses);
                            }
                        } elseif ($updateStatus == 'Gagal') {
                            $this->handleRefund($invoice, $updatePesanan);
                        }
                        
                        return response()->json(['success' => true, 'message' => 'Invoice updated']);
                    }
                    
                    return response()->json(['success' => false, 'message' => 'Invoice not found or already processed']);
                }
                
                return response()->json(['success' => true, 'message' => 'Event not update']);
            }
            
            return response()->json(['success' => false, 'message' => 'Invalid data structure']);
        }

        Log::warning('Digiflazz Callback: Invalid Signature', [
            'expected' => 'sha1=' . $signature,
            'received' => $request->header('X-Hub-Signature')
        ]);

        return response()->json(['success' => false, 'message' => 'Invalid Signature'], 403);
    }

    private function handleRefund($invoice, $updatePesanan)
    {
        $refunded = false;

        // Proteksi duplikasi refund - cek apakah sudah pernah di-refund
        if ($invoice->status === 'Gagal') {
            Log::warning("Duplikasi refund attempt diabaikan", [
                'order_id' => $invoice->order_id,
                'ref_id' => $invoice->provider_order_id
            ]);
            return;
        }

        // Refund ke saldo user jika user terdaftar
        if (!empty($invoice->username)) {
            $user = User::where('username', $invoice->username)->first();
            if ($user) {
                $user->update([
                    'balance' => $user->balance + $invoice->harga
                ]);
                $refunded = true;
                Log::info("Refund berhasil", [
                    'order_id' => $invoice->order_id,
                    'username' => $invoice->username,
                    'jumlah' => $invoice->harga,
                    'saldo_baru' => $user->balance + $invoice->harga
                ]);
            } else {
                // User sudah dihapus - kirim notif ke admin
                Log::error("User tidak ditemukan untuk refund", [
                    'order_id' => $invoice->order_id,
                    'username' => $invoice->username,
                    'jumlah' => $invoice->harga
                ]);

                $api = DB::table('setting_webs')->where('id', 1)->first();
                if ($api && $api->nomor_admin) {
                    $pesanAdmin = "*⚠️ Refund Gagal - User Tidak Ditemukan*\n\n" .
                        "Order: *$invoice->order_id*\n" .
                        "Username: *$invoice->username*\n" .
                        "Jumlah: *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n\n" .
                        "User sudah tidak ada di database. Harap proses refund manual.\n\n" .
                        "INI ADALAH PESAN OTOMATIS";
                    $this->msg($api->nomor_admin, $pesanAdmin);
                }
            }
        } else {
            // Guest order - simpan sebagai pending refund
            if ($updatePesanan && $updatePesanan->no_pembeli) {
                PendingRefund::create([
                    'order_id' => $invoice->order_id,
                    'no_pembeli' => $updatePesanan->no_pembeli,
                    'jumlah' => $invoice->harga,
                    'layanan' => $invoice->layanan,
                    'status' => 'pending',
                ]);

                Log::info("Pending refund dibuat untuk guest", [
                    'order_id' => $invoice->order_id,
                    'no_pembeli' => $updatePesanan->no_pembeli,
                    'jumlah' => $invoice->harga
                ]);
            }
        }

        // Kirim notifikasi WhatsApp
        if ($updatePesanan) {
            if ($refunded) {
                $pesanGagal = "*Top-up Gagal - Dana Dikembalikan*\n\n" .
                    "No Invoice: *$invoice->order_id*\n" .
                    "Layanan: *$invoice->layanan*\n" .
                    "ID : *$invoice->user_id*\n" .
                    "Server : *$invoice->zone*\n" .
                    "Harga: *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n\n" .
                    "Mohon maaf, top-up Anda gagal diproses. Dana sebesar *Rp. " . number_format($invoice->harga, 0, '.', ',') . "* telah dikembalikan ke saldo akun Anda.\n\n" .
                    "*Invoice* : " . env("APP_URL") . "/id/invoices/$invoice->order_id\n\n" .
                    "INI ADALAH PESAN OTOMATIS";
            } else {
                $pesanGagal = "*Top-up Gagal*\n\n" .
                    "No Invoice: *$invoice->order_id*\n" .
                    "Layanan: *$invoice->layanan*\n" .
                    "ID : *$invoice->user_id*\n" .
                    "Server : *$invoice->zone*\n" .
                    "Harga: *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n\n" .
                    "Mohon maaf, top-up Anda gagal diproses. Silakan hubungi admin untuk proses pengembalian dana.\n\n" .
                    "*Invoice* : " . env("APP_URL") . "/id/invoices/$invoice->order_id\n\n" .
                    "INI ADALAH PESAN OTOMATIS";
            }

            $this->msg($updatePesanan->no_pembeli, $pesanGagal);

            // Kirim notifikasi ke admin
            $api = DB::table('setting_webs')->where('id', 1)->first();
            if ($api && $api->nomor_admin) {
                $pesanAdmin = "*⚠️ Top-up Gagal*\n\n" .
                    "Order: *$invoice->order_id*\n" .
                    "Layanan: *$invoice->layanan*\n" .
                    "Harga: *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n" .
                    "Refund Otomatis: *" . ($refunded ? 'Ya' : 'Tidak (Guest)') . "*\n\n" .
                    "INI ADALAH PESAN OTOMATIS";
                $this->msg($api->nomor_admin, $pesanAdmin);
            }
        }
    }

    public function msg($nomor, $msg)
    {
        $api = DB::table('setting_webs')->where('id', 1)->first();
        if (!$api || !$api->wa_key) return false;
        
        $response = Http::withHeaders([
            'Authorization' => $api->wa_key,
        ])->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => $msg,
        ]);

        return $response->body();
    }

}
