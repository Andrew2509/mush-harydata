<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZerosDev\TriPay\Client as TriPayClient;
use ZerosDev\TriPay\Support\Constant;
use ZerosDev\TriPay\Support\Helper;
use ZerosDev\TriPay\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TripayController extends Controller
{
    private $client;

    public function __construct()
    {
        $api = DB::table('setting_webs')->where('id', 1)->first();
        
        $merchantCode = $api->tripay_merchant_code ?? env('TRIPAY_MERCHANT_CODE', '');
        $apiKey = $api->tripay_api ?? env('TRIPAY_API_KEY', '');
        $privateKey = $api->tripay_private_key ?? env('TRIPAY_PRIVATE_KEY', '');
        
        // Detect mode based on API key prefix: DEV- = sandbox, otherwise = production
        $mode = (str_starts_with($apiKey, 'DEV-')) ? Constant::MODE_DEVELOPMENT : Constant::MODE_PRODUCTION;
        
        $this->client = new TriPayClient($merchantCode, $apiKey, $privateKey, $mode);
    }

    /**
     * Create a new transaction via Tripay
     * 
     * @param int $amount Total amount of the transaction
     * @param string $ref_id Unique reference ID (order ID)
     * @param string $payment_method Code of the payment method in Tripay
     * @param string $customer_name Buyer name
     * @param string $customer_phone Buyer phone
     * @param string $customer_email Buyer email
     * @param array $items Array of order items [['name' => 'Item X', 'price' => 10000, 'quantity' => 1]]
     * @return array Returns status and related payment data
     */
    public function createTransaction($amount, $ref_id, $payment_method, $customer_name, $customer_phone, $customer_email, $items = [])
    {
        try {
            $api = DB::table('setting_webs')->where('id', 1)->first();
            
            $merchantCode = $api->tripay_merchant_code ?? env('TRIPAY_MERCHANT_CODE', '');
            $apiKey = $api->tripay_api ?? env('TRIPAY_API_KEY', '');
            $privateKey = $api->tripay_private_key ?? env('TRIPAY_PRIVATE_KEY', '');
            
            // Determine API URL based on key prefix
            $baseUrl = (str_starts_with($apiKey, 'DEV-')) 
                ? 'https://tripay.co.id/api-sandbox' 
                : 'https://tripay.co.id/api';

            // Build order items
            if (empty($items)) {
                $orderItems = [
                    ['name' => 'Topup Service', 'price' => (int) $amount, 'quantity' => 1]
                ];
            } else {
                $orderItems = $items;
            }

            // Generate signature
            $signature = hash_hmac('sha256', $merchantCode . $ref_id . $amount, $privateKey);

            $payload = [
                'method'         => $payment_method,
                'merchant_ref'   => $ref_id,
                'amount'         => (int) $amount,
                'customer_name'  => $customer_name ?: 'Customer',
                'customer_email' => empty($customer_email) ? 'customer@mustopup.com' : $customer_email,
                'customer_phone' => empty($customer_phone) ? '081234567890' : $customer_phone,
                'order_items'    => $orderItems,
                'expired_time'   => time() + (3 * 60 * 60), // 3 hours
                'return_url'     => url('/id/invoices/' . $ref_id),
                'signature'      => $signature,
            ];

            Log::info('Tripay Create Transaction Request', ['url' => $baseUrl . '/transaction/create', 'method' => $payment_method, 'amount' => $amount]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $baseUrl . '/transaction/create');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 600);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($response === false) {
                Log::error('Tripay cURL Error: ' . $error);
                return [
                    'status' => 'Error',
                    'data' => 'Connection Error: ' . $error
                ];
            }

            Log::info('Tripay Response', ['httpCode' => $httpCode, 'body' => substr($response, 0, 500)]);

            $result = json_decode($response, true);

            if (isset($result['success']) && $result['success'] == true) {
                return [
                    'status' => 'Success',
                    'data' => $result['data']
                ];
            } else {
                Log::error('Tripay Transaction Error: ', $result ?? []);
                return [
                    'status' => 'Failed',
                    'data' => $result['message'] ?? 'Unknown error from Tripay'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Tripay Exception: ' . $e->getMessage());
            return [
                'status' => 'Error',
                'data' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle incoming callback from Tripay
     */
    public function handleCallback(Request $request)
    {
        $json = $request->getContent();
        Log::info('Tripay Callback Received', [
            'ip' => $request->ip(),
            'event' => $request->server('HTTP_X_CALLBACK_EVENT'),
            'content_length' => strlen($json),
            'content_sample' => substr($json, 0, 100)
        ]);

        $api = DB::table('setting_webs')->where('id', 1)->first();
        $privateKey = $api->tripay_private_key ?? env('TRIPAY_PRIVATE_KEY', '');
        
        if (empty($privateKey)) {
            Log::error('Tripay Callback: Private Key is empty! Check database or .env');
        }
        
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $signature = hash_hmac('sha256', $json, $privateKey);

        if ($signature !== (string) $callbackSignature) {
            Log::warning('Tripay Callback: Invalid signature', [
                'ip' => $request->ip(),
                'received_len' => strlen((string)$callbackSignature),
                'calculated_len' => strlen($signature),
                'content_sha256' => hash('sha256', $json)
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid signature',
            ], 400);
        }

        if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
            Log::warning('Tripay Callback: Unrecognized event', ['event' => $request->server('HTTP_X_CALLBACK_EVENT')]);
            return response()->json([
                'success' => false,
                'message' => 'Unrecognized event',
            ], 400);
        }

        $data = json_decode($json);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data sent by Tripay',
            ], 400);
        }

        $tripayReference = $data->reference;
        $merchantRef = $data->merchant_ref;
        $status = strtoupper((string) $data->status);

        Log::info('Tripay Callback Processing', [
            'reference' => $tripayReference,
            'merchant_ref' => $merchantRef,
            'status' => $status
        ]);

        if ($status === 'PAID') {
            $invoice = \App\Models\Pembayaran::where('reference', $tripayReference)->where('status', 'Belum Lunas')->first();
            if (!$invoice) {
                $invoice = \App\Models\Pembayaran::where('order_id', $merchantRef)->where('status', 'Belum Lunas')->first();
            }

            if (!$invoice) {
                Log::warning('Tripay Callback: Invoice tidak ditemukan', ['reference' => $tripayReference, 'merchant_ref' => $merchantRef]);
                return response()->json([
                    'success' => false,
                    'message' => 'No invoice found or already paid: ' . $tripayReference,
                ]);
            }

            $order_id = $invoice->order_id;
            $dataPembeli = \App\Models\Pembelian::where('order_id', $order_id)->first();
            $dataDeposit = \App\Models\Deposit::where('order_id', $order_id)->first();

            $orderStatus = false;

            if ($dataDeposit) {
                $userDeposit = \App\Models\User::where('username', $dataDeposit->username)->first();
                $orderStatus = true;
                
                if ($orderStatus) { 
                    $userDeposit->update([
                        'balance' => $dataDeposit->jumlah + $userDeposit->balance,
                    ]);
                    $dataDeposit->update([
                        'status' => 'Success'
                    ]);
                } else {
                    $dataDeposit->update([
                        'status' => 'Gagal'
                    ]);
                }
            } else if ($dataPembeli) {
                $dataLayanan = \App\Models\Layanan::where('layanan', $dataPembeli->layanan)->first();
                
                if ($dataLayanan) {
                    $uid = $dataPembeli->user_id;
                    $zone = $dataPembeli->zone;
                    $provider_id = $dataLayanan->provider_id;
                    
                    if ($dataLayanan->provider == "digiflazz") {
                        try {
                            $random_part = mt_rand(100000, 999999);
                            $provider_order_id = 'REF-HARY' . $random_part;
                            $digiFlazz = new \App\Http\Controllers\digiFlazzController;
                            $order = $digiFlazz->order($uid, $zone, $provider_id, $provider_order_id);
                        
                            Log::info('Digiflazz Order Response', [
                                'order_id' => $order_id,
                                'ref_id' => $provider_order_id,
                                'response' => $order
                            ]);

                            if (isset($order['data']['status']) && ($order['data']['status'] == "Pending" || $order['data']['status'] == "Sukses")) {
                                $orderStatus = true;
                                $orderTransactionId = $provider_order_id;
                            } else {
                                Log::error('Digiflazz Order Gagal', [
                                    'order_id' => $order_id,
                                    'ref_id' => $provider_order_id,
                                    'response' => $order
                                ]);
                            }
                        } catch (\Exception $e) {
                            Log::error('Digiflazz API Exception', [
                                'order_id' => $order_id,
                                'message' => $e->getMessage()
                            ]);
                            $order = ['error' => $e->getMessage()];
                        }
                    } elseif ($dataLayanan->provider == "topupedia") {
                        $topupedia = new \App\Http\Controllers\provider\topupedia\TopupediaController;
                        $ttlpembelian = [["name" => "id", "value" => $uid]];
                        if ($zone != null) { $ttlpembelian[] = ["name" => "server", "value" => $zone]; }
                        
                        $order = $topupedia->order($provider_id, $order_id, 1, $ttlpembelian);
                        if ($order['error'] == false) {
                            $orderTransactionId = $order['data']['invoiceNumber'];
                            $orderStatus = true;
                        }
                    } elseif ($dataLayanan->provider == "bangjeff") {
                        $bangjef = new \App\Http\Controllers\provider\bangjeff\BangjeffController;
                        $ttlpembelian = [["name" => "id", "value" => $uid]];
                        if ($zone != null) { $ttlpembelian[] = ["name" => "server", "value" => $zone]; }
                        
                        $order = $bangjef->order($provider_id, $order_id, 1, $ttlpembelian);
                        if ($order['error'] == false) {
                            $orderTransactionId = $order['data']['invoiceNumber'];
                            $orderStatus = true;
                        }
                    } elseif ($dataLayanan->provider == "aoshi") {
                        $aoshi = new \App\Http\Controllers\provider\aoshi\AoshiController;
                        $ttlpembelian = [["name" => "id", "value" => $uid]];
                        if ($zone != null) { $ttlpembelian[] = ["name" => "server", "value" => $zone]; }
                        
                        $order = $aoshi->order($provider_id, $order_id, 1, $ttlpembelian);
                        if ($order['error'] == false) {
                            $orderTransactionId = $order['data']['invoiceNumber'];
                            $orderStatus = true;
                        }
                    } else {
                        $orderStatus = true;
                        $orderTransactionId = '';
                    }

                    if ($orderStatus) {
                        $dataPembeli->update([
                            'provider_order_id' => $orderTransactionId ?? 0,
                            'status' => 'Proses',
                            'log' => json_encode($order ?? [])
                        ]);
                        
                        Log::info('Tripay Callback: Order berhasil dikirim ke provider', [
                            'order_id' => $order_id,
                            'provider' => $dataLayanan->provider,
                            'provider_order_id' => $orderTransactionId ?? ''
                        ]);

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
                        
                        $this->msg($invoice->no_pembeli, $pesanPembeli);
                    } else {
                        Log::error('Tripay Callback: Order GAGAL dikirim ke provider', [
                            'order_id' => $order_id,
                            'provider' => $dataLayanan->provider,
                            'response' => $order ?? []
                        ]);

                        $dataPembeli->update([
                            'status' => 'Batal',
                            'log' => json_encode($order ?? [])
                        ]);

                        // Refund otomatis ke saldo user jika terdaftar
                        if (!empty($dataPembeli->username)) {
                            $userRefund = \App\Models\User::where('username', $dataPembeli->username)->first();
                            if ($userRefund) {
                                $userRefund->update([
                                    'balance' => $userRefund->balance + $dataPembeli->harga
                                ]);
                                Log::info('Refund otomatis berhasil (provider gagal)', [
                                    'order_id' => $order_id,
                                    'username' => $dataPembeli->username,
                                    'jumlah' => $dataPembeli->harga
                                ]);

                                $pesanRefund = 
                                    "*Top-up Gagal - Dana Dikembalikan*\n\n" .
                                    "No Invoice: *$order_id*\n" .
                                    "Layanan: *$dataPembeli->layanan*\n" .
                                    "Harga: *Rp. " . number_format($dataPembeli->harga, 0, '.', ',') . "*\n\n" .
                                    "Dana sebesar *Rp. " . number_format($dataPembeli->harga, 0, '.', ',') . "* telah dikembalikan ke saldo akun Anda.\n\n" .
                                    "INI ADALAH PESAN OTOMATIS";
                                $this->msg($invoice->no_pembeli, $pesanRefund);
                            }
                        } else {
                            // Guest order - simpan sebagai pending refund
                            \App\Models\PendingRefund::create([
                                'order_id' => $order_id,
                                'no_pembeli' => $invoice->no_pembeli,
                                'jumlah' => $dataPembeli->harga,
                                'layanan' => $dataPembeli->layanan,
                                'status' => 'pending',
                            ]);

                            Log::info('Pending refund dibuat untuk guest (Tripay callback)', [
                                'order_id' => $order_id,
                                'no_pembeli' => $invoice->no_pembeli,
                                'jumlah' => $dataPembeli->harga
                            ]);

                            $pesanRefund = 
                                "*Top-up Gagal*\n\n" .
                                "No Invoice: *$order_id*\n" .
                                "Layanan: *$dataPembeli->layanan*\n" .
                                "Harga: *Rp. " . number_format($dataPembeli->harga, 0, '.', ',') . "*\n\n" .
                                "Silakan login atau daftar akun dengan nomor telepon ini untuk menerima pengembalian dana otomatis ke saldo Anda.\n\n" .
                                "INI ADALAH PESAN OTOMATIS";
                            $this->msg($invoice->no_pembeli, $pesanRefund);
                        }
                    }
                }
            }
            
            $invoice->update(['status' => 'Lunas']);
            Log::info('Tripay Callback: Invoice lunas', ['order_id' => $order_id]);
            return response()->json(['success' => true]);

        } else if ($status === 'EXPIRED' || $status === 'FAILED') {
            $invoice = \App\Models\Pembayaran::where('reference', $tripayReference)->where('status', 'Belum Lunas')->first();
            if (!$invoice) {
                $invoice = \App\Models\Pembayaran::where('order_id', $merchantRef)->where('status', 'Belum Lunas')->first();
            }
            if ($invoice) {
                $invoice->update(['status' => 'Batal']);
                \App\Models\Pembelian::where('order_id', $invoice->order_id)->update(['status' => 'Batal']);
            }
            
            return response()->json(['success' => true]);
        }

        return [
            'status' => 'UNRECOGNIZED',
            'data' => $data
        ];
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
