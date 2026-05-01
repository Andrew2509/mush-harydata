<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

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
            $sign = md5($this->username_digi . $this->api . 'pricelist');
            $data = $this->connect('/v1/price-list', [
                'sign' => $sign
            ]);
            
            if(isset($data['data'])) {
                return view('admin.digiflazz.harga', ['data' => $data['data']]);
            } else {
                return view('admin.digiflazz.harga', ['error' => $data['message'] ?? 'Data structure is invalid']);
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
