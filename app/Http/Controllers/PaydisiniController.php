<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class PaydisiniController extends Controller
{
    private $apiUrl = 'https://paydisini.co.id/api/';
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

    /**
     * Membuat Transaksi Baru
     */
    public function createTransaction(Request $request)
    {
          
        Log::info('masuk');
        $serviceMapping = [
                'Virtual Account Bank BCA' => 1,
                'Virtual Account BANK BNC' => 10,
                'QRIS Merchant PayDisini' => 11,
                'QRIS' => 11,
                'OVO' => 12,
                'DANA' => 13,
                'LINKAJA' => 14,
                'ALFAMART' => 18,
                'INDOMARET' => 19,
                'Virtual Account Bank BRI' => 2,
                'QRIS Merchant PayDisini by Danamon' => 20,
                'Virtual Account Bank OCBC' => 21,
                'Virtual Account Bank Muamalat' => 22,
                'QRIS Paydisini Realtime' => 23,
                'Virtual Account Bank CIMB' => 3,
                'Virtual Account Bank BNI' => 4,
                'Virtual Account Bank MANDIRI' => 5,
                'Virtual Account Bank Maybank' => 6,
                'Virtual Account Bank Permata' => 7,
                'Virtual Account BANK DANAMON' => 8,
                'Virtual Account BANK BSI' => 9,
                'BNIVA' => 4,
                'BRIVA' => 2,
                'MANDIRIVA' => 5,
                'QRIS Custom' => 17
            ];
        
            
            $service = $serviceMapping[$request->input('service')] ?? $request->input('service');
        






        $uniqueCode = $request->input('unique_code', 'postman123unikcode');
       // $service = $request->input('service', 17);
        Log::info($service);
        $amount = $request->input('amount');
        $note = $request->input('note', 'Pembayaran pertama');
        $validTime = $request->input('valid_time', 10800);
        $ewalletPhone = $request->input('ewallet_phone', null);
        $typeFee = $request->input('type_fee', 1);

        if (empty($amount) || $amount <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jumlah tidak bisa kosong atau kurang dari 0'
            ], 400);
        }

        $signature = md5($this->apiKey . $uniqueCode . $service . $amount . $validTime . 'NewTransaction');

        $formData = [
            'key' => $this->apiKey,
            'request' => 'new',
            'unique_code' => $uniqueCode,
            'service' => $service,
            'amount' => $amount,
            'note' => $note,
            'valid_time' => $validTime,
            'ewallet_phone' => $ewalletPhone,
            'type_fee' => $typeFee,
            'signature' => $signature,
            'return_url' => env('APP_URL')
        ];

        try {
            $response = Http::asForm()->post($this->apiUrl, $formData);

            Log::info('API Response', ['response' => $response->body()]);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['data'])) {
                    $data = $responseData['data'];
                    $no_pembayaran = $data['qrcode_url'] ?? 
                                     $data['checkout_url'] ?? 
                                     $data['checkout_url_beta'] ?? 
                                     $data['checkout_url_v2'] ?? 
                                     $data['checkout_url_v3'] ?? 
                                     '';

                    if (empty($no_pembayaran)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Respons tidak berisi URL pembayaran yang valid',
                            'data' => $responseData
                        ]);
                    }

                    $amount = $data['amount'] ?? $amount;
                    $reference = $data['unique_code'] ?? '';

                    return response()->json([
                        'status' => 'success',
                        'data' => [
                            'no_pembayaran' => $no_pembayaran,
                            'amount' => $amount,
                            'reference' => $reference
                        ]
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Respons tidak berisi data yang diharapkan',
                        'data' => $responseData
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat transaksi baru',
                    'error' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Memeriksa Status Transaksi
     */
    public function checkTransactionStatus(Request $request)
    {
        $uniqueCode = $request->input('unique_code', 'postman123unikcode');
        $signature = md5($this->apiKey . $uniqueCode . 'StatusTransaction');

        $formData = [
            'key' => $this->apiKey,
            'request' => 'status',
            'unique_code' => $uniqueCode,
            'signature' => $signature
        ];

        try {
            $response = Http::asForm()->post($this->apiUrl, $formData);

            Log::info('API Response', ['response' => $response->body()]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memeriksa status transaksi',
                    'error' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Membatalkan Transaksi
     */
    public function cancelTransaction(Request $request)
    {
        $uniqueCode = $request->input('unique_code', 'postman123unikcode');

        $signature = md5($this->apiKey . $uniqueCode . 'CancelTransaction');

        $formData = [
            'key' => $this->apiKey,
            'request' => 'cancel',
            'unique_code' => $uniqueCode,
            'signature' => $signature
        ];


        try {
            $response = Http::asForm()->post($this->apiUrl, $formData);

            Log::info('API Response', ['response' => $response->body()]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membatalkan transaksi',
                    'error' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Melihat Payment Channel
     */
    public function getPaymentChannel()
    {
        $signature = md5($this->apiKey . 'PaymentChannel');
        $formData = [
            'key' => $this->apiKey,
            'request' => 'payment_channel',
            'signature' => $signature
        ];
        try {
            $response = Http::asForm()->post($this->apiUrl, $formData);

            Log::info('API Response', ['response' => $response->body()]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal melihat payment channel',
                    'error' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Melihat Panduan Pembayaran
     */
    public function getPaymentGuide(Request $request)
    {
        $service = $request->input('service', 11);
        $signature = md5($this->apiKey . $service . 'PaymentGuide');

        $formData = [
            'key' => $this->apiKey,
            'request' => 'payment_guide',
            'service' => $service,
            'signature' => $signature
        ];

        try {
            $response = Http::asForm()->post($this->apiUrl, $formData);

            Log::info('API Response', ['response' => $response->body()]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal melihat panduan pembayaran',
                    'error' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
