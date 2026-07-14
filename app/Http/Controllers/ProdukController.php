<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\DigiFlazzController;

use App\Http\Controllers\provider\topupedia\TopupediaController;
use App\Models\Layanan;
use App\Models\Kategori;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function get($provider = null)
    {
        $error = null;
        try {
            $pricelist = Cache::remember('digiflazz_pricelist_cache', 600, function () {
                $data = $this->getFullDigiflazzPricelist();
                return !empty($data) ? $data : null;
            });
            if (!$pricelist) {
                $error = 'Gagal mengambil data dari API Digiflazz (Pricelist Kosong/Limit)';
                Cache::forget('digiflazz_pricelist_cache');
            }
        } catch (\Exception $e) {
            $pricelist = null;
            $error = $e->getMessage();
            Cache::forget('digiflazz_pricelist_cache');
        }

        $groupedBrands = [
            'Pulsa' => [],
            'Data' => [],
            'Games' => [],
            'Voucher' => [],
            'E-Money' => [],
            'PLN' => [],
            'Paket SMS & Telpon' => [],
            'Aktivasi Voucher' => [],
            'TV' => [],
            'Masa Aktif' => [],
            'Lainnya' => []
        ];

        if ($pricelist) {
            foreach ($pricelist as $product) {
                if (empty($product['brand'])) continue;

                $brand = $product['brand'];
                $category = strtolower($product['category'] ?? '');

                // Guess the group
                $group = 'Lainnya';
                if (str_contains($category, 'pulsa')) {
                    $group = 'Pulsa';
                } elseif (str_contains($category, 'data') || str_contains($category, 'internet')) {
                    $group = 'Data';
                } elseif (str_contains($category, 'game')) {
                    $group = 'Games';
                } elseif (str_contains($category, 'voucher')) {
                    $group = 'Voucher';
                } elseif (str_contains($category, 'e-money') || str_contains($category, 'emoney') || str_contains($category, 'saldo')) {
                    $group = 'E-Money';
                } elseif (str_contains($category, 'pln')) {
                    $group = 'PLN';
                } elseif (str_contains($category, 'sms') || str_contains($category, 'telpon') || str_contains($category, 'telepon') || str_contains($category, 'paket sms')) {
                    $group = 'Paket SMS & Telpon';
                } elseif (str_contains($category, 'tv')) {
                    $group = 'TV';
                } elseif (str_contains($category, 'aktif')) {
                    $group = 'Masa Aktif';
                } elseif (str_contains($category, 'aktivasi')) {
                    $group = 'Aktivasi Voucher';
                }

                if (!in_array($brand, $groupedBrands[$group])) {
                    $groupedBrands[$group][] = $brand;
                }
            }
        }

        $localKategoris = Kategori::all();

        return view('admin.produk.get', [
            'title' => 'Get Produk',
            'kategoris' => $localKategoris,
            'groupedBrands' => $groupedBrands,
            'pricelist' => $pricelist,
            'error' => $error
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'provider' => 'required|string',
            'kategori' => 'required|string',
            'profit' => 'required|numeric',
            'profit_member' => 'required|numeric',
            'profit_platinum' => 'required|numeric',
            'profit_gold' => 'required|numeric',
        ];

        $messages = [
            'provider.required' => 'Provider is required',
            'kategori.required' => 'Kategori is required.',
            'kategori.string' => 'Kategori must be a string.',
            'profit.required' => 'Profit is required.',
            'profit.numeric' => 'Profit must be a number.',
            'profit_member.required' => 'Profit Member is required.',
            'profit_member.numeric' => 'Profit Member must be a number.',
            'profit_platinum.required' => 'Profit Platinum is required.',
            'profit_platinum.numeric' => 'Profit Platinum must be a number.',
            'profit_gold.required' => 'Profit Gold is required.',
            'profit_gold.numeric' => 'Profit Gold must be a number.',
        ];

        $validatedData = $request->validate($rules, $messages);

        if ($request->provider == "vip") {


            $sign = md5(env("VIP_APIID") . env("VIP_APIKEY"));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://vip-reseller.co.id/api/game-feature');
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "key=" . env("VIP_APIKEY") . "&sign=$sign&type=services");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            $data = json_decode(curl_exec($ch), true);

            if ($data['result'] === true) {
                foreach ($data['data'] as $product) {
                    $kategoriArray = explode(',', $request->kategori);
                    if ($product['status'] === 'available' && in_array($product['game'], $kategoriArray)) {
                        $dataGames = Kategori::where('nama', $product['game'])->first();

                        if ($dataGames) {
                            $layanan = new Layanan();
                            $layanan->kategori_id = $dataGames->id;
                            $layanan->layanan = $product['name'];
                            $layanan->provider_id = $product['code'];
                            $layanan->harga = $product['price']['basic'] + ($product['price']['basic'] * $request->profit / 100);
                            $layanan->harga_member = $product['price']['basic'] + ($product['price']['basic'] * $request->profit_member / 100);
                            $layanan->harga_platinum = $product['price']['basic'] + ($product['price']['basic'] * $request->profit_platinum / 100);
                            $layanan->harga_gold = $product['price']['basic'] + ($product['price']['basic'] * $request->profit_gold / 100);
                            $layanan->profit = $request->profit;
                            $layanan->profit_member = $request->profit_member;
                            $layanan->profit_platinum = $request->profit_platinum;
                            $layanan->profit_gold = $request->profit_gold;
                            $layanan->provider = 'vip';
                            $layanan->catatan = '';
                            $layanan->status = 'available';
                            $layanan->save();
                        }
                    }
                }
                return back()->with('success', 'Berhasil menginput layanan');
            } else {
                echo "API Error: " . $data['message'];
            }
            
        } else if ($request->provider == 'topupedia') {
            $url = 'https://api.topupedia.com/api/v3/variant';
            $your_api_key = '4bf8038f-5d65-43b8-bfb9-da1bd6c9cc9e';

            $data = array(
                'code' => $request->kategori,
            );

            $data_json = json_encode($data);
            $headers = array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $your_api_key,
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);

            $buka = fopen(storage_path('logging.txt'), 'w');

            fwrite($buka, 'test' . $response);

            if ($response === false) {
                return back()->with('error', 'Gagal mengambil data dari API.');
            }

            $responseData = json_decode($response, true);


            if ($responseData === null) {
                return back()->with('error', 'Gagal menguraikan respons JSON dari API.');
            }

            if (isset($responseData['error']) && $responseData['error'] === true) {
                return back()->with('error', 'API mengembalikan kesalahan: ' . $responseData['message']);
            }

            if (isset($responseData['data']) && is_array($responseData['data'])) {
                foreach ($responseData['data'] as $product) {
                    $kategoriArray = explode(',', $request->kategori);
                    if ($product['isActive'] === true) {
                        $dataLayanan =  Layanan::all();
                        
                        if($dataLayanan->where('provider_id', $product['code'])->isNotEmpty()){
                                return back()->with('error', 'Data Sudah Ditambahkan');    
                        }
                        
                        $dataGames = Kategori::where('kode', $request->kategori)->first();
                        
                        // $buka= fopen(storage_path('logging.txt'), 'w');
                    
                        // fwrite($buka,'test '. json_encode($dataGames));

                        if ($dataGames) {
                                $layanan = new Layanan();
                                $layanan->kategori_id = $dataGames->id;
                                $layanan->layanan = $product['name'];
                                $layanan->provider_id = $product['code'];
                                $layanan->harga = $product['price'] + ($product['price'] * $request->profit / 100);
                                $layanan->harga_member = $product['price'] + ($product['price'] * $request->profit_member / 100);
                                $layanan->harga_platinum = $product['price'] + ($product['price'] * $request->profit_platinum / 100);
                                $layanan->harga_gold = $product['price'] + ($product['price'] * $request->profit_gold / 100);
                                $layanan->profit = $request->profit;
                                $layanan->profit_member = $request->profit_member;
                                $layanan->profit_platinum = $request->profit_platinum;
                                $layanan->profit_gold = $request->profit_gold;
                                $layanan->provider = 'topupedia';
                                $layanan->catatan = '';
                                $layanan->status = 'available';
                                $layanan->save();
                        }
                    }
                }
                
                return back()->with('success', 'Berhasil menginput layanan');
            } else {
                return back()->with('error', 'Data layanan tidak valid dari API.');
            }
        }  else if ($request->provider == 'digiflazz') {
           $profit = \DB::table('setting_webs')->where('id',1)->first();
           $data = $this->getFullDigiflazzPricelist();

            if (!empty($data)) {
                $addedCount = 0;
                foreach ($data as $product) {
                    $kategoriArray = explode(',', $request->kategori);
                    if ($product['buyer_product_status'] == true && in_array($product['brand'], $kategoriArray)) {
                        $dataGames = Kategori::where('nama', $product['brand'])->first();

                        if (!$dataGames) {
                            $tipe = 'game';
                            $categoryLower = strtolower($product['category'] ?? '');
                            if (str_contains($categoryLower, 'e-money') || str_contains($categoryLower, 'emoney') || str_contains($categoryLower, 'saldo')) {
                                $tipe = 'pulsa';
                            } elseif (str_contains($categoryLower, 'voucher')) {
                                $tipe = 'voucher';
                            } elseif (str_contains($categoryLower, 'pulsa') || str_contains($categoryLower, 'data') || str_contains($categoryLower, 'paket') || str_contains($categoryLower, 'pln') || str_contains($categoryLower, 'token')) {
                                $tipe = 'pulsa';
                            } elseif (str_contains($categoryLower, 'app') || str_contains($categoryLower, 'premium') || str_contains($categoryLower, 'streaming')) {
                                $tipe = 'app';
                            }

                             $dataGames = new Kategori();
                             $dataGames->nama = $product['brand'];
                             $dataGames->sub_nama = $product['brand'];
                             $dataGames->kode = Str::slug($product['brand']);
                             $dataGames->server_id = 0;
                             $dataGames->tipe = $tipe;
                             $dataGames->thumbnail = $this->getBrandLogo($product['brand']);
                             $dataGames->banner = '/assets/banner_game/default.png';
                             $dataGames->status = 'active';
                             $dataGames->deskripsi_game = 'Topup ' . $product['brand'] . ' instan 24 jam aman.';
                             $dataGames->deskripsi_field = 'Masukkan nomor HP atau ID target Anda.';
                             $dataGames->save();

                            DB::table('custom_inputs')->insert([
                                'kategori_id' => $dataGames->id,
                                'field_1' => 'Target ID / No HP,id,number',
                                'field_2' => null,
                                'field_select_title' => null,
                                'field_select' => null,
                            ]);
                        }

                        $existingLayanan = Layanan::where('provider_id', $product['buyer_sku_code'])->first();
                        if (!$existingLayanan) {
                            $layanan = new Layanan();
                            $layanan->kategori_id = $dataGames->id;
                            $layanan->layanan = $product['product_name'];
                            $layanan->provider_id = $product['buyer_sku_code'];
                            $layanan->harga = $product['price'] + ($product['price'] * $request->profit / 100);
                            $layanan->harga_member = $product['price'] + ($product['price'] * $request->profit_member / 100);
                            $layanan->harga_platinum = $product['price'] + ($product['price'] * $request->profit_platinum / 100);
                            $layanan->harga_gold = $product['price'] + ($product['price'] * $request->profit_gold / 100);
                            $layanan->profit = $request->profit;
                            $layanan->profit_member = $request->profit_member;
                            $layanan->profit_platinum = $request->profit_platinum;
                            $layanan->profit_gold = $request->profit_gold;
                            $layanan->provider = 'digiflazz';
                            $layanan->catatan = '';
                            $layanan->status = 'available';
                            $layanan->save();
                            $addedCount++;
                        }
                    }

                }
                return back()->with('success', "Berhasil menginput {$addedCount} layanan dari Digiflazz (Prepaid + Pasca).");
            } else {
                return back()->with('error', 'Gagal mengambil data dari API Digiflazz. Silakan coba beberapa saat lagi.');
            }

        }


    }
    
    public function sync(Request $request)
    {
        try {
            $pricelist = $this->getFullDigiflazzPricelist();

            if (empty($pricelist)) {
                if (app()->runningInConsole()) {
                    return [
                        'success' => false,
                        'message' => 'Gagal mengambil data pricelist dari API Digiflazz (Prepaid & Pasca kosong).'
                    ];
                }
                return back()->with('error', 'Gagal mengambil data pricelist dari API Digiflazz (Prepaid & Pasca kosong).');
            }

            // Ambil profit default website untuk backup
            $defaultProfit = DB::table('setting_webs')->where('id', 1)->first();
            $pPublik = $defaultProfit->profit_public ?? 5;
            $pMember = $defaultProfit->profit_member ?? 4;
            $pPlatinum = $defaultProfit->profit_platinum ?? 3;
            $pGold = $defaultProfit->profit_gold ?? 2;

            $syncedSkuCodes = [];
            $addedCategories = 0;
            $addedProducts = 0;
            $updatedProducts = 0;
            $deactivatedProducts = 0;

            foreach ($pricelist as $product) {
                if (empty($product['brand']) || empty($product['buyer_sku_code'])) {
                    continue;
                }

                $brand = trim($product['brand']);
                $sku = trim($product['buyer_sku_code']);
                $category = trim($product['category'] ?? '');
                $productName = trim($product['product_name']);
                $price = floatval($product['price'] ?? 0);
                $isActive = ($product['buyer_product_status'] == true);

                $syncedSkuCodes[] = $sku;

                // 1. Sync Kategori
                $dataGames = Kategori::where('nama', $brand)->first();
                if (!$dataGames) {
                    $slug = Str::slug($brand);
                    // Tebak tipe
                    $tipe = 'game';
                    $categoryLower = strtolower($category);
                    if (str_contains($categoryLower, 'e-money') || str_contains($categoryLower, 'emoney') || str_contains($categoryLower, 'saldo')) {
                        $tipe = 'pulsa';
                    } elseif (str_contains($categoryLower, 'voucher')) {
                        $tipe = 'voucher';
                    } elseif (str_contains($categoryLower, 'pulsa') || str_contains($categoryLower, 'data') || str_contains($categoryLower, 'paket') || str_contains($categoryLower, 'pln') || str_contains($categoryLower, 'token')) {
                        $tipe = 'pulsa';
                    } elseif (str_contains($categoryLower, 'app') || str_contains($categoryLower, 'premium') || str_contains($categoryLower, 'streaming')) {
                        $tipe = 'app';
                    }

                    $dataGames = new Kategori();
                    $dataGames->nama = $brand;
                    $dataGames->sub_nama = $brand;
                    $dataGames->kode = $slug;
                    $dataGames->server_id = 0;
                    $dataGames->tipe = $tipe;
                    $dataGames->thumbnail = $this->getBrandLogo($brand);
                    $dataGames->banner = '/assets/banner_game/default.png';
                    $dataGames->status = 'active';
                    $dataGames->deskripsi_game = 'Topup ' . $brand . ' instan 24 jam aman.';
                    $dataGames->deskripsi_field = 'Masukkan nomor HP atau ID target Anda.';
                    $dataGames->save();

                    DB::table('custom_inputs')->insert([
                        'kategori_id' => $dataGames->id,
                        'field_1' => 'Target ID / No HP,id,number',
                        'field_2' => null,
                        'field_select_title' => null,
                        'field_select' => null,
                    ]);

                    $addedCategories++;
                }

                // 2. Sync Layanan (Produk)
                $dataProduct = Layanan::where('provider_id', $sku)->first();

                if ($isActive) {
                    if ($dataProduct) {
                        // Update harga produk yang sudah ada menggunakan persentase profit lamanya
                        $profit = $dataProduct->profit ?? $pPublik;
                        $profit_member = $dataProduct->profit_member ?? $pMember;
                        $profit_platinum = $dataProduct->profit_platinum ?? $pPlatinum;
                        $profit_gold = $dataProduct->profit_gold ?? $pGold;

                        $dataProduct->harga = $price + ($price * $profit / 100);
                        $dataProduct->harga_member = $price + ($price * $profit_member / 100);
                        $dataProduct->harga_platinum = $price + ($price * $profit_platinum / 100);
                        $dataProduct->harga_gold = $price + ($price * $profit_gold / 100);
                        $dataProduct->status = 'available';
                        $dataProduct->save();

                        $updatedProducts++;
                    } else {
                        // Tambahkan produk baru dengan profit default
                        $layanan = new Layanan();
                        $layanan->kategori_id = $dataGames->id;
                        $layanan->layanan = $productName;
                        $layanan->provider_id = $sku;
                        $layanan->harga = $price + ($price * $pPublik / 100);
                        $layanan->harga_member = $price + ($price * $pMember / 100);
                        $layanan->harga_platinum = $price + ($price * $pPlatinum / 100);
                        $layanan->harga_gold = $price + ($price * $pGold / 100);
                        $layanan->profit = $pPublik;
                        $layanan->profit_member = $pMember;
                        $layanan->profit_platinum = $pPlatinum;
                        $layanan->profit_gold = $pGold;
                        $layanan->provider = 'digiflazz';
                        $layanan->catatan = '';
                        $layanan->status = 'available';
                        $layanan->save();

                        $addedProducts++;
                    }
                } else {
                    // Jika produk tidak aktif di Digiflazz, set status lokal menjadi tidak tersedia
                    if ($dataProduct) {
                        $dataProduct->status = 'empty';
                        $dataProduct->save();
                        $deactivatedProducts++;
                    }
                }
            }

            // 3. Deaktivasi produk lokal provider 'digiflazz' yang sudah dihapus dari API Digiflazz
            $removedCount = Layanan::where('provider', 'digiflazz')
                ->whereNotIn('provider_id', $syncedSkuCodes)
                ->update(['status' => 'empty']);

            $deactivatedProducts += $removedCount;

            // Bersihkan cache agar daftar harga terbaru langsung tampil
            Cache::forget('digiflazz_pricelist_cache');

            if (app()->runningInConsole()) {
                return [
                    'success' => true,
                    'message' => "Sinkronisasi otomatis berhasil! Menambahkan {$addedCategories} kategori baru, {$addedProducts} produk baru, memperbarui {$updatedProducts} produk, dan menonaktifkan {$deactivatedProducts} produk tidak aktif/dihapus."
                ];
            }

            return back()->with('success', "Sinkronisasi otomatis berhasil! Menambahkan {$addedCategories} kategori baru, {$addedProducts} produk baru, memperbarui {$updatedProducts} produk, dan menonaktifkan {$deactivatedProducts} produk tidak aktif/dihapus.");

        } catch (\Exception $e) {
            if (app()->runningInConsole()) {
                return [
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat sinkronisasi otomatis: ' . $e->getMessage()
                ];
            }
            return back()->with('error', 'Terjadi kesalahan saat sinkronisasi otomatis: ' . $e->getMessage());
        }
    }

public function syncAllDigiflazz(Request $request)
{
    $request->validate([
        'profit' => 'required|numeric',
        'profit_member' => 'required|numeric',
        'profit_platinum' => 'required|numeric',
        'profit_gold' => 'required|numeric',
    ]);

    try {
        $pricelist = Cache::remember('digiflazz_pricelist_cache', 600, function () {
            $data = $this->getFullDigiflazzPricelist();
            return !empty($data) ? $data : null;
        });

        if (!$pricelist) {
            return back()->with('error', 'Gagal mengambil data dari API Digiflazz (Limit/Error). Silakan coba beberapa saat lagi.');
        }

        $addedCount = 0;
        $updatedCount = 0;

        foreach ($pricelist as $product) {
            if (empty($product['brand']) || $product['buyer_product_status'] != true) {
                continue;
            }

            // 1. Dapatkan atau buat Kategori
            $dataGames = Kategori::where('nama', $product['brand'])->first();
            if (!$dataGames) {
                $tipe = 'game';
                $categoryLower = strtolower($product['category'] ?? '');
                if (str_contains($categoryLower, 'e-money') || str_contains($categoryLower, 'emoney') || str_contains($categoryLower, 'saldo')) {
                    $tipe = 'pulsa';
                } elseif (str_contains($categoryLower, 'voucher')) {
                    $tipe = 'voucher';
                } elseif (str_contains($categoryLower, 'pulsa') || str_contains($categoryLower, 'data') || str_contains($categoryLower, 'paket') || str_contains($categoryLower, 'pln') || str_contains($categoryLower, 'token')) {
                    $tipe = 'pulsa';
                } elseif (str_contains($categoryLower, 'app') || str_contains($categoryLower, 'premium') || str_contains($categoryLower, 'streaming')) {
                    $tipe = 'app';
                }

                $dataGames = new Kategori();
                $dataGames->nama = $product['brand'];
                $dataGames->sub_nama = $product['brand'];
                $dataGames->kode = Str::slug($product['brand']);
                $dataGames->server_id = 0;
                $dataGames->tipe = $tipe;
                $dataGames->thumbnail = $this->getBrandLogo($product['brand']);
                $dataGames->banner = '/assets/banner_game/default.png';
                $dataGames->status = 'active';
                $dataGames->deskripsi_game = 'Topup ' . $product['brand'] . ' instan 24 jam aman.';
                $dataGames->deskripsi_field = 'Masukkan nomor HP atau ID target Anda.';
                $dataGames->save();

                DB::table('custom_inputs')->insert([
                    'kategori_id' => $dataGames->id,
                    'field_1' => 'Target ID / No HP,id,number',
                    'field_2' => null,
                    'field_select_title' => null,
                    'field_select' => null,
                ]);
            }

            // 2. Dapatkan atau buat Layanan
            $dataProduct = Layanan::where('provider_id', $product['buyer_sku_code'])->first();
            if ($dataProduct) {
                // Update Layanan menggunakan profit lamanya yang sudah berjalan
                $profit = $dataProduct->profit;
                $profit_member = $dataProduct->profit_member;
                $profit_platinum = $dataProduct->profit_platinum;
                $profit_gold = $dataProduct->profit_gold;

                $harga = $product['price'];
                $dataProduct->harga = $harga + ($harga * $profit / 100);
                $dataProduct->harga_member = $harga + ($harga * $profit_member / 100);
                $dataProduct->harga_platinum = $harga + ($harga * $profit_platinum / 100);
                $dataProduct->harga_gold = $harga + ($harga * $profit_gold / 100);
                $dataProduct->save();
                
                $updatedCount++;
            } else {
                // Tambahkan Layanan Baru menggunakan profit default dari form input
                $layanan = new Layanan();
                $layanan->kategori_id = $dataGames->id;
                $layanan->layanan = $product['product_name'];
                $layanan->provider_id = $product['buyer_sku_code'];
                $layanan->harga = $product['price'] + ($product['price'] * $request->profit / 100);
                $layanan->harga_member = $product['price'] + ($product['price'] * $request->profit_member / 100);
                $layanan->harga_platinum = $product['price'] + ($product['price'] * $request->profit_platinum / 100);
                $layanan->harga_gold = $product['price'] + ($product['price'] * $request->profit_gold / 100);
                $layanan->profit = $request->profit;
                $layanan->profit_member = $request->profit_member;
                $layanan->profit_platinum = $request->profit_platinum;
                $layanan->profit_gold = $request->profit_gold;
                $layanan->provider = 'digiflazz';
                $layanan->catatan = '';
                $layanan->status = 'available';
                $layanan->save();

                $addedCount++;
            }
        }

        return back()->with('success', "Berhasil sinkronisasi masal seluruh produk Digiflazz! Menambahkan {$addedCount} layanan baru, dan memperbarui {$updatedCount} layanan lama.");

    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan saat sinkronisasi masal: ' . $e->getMessage());
    }
}


public function synctopupedia(Request $request) {
    $aoshi = new TopupediaController;
    $data = $aoshi->listVariant($request->kategori);
    
    // Mengosongkan file logging.txt sebelum menulis informasi baru
    file_put_contents(storage_path('logging.txt'), '');

    if (isset($data['data']) && is_array($data['data'])) {
        foreach ($data['data'] as $product) {
            if ($product['isActive'] === true) {
                $dataGames = Kategori::where('kode', $request->kategori)->first();
                $dataProduct = Layanan::where('provider_id', $product['code'])->first();

                if ($dataProduct) {
                    // Ambil profit dari Layanan
                    $profit = $dataProduct->profit;
                    $profit_member = $dataProduct->profit_member;
                    $profit_platinum = $dataProduct->profit_platinum;
                    $profit_gold = $dataProduct->profit_gold;

                    // Hitung harga baru berdasarkan price dari API dan profit dari Layanan
                    $newHarga = $product['price'] + ($product['price'] * $profit / 100);
                    $newHargaMember = $product['price'] + ($product['price'] * $profit_member / 100);
                    $newHargaPlatinum = $product['price'] + ($product['price'] * $profit_platinum / 100);
                    $newHargaGold = $product['price'] + ($product['price'] * $profit_gold / 100);

                    // Update data produk
                    $dataProduct->update([
                        'provider_id' => $product['code'],
                        'harga' => $newHarga,
                        'harga_member' => $newHargaMember,
                        'harga_platinum' => $newHargaPlatinum,
                        'harga_gold' => $newHargaGold,
                    ]);

                    // Tulis ke file logging.txt untuk debugging
                    $logMessage = "Produk: {$product['code']}, Harga API: {$product['price']}, " .
                                  "Profit: {$profit}%, Harga Lama: {$dataProduct->harga}, " .
                                  "Harga Baru: {$newHarga}, Harga Member Baru: {$newHargaMember}, " .
                                  "Harga Platinum Baru: {$newHargaPlatinum}, Harga Gold Baru: {$newHargaGold}" . PHP_EOL;

                    file_put_contents(storage_path('logging.txt'), $logMessage, FILE_APPEND);
                } else {
                    // Jika $dataProduct null, tulis ke logging.txt
                    $logMessage = "dataProduct is null for product code: {$product['code']}" . PHP_EOL;
                    file_put_contents(storage_path('logging.txt'), $logMessage, FILE_APPEND);
                }
            }
        }
        return back()->with('success', 'Berhasil Update Harga Produk API Topupedia');
    } else {
        return redirect('/layanan')->with('error', 'Data Layanan Tidak Valid Dari API!');
    }
}



public function detail($id)
{
    $categories = \DB::table('kategoris')->select('id', 'nama')->get();

    $send = "
            <form action='".route("detail.produk.get.update", [$id])."' method='POST' enctype='multipart/form-data'>
                <input type='hidden' name='_token' value='".csrf_token()."'>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='category-select'>Kategori</label>
                    <div class='col-lg-10'>
                        <select class='form-control' id='category-select' name='category_id'>
                            <option value=''>Pilih Kategori</option>";

    foreach ($categories as $category) {
        $send .= "<option value='".$category->id."'>".$category->nama."</option>";
    }

    $send .= "        </select>
                    </div>
                </div>
                
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='profit'>Profit Public</label>
                    <div class='col-lg-10'>
                        <input type='text' class='form-control' name='profit' required>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='profit_member'>Profit Member</label>
                    <div class='col-lg-10'>
                        <input type='text' class='form-control' name='profit_member' required>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='profit_platinum'>Profit Platinum</label>
                    <div class='col-lg-10'>
                        <input type='text' class='form-control' name='profit_platinum' required>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='profit_gold'>Profit Gold</label>
                    <div class='col-lg-10'>
                        <input type='text' class='form-control' name='profit_gold' required>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Close</button>
                    <button type='submit' class='btn btn-primary'>Simpan</button>
                </div>
            </form>
    ";

    return $send;        
}

public function patch(Request $request, $id)
{
    $category_id = $request->category_id;

    \DB::table('layanans')
        ->where('kategori_id', $category_id)
        ->update([
            'profit' => $request->profit,
            'profit_member' => $request->profit_member,
            'profit_platinum' => $request->profit_platinum,
            'profit_gold' => $request->profit_gold,
        ]);

    $kategori = \DB::table('kategoris')->where('id', $category_id)->value('nama');

    return redirect()->back()->with('success', 'Profit berhasil diperbarui untuk kategori: ' . $kategori. ' Silahkan klik sync untuk merubah harga');
}

    private function getBrandLogo($brand)
    {
        $slug = Str::slug($brand);
        $dir = public_path('assets/thumbnail');
        
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                $filename = strtolower($file);
                
                // Cek jika nama file mengandung slug
                if (str_contains($filename, $slug)) {
                    return '/assets/thumbnail/' . $file;
                }
                
                // Atau sebaliknya (misal slug mengandung nama file tanpa ekstensi)
                $fileWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
                if (str_contains($slug, $fileWithoutExt) && strlen($fileWithoutExt) > 3) {
                    return '/assets/thumbnail/' . $file;
                }
            }
        }

        // Manual mapping fallback untuk merk-merk terkenal
        $mapping = [
            'telkomsel' => '/assets/thumbnail/telkmsel.webp',
            'xl' => '/assets/thumbnail/axis.png', 
            'axis' => '/assets/thumbnail/axis.png',
            'by.u' => '/assets/thumbnail/byu.png',
            'byu' => '/assets/thumbnail/byu.png',
            'indosat' => '/assets/thumbnail/indosat.png',
            'smartfren' => '/assets/thumbnail/Smartfren_2011.webp',
            'gopay' => '/assets/thumbnail/1568261197-gopay-small.webp',
            'dana' => '/assets/thumbnail/dana.png',
            'ovo' => '/assets/thumbnail/Logo_dana_blue.svg.png',
            'shopeepay' => '/assets/thumbnail/dana.png',
            'free fire' => '/assets/thumbnail/free_fire.png',
            'mobile legends' => '/assets/thumbnail/mlbbbill_11zon.webp',
            'pubg' => '/assets/thumbnail/pubgbill_11zon.webp',
            'netflix' => '/assets/thumbnail/netflix.jpg',
        ];

        foreach ($mapping as $key => $path) {
            if (str_contains(strtolower($brand), $key)) {
                return $path;
            }
        }

        return '/assets/thumbnail/default.png';
    }

    private function getFullDigiflazzPricelist()
    {
        $digi = new DigiFlazzController();
        $apiSetting = DB::table('setting_webs')->where('id', 1)->first();
        if (!$apiSetting) {
            return [];
        }

        // Fetch prepaid
        $dataPrepaid = $digi->harga();

        // Fetch postpaid
        $signPasca = md5($apiSetting->username_digi . $apiSetting->api_key_digi . "pricelist");
        $dataPostpaid = $digi->connect('/v1/price-list', [
            'cmd' => 'pasca',
            'username' => $apiSetting->username_digi,
            'sign' => $signPasca
        ]);

        $pricelist = [];
        if (isset($dataPrepaid['data']) && is_array($dataPrepaid['data'])) {
            $pricelist = array_merge($pricelist, $dataPrepaid['data']);
        }
        if (isset($dataPostpaid['data']) && is_array($dataPostpaid['data'])) {
            $pricelist = array_merge($pricelist, $dataPostpaid['data']);
        }

        return $pricelist;
    }
}