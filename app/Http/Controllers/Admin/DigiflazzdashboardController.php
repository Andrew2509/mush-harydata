<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DigiflazzdashboardController extends Controller
{
    private $api;
    private $url;
    private $username_digi;

    public function __construct()
    {
        $setting = DB::table('setting_webs')->where('id', 1)->first();
        if ($setting) {
            $this->api = $setting->api_key_digi ?? null;
            $this->username_digi = $setting->username_digi ?? null;
        } else {
            $this->api = null;
            $this->username_digi = null;
        }
        $this->url = 'https://api.digiflazz.com';
    }

    public function balance()
    {
        try {
            $sign = md5($this->username_digi . $this->api . 'depo');
            $data = $this->connect('/v1/cek-saldo', [
                'cmd' => 'deposit',
                'sign' => $sign
            ]);
            
            if (isset($data['data']['deposit'])) {
                return view('admin.digiflazz.ceksaldobj', ['saldo' => $data['data']['deposit']]);
            } else {
                return view('admin.digiflazz.ceksaldobj', ['error' => $data['data']['message'] ?? 'Gagal mengambil data saldo']);
            }
        } catch (\Exception $e) {
            return view('admin.digiflazz.ceksaldobj', ['error' => $e->getMessage()]);
        }
    }

    public function harga()
    {
        try {
            // Coba ambil dari Cache selama 10 menit (600 detik)
            $cachedData = Cache::remember('digiflazz_pricelist_cache', 600, function () {
                $sign = md5($this->username_digi . $this->api . 'pricelist');
                $data = $this->connect('/v1/price-list', [
                    'cmd' => 'prepaid',
                    'sign' => $sign
                ]);
                
                // Jika sukses, kembalikan data produk. Jika error, kembalikan null agar tidak masuk cache
                if (isset($data['data']) && is_array($data['data']) && isset($data['data'][0])) {
                    return $data['data'];
                }
                
                // Simpan pesan error ke session flash agar bisa ditampilkan sekali
                $errorMsg = $data['data']['message'] ?? $data['message'] ?? 'Gagal mengambil pricelist';
                session()->flash('digiflazz_error', $errorMsg);
                return null;
            });

            if ($cachedData !== null) {
                return view('admin.digiflazz.harga', ['data' => $cachedData]);
            } else {
                $errorMsg = session('digiflazz_error') ?? 'Gagal mengambil pricelist (Limit/API Error)';
                return view('admin.digiflazz.harga', ['error' => $errorMsg]);
            }
        } catch (\Exception $e) {
            return view('admin.digiflazz.harga', ['error' => $e->getMessage()]);
        }
    }

    private function connect($endpoint, $data)
    {
        $payload = array_merge([
            'username' => $this->username_digi,
        ], $data);

        $response = Http::post($this->url . $endpoint, $payload);

        return $response->json();
    }
}
