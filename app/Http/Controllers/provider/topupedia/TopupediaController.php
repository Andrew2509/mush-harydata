<?php

namespace App\Http\Controllers\provider\topupedia;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Pembayaran;
use App\Models\User;

class TopupediaController extends Controller
{
    private $api;
    private $url;
    public function __construct()
    {
        $this->api = '4bf8038f-5d65-43b8-bfb9-da1bd6c9cc9e';
        $this->url = 'https://api.topupedia.com';
    }
    public function balance()
    {
        $data = $this->go($this->url.'/api/v3/balance');
        
        return $data;
    }
    
    public function getProduct()
    {
        $data = $this->go($this->url.'/api/v3/product');
        
        return $data;
    }
    
    public function listVariant()
    {
        $data = $this->go($this->url.'/api/v3/variant', [
            'code' => 'MLBB'
        ]);
        
        return $data;
    }
    
    public function detailVariant($code)
    {
        $data = $this->go($this->url.'/api/v3/variant/'.$code);
        
        return $data;
    }
    
    
     public function order($code,$ref,$qty,$input)
    {
        $data = $this->go($this->url.'/api/v3/checkout',[
          'code' => $code,
          'referenceNumber' => $ref,
          'qty' => $qty,
          'inputs' => $input
        ]);
        
        return $data;
    }
    
    
    public function checkOrder($invoice)
    {
        $data = $this->go($this->url."/api/v3/order/{$invoice}");
        
        return $data;
    }
    
    public function go($url,$data = [])
    {
        $data =  Http::withToken($this->api)->post($url,$data);
        
        $response = $data->json();
        
        return $response;
        
    }

  public function handleCallback(Request $request)
  {
    $json = $request->getContent();
    $data = json_decode($json, true);

    $poid = $data['invoice_number'];
    $voucher = $data['voucher'];
    $statusCode = $data['status_code'];

    $statusMapped = ($statusCode === "SUCCESS") ? "Sukses" : (($statusCode === "FAILED" || $statusCode === "CANCELLED") ? "Gagal" : $statusCode);

    Log::info(json_encode($data));

    $pembelian = Pembelian::where('provider_order_id', $poid)->first();

        if ($pembelian) {
            $updateData = [
                'status' => $statusMapped
            ];

            if ($pembelian->tipe_transaksi == "voucher") {
                $updateData['voucher'] = $voucher;
            }

            $pembelian->update($updateData);

            $updatePesanan = Pembayaran::where('order_id', $pembelian->order_id)->first();

            if ($statusMapped == 'Sukses') {
                if ($updatePesanan) {
                    $pesanSukses = "*Pembelian Sukses*\n\n" .
                        "No Invoice: *$pembelian->order_id*\n" .
                        "Layanan: *$pembelian->layanan*\n" .
                        "ID : *$pembelian->user_id*\n" .
                        "Server : *$pembelian->zone*\n" .
                        "Nickname : *$pembelian->nickname*\n" .
                        "Harga: *Rp. " . number_format($pembelian->harga, 0, '.', ',') . "*\n" .
                        "Status Pembelian: *Sukses*\n" .
                        "Metode Pembayaran: *$updatePesanan->metode*\n\n" .
                        "*Invoice* : " . env("APP_URL") . "/id/invoices/$pembelian->order_id\n\n" .
                        "INI ADALAH PESAN OTOMATIS";

                    $this->msg($updatePesanan->no_pembeli, $pesanSukses);
                }
            } elseif ($statusMapped == 'Gagal') {
                $this->handleRefund($pembelian, $updatePesanan);
            }
        }
    }

    private function handleRefund($invoice, $updatePesanan)
    {
        $refunded = false;

        if (!empty($invoice->username)) {
            $user = User::where('username', $invoice->username)->first();
            if ($user) {
                $user->update([
                    'balance' => $user->balance + $invoice->harga
                ]);
                $refunded = true;
            }
        }

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
    
    