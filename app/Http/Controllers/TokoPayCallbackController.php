<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Layanan;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;
use App\Models\Voucher;
use App\Models\Deposit;
use App\Models\User;
use App\Http\Controllers\digiFlazzController;
use App\Http\Controllers\provider\topupedia\TopupediaController;
use App\Http\Controllers\provider\bangjeff\BangJeffController;
use App\Http\Controllers\provider\aoshi\AoshiController;
class TokoPayCallbackController extends Controller
{
    protected $api;

    public function __construct()
    {
        $this->api = DB::table('setting_webs')->where('id', 1)->first();
    }

    public function handle(Request $request)
    {
        $json = $request->getContent();
        $data = json_decode($json, true);
        if (isset($data['status'], $data['reff_id'], $data['signature'])) {
            $referenceUniq = $data['reference'];
            $invoice = Pembayaran::where('reference', $referenceUniq)
                ->where('status', 'Belum Lunas')
                ->first();

            if (!$invoice) {
                return Response::json([
                    'success' => false,
                    'message' => 'No invoice found or already paid: ' . $referenceUniq,
                ]);
            }

            $order_id = $invoice->order_id;
            $dataPembeli = Pembelian::where('order_id', $order_id)->first();

            if ($dataPembeli) {
                $dataLayanan = Layanan::where('layanan', $dataPembeli->layanan)->first();

                if ($dataLayanan) {
                    $dataKategori = Kategori::where('id', $dataLayanan->kategori_id)->first();

                    if ($dataKategori) {
                        $pesanPembeli =
                            "*Pembayaran Berhasil*\n\n" .
                            "No Invoice: *$order_id*\n" .
                            "Layanan : *$dataPembeli->layanan*\n" .
                            "ID : *$dataPembeli->user_id*\n" .
                            "Server : *$dataPembeli->zone*\n" .
                            "Nickname : *$dataPembeli->nickname*\n" .
                            "Harga : *Rp. " . number_format($dataPembeli->harga, 0, '.', ',') . "*\n" .
                            "Status Pembelian: *Process*\n" .
                            "Estimasi Proses: *1-5 Menit Max 24 Jam*\n\n" .
                            "INI ADALAH PESAN OTOMATIS";

                        $pesanJoki =
                            "*Pembayaran Berhasil*\n\n" .
                            "No Invoice: *$order_id*\n" .
                            "Layanan: *$dataPembeli->layanan*\n" .
                            "ID: *$dataPembeli->user_id*\n" .
                            "Server: *$dataPembeli->zone*\n" .
                            "Nickname: *$dataPembeli->nickname*\n" .
                            "Harga: *Rp. " . number_format($dataPembeli->harga, 0, '.', ',') . "*\n" .
                            "Status Pembelian: *Process*\n" .
                            "Penjoki kami akan segera memulai permainan.\n\n" .
                            "INI ADALAH PESAN OTOMATIS";

                        $pesanSukses =
                            "*Diamond Berhasil Dikirim*\n\n" .
                            "No Invoice: *$order_id*\n" .
                            "Layanan: *$dataPembeli->layanan*\n" .
                            "ID: *$dataPembeli->user_id*\n" .
                            "Server: *$dataPembeli->zone*\n" .
                            "Nickname: *$dataPembeli->nickname*\n" .
                            "Harga: *Rp. " . number_format($dataPembeli->harga, 0, '.', ',') . "*\n" .
                            "Status Pembelian: *Success*\n\n" .
                            "Terima kasih telah bertransaksi dengan kami.";

                        $zoneSend = $dataPembeli->zone == null ? "" : "($dataPembeli->zone)\n";
                        $nickname = $dataPembeli->nickname == null ? '' : "Nickname : $dataPembeli->nickname\n";

                        $uid = $dataPembeli->user_id;
                        $zone = $dataPembeli->zone;
                        $provider_id = $dataLayanan->provider_id;
                    } else {
                        // Handle jika $dataKategori tidak ditemukan
                    }
                } else {
                    // Handle jika $dataLayanan tidak ditemukan
                }
            } else {
                $dataDeposit = Deposit::where('order_id', $order_id)->first();
            }

            $status = $data['status'];
            if ($status === "Success") {
                // Hanya proses yang status transaksi sudah di bayar, sukses = dibayar
                $ref_id = $data['reff_id'];
                /*
                 * Validasi Signature
                 */
                $signature_from_tokopay = $data['signature'];
                $signature_validasi = md5($this->api->tokopay_merchant_id . ":" . $this->api->tokopay_secret_key . ":" . $ref_id);
                if ($signature_from_tokopay === $signature_validasi) {

                    if (isset($dataDeposit)) {
                        $userDeposit = User::where('username', $dataDeposit->username)->first();

                        if ($userDeposit) {
                            $userDeposit->update([
                                'balance' => $dataDeposit->jumlah + $userDeposit->balance,
                            ]);
                            $dataDeposit->update([
                                'status' => 'Success'
                            ]);
                        }
                    } else {
                        if ($dataLayanan->provider == "digiflazz") {
                        $random_part = mt_rand(100000, 999999);
                        $provider_order_id = 'REF-HARY' . $random_part;
                        $digiFlazz = new digiFlazzController;
                        $order = $digiFlazz->order($uid, $zone, $provider_id, $provider_order_id);
                    
                        if ($order['data']['status'] == "Pending" || $order['data']['status'] == "Sukses") {
                            $order['data']['status'] = true;
                            $order['transactionId'] = $provider_order_id;
                        } else {
                            $order['data']['status'] = false;
                        }
                        } elseif ($dataLayanan->provider == "topupedia") {
                            $topupedia = new TopupediaController;
                            
                            $ttlpembelian = [
                                [
                                    "name" => "id",
                                    "value" => $dataPembeli->user_id
                                ]
                            ];
                        
                            if ($dataPembeli->zone != null) {
                                $ttlpembelian[] = [
                                    "name" => "server",
                                    "value" => $dataPembeli->zone
                                ];
                            }
                            
                            $order = $topupedia->order($provider_id, $order_id, 1, $ttlpembelian);
                        
                            if ($order['error'] == false) {
                                $order['transactionId'] = $order['data']['invoiceNumber'];
                                $order['data']['status'] = true;
                            } else {
                                $order['data']['status'] = false;
                            }
                        } elseif ($dataLayanan->provider == "bangjeff") {
                            $bangjef = new BangjeffController;
                            
                            $ttlpembelian = [
                                [
                                    "name" => "id",
                                    "value" => $dataPembeli->user_id
                                ]
                            ];
                        
                            if ($dataPembeli->zone != null) {
                                $ttlpembelian[] = [
                                    "name" => "server",
                                    "value" => $dataPembeli->zone
                                ];
                            }
                            
                            $order = $bangjef->order($provider_id, $order_id, 1, $ttlpembelian);
                        
                            if ($order['error'] == false) {
                                $order['transactionId'] = $order['data']['invoiceNumber'];
                                $order['data']['status'] = true;
                            } else {
                                $order['data']['status'] = false;
                            }
                        } elseif ($dataLayanan->provider == "aoshi") {
                            $aoshi = new AoshiController;
                            
                            $ttlpembelian = [
                                [
                                    "name" => "id",
                                    "value" => $dataPembeli->user_id
                                ]
                            ];
                        
                            if ($dataPembeli->zone != null) {
                                $ttlpembelian[] = [
                                    "name" => "server",
                                    "value" => $dataPembeli->zone
                                ];
                            }
                            
                            $order = $aoshi->order($provider_id, $order_id, 1, $ttlpembelian);
                        
                            if ($order['error'] == false) {
                                $order['transactionId'] = $order['data']['invoiceNumber'];
                                $order['data']['status'] = true;
                            } else {
                                $order['data']['status'] = false;
                            }
                        } elseif ($dataLayanan->provider == "joki" || $dataLayanan->provider == "jokigendong" || $dataLayanan->provider == "vilogml") {
                            $provider_order_id = '';
                            $order['data']['status'] = true;
                        }


                        if ($order['data']['status']) {

                            if ($dataPembeli->tipe_transaksi !== 'joki' || $dataLayanan->provider == "vilogml") {
                                // Update status menjadi 'Proses' untuk tipe transaksi bukan 'joki'
                                $dataPembeli->update([
                                    'provider_order_id' => isset($order['transactionId']) ? $order['transactionId'] : 0,
                                    'status' => 'Proses',
                                    'log' => json_encode($order),
                                    'waktu_callback' => now()
                                ]);
                                // Kirim pesan setelah status menjadi 'Diproses'
                                $this->msg($invoice->no_pembeli, $pesanPembeli);
                            } else {
                                // Update status menjadi 'Proses' untuk tipe transaksi 'joki'
                                $dataPembeli->update([
                                    'provider_order_id' => '', 
                                    'status' => 'Proses',
                                    'log' => json_encode($order),
                                    'waktu_callback' => now()
                                ]);
                                // Kirim pesan untuk joki setelah status 'Diproses'
                                $this->msg($invoice->no_pembeli, $pesanJoki);
                            }
                        } else {
                            // Logika untuk order yang gagal
                            if ($dataPembeli->tipe_transaksi !== 'joki') {
                                $dataPembeli->update([
                                    'status' => 'Batal', // Update status menjadi 'Batal' untuk tipe transaksi bukan 'joki'
                                    'log' => json_encode($order)
                                ]);
                            } else {
                                // Jika tipe transaksi adalah 'joki' dan transaksi gagal, Anda dapat menentukan logika khusus di sini
                            }
                        }
                    }
                    $invoice->update(['status' => 'Lunas']);

                    // Cek jika status transaksi berubah menjadi 'Sukses'
                    if ($dataPembeli->status === 'Sukses' && $dataPembeli->tipe_transaksi !== 'joki') {
                        $this->msg($invoice->no_pembeli, $pesanSukses);
                    }

                    return Response::json(['success' => true]);
                } else {
                    return Response::json(['error' => "Invalid Signature"]);
                }
            } else {
                return Response::json(['error' => "Status payment tidak success"]);
            }
        } else {
            return Response::json(['error' => "Data json tidak sesuai"]);
        }
    }

    public function msg($nomor, $msg)
    {
        $api = DB::table('setting_webs')->where('id', 1)->first();

         $response = Http::withHeaders([
            'Authorization' => $api->wa_key,
        ])->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => $msg,
        ]);

        return $response->body();
    }
}
