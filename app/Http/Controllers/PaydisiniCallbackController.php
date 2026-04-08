<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\Kategori;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\digiFlazzController;
use App\Http\Controllers\provider\bangjeff\BangJeffController;

class PaydisiniCallbackController extends Controller
{
    private $apiKey;
    public function __construct()
    {
        try {
            $setting = DB::table('setting_webs')->where('id', 1)->first();
            
            if (!$setting) {
                throw new \Exception('Pengaturan tidak ditemukan');
            }
    
            $this->apiKey = $setting->paydisini ?? '';
        } catch (\Exception $e) {
            // Log error
            Log::error('Gagal mengambil API Key: ' . $e->getMessage());
            
            // Fallback ke default atau .env
            $this->apiKey = config('app.paydisini_api_key', '');
        }
    }
    
    public function callbackTransaction(Request $request)
    {
        Log::info('Paydisini Callback Header', $request->headers->all());
        Log::info('Paydisini Callback Body', $request->all());
        
        $key = $request->input('key');
        $uniqueCode = $request->input('unique_code');
        $status = $request->input('status');
        $signature = $request->input('signature');
        
        if (!$key || !$uniqueCode || !$status || !$signature) {
            Log::warning('Paydisini Callback: Missing required fields');
            return response()->json(['success' => false, 'message' => 'Missing fields'], 400);
        }

        if ($key !== $this->apiKey) {
            Log::error('Paydisini Callback: Invalid API Key', ['received' => $key, 'expected' => $this->apiKey]);
            return response()->json(['success' => false, 'message' => 'Invalid API Key'], 400);
        }

        $expectedSignature = md5($this->apiKey . $uniqueCode . 'CallbackStatus');
        if ($signature !== $expectedSignature) {
            Log::error('Paydisini Callback: Invalid Signature', [
                'received' => $signature,
                'expected' => $expectedSignature,
                'formula' => 'md5(apiKey . uniqueCode . "CallbackStatus")'
            ]);
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 400);
        }

        $transaction = Pembayaran::where('order_id', $uniqueCode)
            ->where('status', 'Belum Lunas')
            ->first();

        if (!$transaction) {
            Log::warning('Paydisini Callback: Transaction not found or already paid', ['order_id' => $uniqueCode]);
            return response()->json(['success' => false, 'message' => 'Transaksi Tidak Di Temukan!!'], 404);
        }

        try {
            if ($status === 'Success') {
                DB::beginTransaction();
                
                $transaction->update(['status' => 'Lunas']);
                
                $pembelian = Pembelian::where('order_id', $uniqueCode)->first();
                if ($pembelian) {
                    $pembelian->update(['status' => 'Proses']); 
                    $this->handleSuccess($pembelian, $transaction);
                } else {
                    $deposit = Deposit::where('order_id', $uniqueCode)->first();
                    if ($deposit) {
                        $user = User::where('username', $deposit->username)->first();
                        if ($user) {
                            $user->update(['balance' => $user->balance + $deposit->jumlah]);
                            $deposit->update(['status' => 'Success']);
                        }
                    }
                }
                
                DB::commit();
                Log::info('Paydisini Callback: Payment processed successfully', ['order_id' => $uniqueCode]);
                return response()->json(['success' => true]);
            } elseif ($status === 'Canceled') {
                $transaction->update(['status' => 'Gagal']);
                $pembelian = Pembelian::where('order_id', $uniqueCode)->first();
                if ($pembelian) {
                    $pembelian->update(['status' => 'Gagal']);
                }
                Log::info('Paydisini Callback: Payment canceled', ['order_id' => $uniqueCode]);
                return response()->json(['success' => true]);
            } else {
                Log::warning('Paydisini Callback: Unknown status received', ['status' => $status]);
                return response()->json(['success' => false, 'message' => 'Invalid status'], 400);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Paydisini Callback Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Server Error'], 500);
        }
    }

    private function handleSuccess($pembelian, $transaction)
    {
        $layanan = Layanan::where('layanan', $pembelian->layanan)->first();
        if ($layanan) {
            $provider = $layanan->provider;
            $user_id = $pembelian->user_id;
            $zone = $pembelian->zone;
            $provider_id = $layanan->provider_id;

            if ($provider === "digiflazz") {
                $random_part = mt_rand(100000, 999999);
                $provider_order_id = 'Terproses Digiflazz - ' . $random_part;
                $digiFlazz = new digiFlazzController;
                $order = $digiFlazz->order($user_id, $zone, $provider_id, $provider_order_id);

                if ($order['data']['status'] === "Pending" || $order['data']['status'] === "Sukses") {
                    $order['data']['status'] = true;
                    $order['transactionId'] = $provider_order_id;
                } else {
                    $order['data']['status'] = false;
                }
            } elseif ($provider === "bangjeff") {
                $bangjeff = new BangJeffController;
                
                $ttlpembelian = [
                    [
                        "name" => "id",
                        "value" => $user_id
                    ]
                ];

                if ($zone !== null) {
                    $ttlpembelian[] = [
                        "name" => "server",
                        "value" => $zone
                    ];
                }
                
                $order = $bangjeff->order($provider_id, $pembelian->order_id, 1, $ttlpembelian);

                if (!$order['error']) {
                    $order['transactionId'] = $order['data']['invoiceNumber'];
                    $order['data']['status'] = true;
                } else {
                    $order['data']['status'] = false;
                }
            } elseif ($provider === "topupedia") {
                $topupedia = new \App\Http\Controllers\provider\topupedia\TopupediaController;
                $ttlpembelian = [["name" => "id", "value" => $user_id]];
                if ($zone !== null) { $ttlpembelian[] = ["name" => "server", "value" => $zone]; }
                $order = $topupedia->order($provider_id, $pembelian->order_id, 1, $ttlpembelian);
                if (!$order['error']) {
                    $order['transactionId'] = $order['data']['invoiceNumber'];
                    $order['data']['status'] = true;
                } else {
                    $order['data']['status'] = false;
                }
            } elseif ($provider === "aoshi") {
                $aoshi = new \App\Http\Controllers\provider\aoshi\AoshiController;
                $ttlpembelian = [["name" => "id", "value" => $user_id]];
                if ($zone !== null) { $ttlpembelian[] = ["name" => "server", "value" => $zone]; }
                $order = $aoshi->order($provider_id, $pembelian->order_id, 1, $ttlpembelian);
                if (!$order['error']) {
                    $order['transactionId'] = $order['data']['invoiceNumber'];
                    $order['data']['status'] = true;
                } else {
                    $order['data']['status'] = false;
                }
            } elseif ($provider === "joki" || $provider === "jokigendong") {
                $provider_order_id = '';
                $order['data']['status'] = true;
            }

            if ($order['data']['status']) {
                $pesanPembeli = 
                    "*Pembayaran Berhasil*\n\n" .
                    "No Invoice: *$pembelian->order_id*\n" .
                    "Layanan : *$pembelian->layanan*\n" .
                    "ID : *$pembelian->user_id*\n" .
                    "Server : *$pembelian->zone*\n" .
                    "Nickname : *$pembelian->nickname*\n" .
                    "Harga : *Rp. " . number_format($pembelian->harga, 0, '.', ',') . "*\n" .
                    "Status Pembelian: *Process*\n" .
                    "Estimasi Proses: *1-5 Menit Max 24 Jam*\n\n" .
                    "INI ADALAH PESAN OTOMATIS";

                if ($pembelian->tipe_transaksi !== 'joki') {
                    $pembelian->update([
                        'provider_order_id' => isset($order['transactionId']) ? $order['transactionId'] : 0,
                        'status' => 'Proses',
                        'log' => json_encode($order)
                    ]);
                    $this->msg($transaction->no_pembeli, $pesanPembeli); 
                } else {
                    $pembelian->update([
                        'provider_order_id' => '', 
                        'status' => 'Proses',
                        'log' => json_encode($order)
                    ]);
                    $this->msg($transaction->no_pembeli, $pesanPembeli); 
                }
            } else {
                if ($pembelian->tipe_transaksi !== 'joki') {
                    $pembelian->update([
                        'status' => 'Batal',
                        'log' => json_encode($order)
                    ]);
                }
                
            }
        } else {
            Log::error('Service Tiidak Ditemukan', ['layanan' => $pembelian->layanan]);
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
