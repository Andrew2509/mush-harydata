<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\Voucher;
use App\Models\Pembelian;
use App\Models\Rating;
use App\Models\Paket;
use App\Models\PaketLayanan;
use App\Models\User;
use App\Models\Berita;
use Illuminate\Support\Facades\Session;
use App\Models\Method;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\digiFlazzController;
use App\Http\Controllers\provider\aoshi\AoshiController;
use App\Http\Controllers\ApiCheckController;
use App\Http\Controllers\MethodController;
use App\Http\Controllers\PaydisiniController;
use App\Http\Controllers\TripayController;
use App\Http\Controllers\provider\bangjeff\BangJeffController;
use App\Http\Controllers\provider\topupedia\TopupediaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function create(Kategori $kategori)
    {
     
        
         if(in_array($kategori->tipe,['game','voucher','pulsa','app', 'populer'])){
            
        $data = Kategori::where('kode', $kategori->kode)->join('custom_inputs', 'kategoris.id', 'custom_inputs.kategori_id')->select('custom_inputs.field_1 AS field_1', 'custom_inputs.field_2 AS field_2', 'custom_inputs.field_select_title AS field_select_title', 'custom_inputs.field_select AS field_select', 'nama', 'sub_nama', 'server_id', 'thumbnail', 'kategoris.id AS id', 'kode', 'petunjuk','deskripsi_game','deskripsi_field','banner','tipe')->first();
        if($data == null) return back();
            
        }else{
            
            $data = Kategori::where('kode', $kategori->kode)->select('nama', 'sub_nama', 'server_id', 'thumbnail', 'kategoris.id AS id', 'kode', 'petunjuk','deskripsi_game','deskripsi_field','banner','tipe')->first();
            if($data == null) return back();
            
        }
        
        
        if(Auth::check()){
            if(Auth::user()->role == "Member"){
                $layanan = Layanan::where('kategori_id', $data->id)->where('status', 'available')->select('id', 'layanan', 'harga_member AS harga', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale','product_logo')->orderBy('harga', 'asc')->get();
            }else if(Auth::user()->role == "Platinum"){
                $layanan = Layanan::where('kategori_id', $data->id)->where('status', 'available')->select('id', 'layanan', 'harga_platinum AS harga', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale','product_logo')->orderBy('harga', 'asc')->get();
            }else if(Auth::user()->role == "Gold" || Auth::user()->role == "Admin"){
                $layanan = Layanan::where('kategori_id', $data->id)->where('status', 'available')->select('id', 'layanan', 'harga_gold AS harga', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale','product_logo')->orderBy('harga', 'asc')->get();
            }
        }else{
            $layanan = Layanan::where('kategori_id', $data->id)->where('status', 'available')->select('id', 'layanan','product_logo', 'harga', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale','product_logo')->orderBy('harga', 'asc')->get();
        }  
        
         $ratings = DB::table('ratings')
            ->join('pembelians', 'ratings.rating_id', '=', 'pembelians.order_id')
            ->join('pembayarans', 'ratings.rating_id', '=', 'pembayarans.order_id')
            ->leftJoin('kategoris', 'ratings.kategori_id', '=', 'kategoris.id')
            ->select([
                'ratings.bintang', 
                'ratings.comment', 
                'ratings.id', 
                'ratings.created_at', 
                'pembelians.username', 
                'pembelians.layanan', 
                'pembayarans.no_pembeli',
                'kategoris.nama AS kategori_nama'
            ])
            ->orderByDesc('ratings.id')
            ->limit(10)
            ->get();

        // Calculate statistics for the reviews component
        $totalReviews = DB::table('ratings')->count();
        $averageRating = DB::table('ratings')->avg('bintang') ?? 0;
        $totalRatingsCount = DB::table('ratings')
            ->select(['bintang', DB::raw('count(*) as count')])
            ->groupBy('bintang')
            ->pluck('count', 'bintang');
        
        $satisfactionCount = DB::table('ratings')->whereIn('bintang', [4, 5])->count();
        $satisfactionPercentage = $totalReviews > 0 ? ($satisfactionCount / $totalReviews) * 100 : 0;

            
            $pakets = [];
        $userRole = Auth::check() ? Auth::user()->role : null;
        
        foreach (Paket::all() as $paket) {
            $layananIds = $paket->layanan->pluck('id')->toArray();
            $layananData = Layanan::whereIn('id', $layananIds)
                            ->where('kategori_id', $data->id)
                            ->where(function ($query) use ($userRole) {
                                if ($userRole == 'Member') {
                                    $query->where('harga_member', '>', 0);
                                } elseif ($userRole == 'Platinum') {
                                    $query->where('harga_platinum', '>', 0);
                                } elseif ($userRole == 'Gold' || $userRole == 'Admin') {
                                    $query->where('harga_gold', '>', 0);
                                } else {
                                    $query->where('harga', '>', 0);
                                }
                            })
                            ->get();
        
            $l = [];
            foreach ($layananData as $lyn) {
                $paketLayanan = PaketLayanan::where('paket_id', $paket->id)
                                ->where('layanan_id', $lyn->id)
                                ->first();
        
                if ($paketLayanan) {
                    if ($userRole == 'Member') {
                        $harga = $lyn->harga_member;
                    } elseif ($userRole == 'Platinum') {
                        $harga = $lyn->harga_platinum;
                    } elseif ($userRole == 'Gold' || $userRole == 'Admin') {
                        $harga = $lyn->harga_gold;
                    } else {
                        $harga = $lyn->harga;
                    }
        
                    $lynData = [
                        'id' => $lyn->id,
                        'layanan' => $lyn->layanan,
                        'product_logo' => $paketLayanan->product_logo,
                        'harga' => $harga,  // Use the dynamically set price
                        'is_flash_sale' => $lyn->is_flash_sale,
                        'expired_flash_sale' => $lyn->expired_flash_sale,
                        'harga_flash_sale' => $lyn->harga_flash_sale,
                        'updated_at' => $lyn->updated_at,
                    ];
        
                    $l[] = $lynData;
                }
            }
        
            if (!empty($l)) {
                $pakets[] = [
                    'nama' => $paket->nama,
                    'layanan' => $l,
                ];
            }
        }

        
        $flashsale = Layanan::join('kategoris', 'kategoris.id', '=', 'layanans.kategori_id')
    ->select('kategoris.thumbnail AS gmr_thumb', 'kategoris.kode AS kode_game', 'layanans.*')
    ->where('layanans.is_flash_sale', 1)
    ->where('layanans.expired_flash_sale', '>=', now())
    ->where('layanans.stock_flash_sale', '>', 0)
    ->get();


        
        return view('order.topup', [
            'title' => $data->nama,
            'description' => 'Top Up ' . $data->nama . ' ' . $kategori->sub_nama . ' murah dan aman di MSSTOREE7. Proses cepat 24 jam otomatis.',
            'kategori' => $data,
            'nominal' => $layanan,
            'pakets' => $pakets,
            'harga' => $layanan,
            'ratings' => $ratings,
            'totalReviews' => $totalReviews,
            'averageRating' => $averageRating,
            'totalRatingsCount' => $totalRatingsCount,
            'satisfactionPercentage' => $satisfactionPercentage,
            'flashsale' => $flashsale,
            'pay_method' => Method::all(),
            'banner_topup' => Berita::whereIn('tipe', ['banner_topup', 'banner_beranda', 'banner'])->latest()->first(),
            'banner_topup_mobile' => Berita::whereIn('tipe', ['banner_topup_mobile', 'banner_beranda_mobile'])->latest()->first(),
            'recent_transactions' => Pembelian::join('layanans', 'pembelians.layanan', '=', 'layanans.layanan')
                ->where('layanans.kategori_id', $data->id)
                ->whereIn('pembelians.status', ['Sukses', 'Success'])
                ->select(['pembelians.*'])
                ->latest('pembelians.created_at')
                ->limit(5)
                ->get(),
            'total_users' => User::count(),
            'total_games' => Kategori::where('status', 'active')->count(),
            'total_products' => Layanan::count(),
            'total_transactions' => Pembelian::count(),
        ]);
    }

    public function price(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->role == "Member") {
                $data = Layanan::where('id', $request->nominal)
                    ->select('harga_member AS harga', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale')
                    ->first();    
            } elseif (Auth::user()->role == "Platinum") {
                $data = Layanan::where('id', $request->nominal)
                    ->select('harga_platinum AS harga', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale')
                    ->first();
            } elseif (Auth::user()->role == "Gold" || Auth::user()->role == "Admin") {
                $data = Layanan::where('id', $request->nominal)
                    ->select('harga_gold AS harga', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale')
                    ->first();    
            }
        } else {
            $data = Layanan::where('id', $request->nominal)
                ->select('harga AS harga', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale')
                ->first();
        }
        if (!$data) {
            return response()->json([
                'status' => false,
                'data' => 'Layanan tidak ditemukan'
            ]);
        }

        if ($data->is_flash_sale == 1 && $data->expired_flash_sale >= date('Y-m-d H:i:s') && $data->stock_flash_sale > 0) {
            $data->harga = $data->harga_flash_sale;
        }
    
         if (in_array($request->ktg_tipe, ['joki', 'jokigendong' , 'vilogml'])) {
            $qty = $request->qty;
            if ($qty <= 0) {
                $qty = 1;
            }
            
            $data->harga *= $qty;
        }
    
        if (isset($request->voucher)) {
            $voucher = Voucher::where('kode', $request->voucher)->first();
            if ($voucher) {
                if ($voucher->stock > 0) {
                    $potongan = $data->harga * ($voucher->promo / 100);
                    if ($potongan > $voucher->max_potongan) {
                        $potongan = $voucher->max_potongan;
                    }
                    $data->harga -= $potongan;
                }
            }
        }
    
        return response()->json([
            'status' => true,
            'harga' => $data->harga
        ]);
    }



    private function generatePremiumModal($title, $desc, $playerData, $purchaseData, $price, $paymentMethod) {
        $html = "<div class='relative w-full max-w-md mx-auto transform transition-all scale-100'>
            <div class='absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-2xl blur opacity-50'></div>
            <div class='relative glass-panel rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden flex flex-col items-center pt-8 pb-6 px-6' style='background: rgba(30, 41, 59, 0.9); backdrop-filter: blur(12px);'>
                <div class='w-16 h-16 rounded-full bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-[0_0_20px_rgba(16,185,129,0.4)] mb-4'>
                    <i class='fas fa-check text-white text-3xl'></i>
                </div>
                <h2 class='text-2xl font-display font-bold text-white mb-2 tracking-wide'>" . htmlspecialchars($title) . "</h2>
                <p class='text-sm text-gray-400 text-center mb-6 max-w-xs leading-relaxed'>" . htmlspecialchars($desc) . "</p>
                
                <div class='w-full bg-black/40 rounded-xl p-5 border border-gray-700/50 mb-6'>
                    <div class='mb-5 pb-5 border-b border-gray-700 border-dashed'>
                        <div class='flex items-center gap-2 mb-3 text-gray-200 font-semibold font-display uppercase tracking-wider text-xs'>
                            <span class='w-1 h-3 bg-emerald-500 rounded-full'></span>Data Player
                        </div>
                        <div class='space-y-2 text-sm'>";
        foreach($playerData as $label => $value) {
            $idAttr = ($label == "Username" || $label == "Nickname") ? " id='nick'" : "";
            $html .= "<div class='flex justify-between items-center'>
                        <span class='text-gray-400'>$label</span>
                        <span class='font-mono text-white font-medium text-right truncate ml-4'$idAttr>$value</span>
                      </div>";
        }
        $html .= "      </div>
                    </div>
                    <div>
                        <div class='flex items-center gap-2 mb-3 text-gray-200 font-semibold font-display uppercase tracking-wider text-xs'>
                            <span class='w-1 h-3 bg-cyan-500 rounded-full'></span>Ringkasan Pembelian
                        </div>
                        <div class='space-y-2 text-sm'>";
        foreach($purchaseData as $label => $value) {
            $html .= "<div class='flex justify-between items-center'>
                        <span class='text-gray-400'>$label</span>
                        <span class='text-white font-medium'>$value</span>
                      </div>";
        }
        $html .= "          <div class='flex justify-between items-center'>
                                <span class='text-gray-400'>Price</span>
                                <span class='text-emerald-500 font-bold font-mono'>$price</span>
                            </div>";
        if ($paymentMethod) {                    
            $html .= "      <div class='flex justify-between items-center mt-2'>
                                <span class='text-gray-400'>Payment</span>
                                <span class='text-white font-bold bg-white/5 px-2 py-0.5 rounded text-xs border border-gray-700 truncatee'>" . htmlspecialchars($paymentMethod) . "</span>
                            </div>";
        }
        $html .= "      </div>
                    </div>
                </div>
                
                <div class='flex gap-3 w-full'>
                    <button onclick='Swal.clickConfirm()' class='flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-green-500 text-white font-bold py-3 px-4 rounded-lg shadow-[0_4px_15px_rgba(16,185,129,0.3)] transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group'>
                        <span>Pesan Sekarang</span>
                        <i class='fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform'></i>
                    </button>
                    <button onclick='Swal.close()' class='flex-1 bg-transparent hover:bg-red-900/20 text-red-500 border border-red-500/30 hover:border-red-500 font-bold py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center'>
                        Batalkan
                    </button>
                </div>
            </div>
        </div>";
        return $html;
    }

    public function confirm(Request $request)
    {
     
       if($request->ktg_tipe === 'jokigendong') {
            $request->validate([
                'nickname_joki' => 'required|string|max:255',
                'tglmain_joki' => 'required|string|max:255',
                'jambooking_joki' => 'required|string|max:255',
                'loginvia_joki' => 'required',
                'catatan_joki' => 'required',
                'service' => 'required|numeric',
                'payment_method' => 'required',
                'nomor' => 'required|numeric',                
            ]);
        } elseif($request->ktg_tipe === 'joki') {
            $request->validate([
                'email_joki' => 'required|string|max:255',
                'password_joki' => 'required|string|max:255',
                'loginvia_joki' => 'required|string|max:255',
                'nickname_joki' => 'required|string|max:255',
                'request_joki' => 'required|string|max:255',
                'catatan_joki' => 'required|string|max:255',
                'service' => 'required|numeric',
                'payment_method' => 'required',
                'nomor' => 'required|numeric',
            ]);
        } elseif($request->ktg_tipe === 'vilogml') {
            $request->validate([
                'email_joki' => 'required|string|max:255',
                'password_joki' => 'required|string|max:255',
                'loginvia_joki' => 'required|string|max:255',
                'nickname_joki' => 'required|string|max:255',
                'request_joki' => 'required|string|max:255',
                'catatan_joki' => 'required|string|max:255',
                'service' => 'required|numeric',
                'payment_method' => 'required',
                'nomor' => 'required|numeric',
            ]);
        } else {
            $request->validate([
                'uid' => 'required|max:25',
                'service' => 'required|numeric',
                'payment_method' => 'required',
                'nomor' => 'required|numeric',                
            ]);
        }

           $item = Layanan::where('id',$request->service)->first();
           if (!$item) return response()->json(['status' => false, 'data' => 'Layanan tidak ditemukan']);
           $produk = Kategori::where('id',$item->kategori_id)->first();
           if (!$produk) return response()->json(['status' => false, 'data' => 'Kategori tidak ditemukan']);
            
            // cek data
            if(Auth::check()){
                if(Auth::user()->role == "Member"){
                    $dataLayanan = Layanan::where('id', $request->service)->select('provider', 'profit_member AS profit', 'harga_member AS harga', 'kategori_id', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale')->first();
                }else if(Auth::user()->role == "Platinum"){
                    $dataLayanan = Layanan::where('id', $request->service)->select('provider', 'profit_platinum AS profit', 'harga_platinum AS harga', 'kategori_id', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale')->first();
                }else if(Auth::user()->role == "Gold" || Auth::user()->role == "Admin"){
                    $dataLayanan = Layanan::where('id', $request->service)->select('provider', 'profit_gold AS profit', 'harga_gold AS harga', 'kategori_id', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale')->first();
                }
            }else{
                $dataLayanan = Layanan::where('id', $request->service)->select('provider', 'profit', 'harga AS harga', 'kategori_id', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale')->first();
            }
            if (!$dataLayanan) return response()->json(['status' => false, 'data' => 'Detail layanan tidak ditemukan']);

            // Cek Saldo Provider
            $provider = $dataLayanan->provider;
            $profitPercent = (float)($dataLayanan->profit ?? 0) / 100;
            $hargaAcuan = $dataLayanan->harga;
            if($dataLayanan->is_flash_sale == 1 && $dataLayanan->expired_flash_sale >= date('Y-m-d H:i:s') && $dataLayanan->stock_flash_sale > 0){
                $hargaAcuan = $dataLayanan->harga_flash_sale;
            }
            $estimasiHargaBeli = $hargaAcuan / (1 + $profitPercent);
            $currentSaldo = null;

            try {
                if ($provider == 'digiflazz') {
                    $digi = new digiFlazzController;
                    $resSaldo = $digi->cekSaldo();
                    $currentSaldo = $resSaldo['data']['deposit'] ?? null;
                } elseif (in_array($provider, ['topupedia', 'bangjeff', 'aoshi'])) {
                    $ctrl = null;
                    if ($provider == 'topupedia') {
                        $ctrl = new TopupediaController;
                    } elseif ($provider == 'bangjeff') {
                        $ctrl = new BangJeffController;
                    } elseif ($provider == 'aoshi') {
                        $ctrl = new AoshiController;
                    }
                    
                    if ($ctrl) {
                        $resSaldo = $ctrl->balance();
                        $currentSaldo = $resSaldo['data']['balance'] ?? null;
                    }
                }

                if ($currentSaldo !== null && $currentSaldo < $estimasiHargaBeli) {
                    return response()->json([
                        'status' => false,
                        'data' => 'Maaf, stok layanan ini sedang kosong (Saldo Provider Menipis). Mohon hubungi admin atau pilih produk lain.'
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error("Gagal pengecekan saldo provider ($provider) di confirm: " . $e->getMessage());
            }
            if($dataLayanan->is_flash_sale == 1 && $dataLayanan->expired_flash_sale >= date('Y-m-d H:i:s') && $dataLayanan->stock_flash_sale > 0){
            
                $dataLayanan->harga = $dataLayanan->harga_flash_sale;
                
            }
             // qty
             if (in_array($request->ktg_tipe, ['joki', 'jokigendong' , 'vilogml'])) {
            $qty = $request->qty;
            if ($qty <= 0) {
                $qty = 1;
            }
            
            $dataLayanan->harga *= $qty;
        }
               // voucher
            if(isset($request->voucher)){
                $voucher = Voucher::where('kode', $request->voucher)->first();
                
                if($voucher && $voucher->stock > 0){
                    $potongan = $dataLayanan->harga * ($voucher->promo / 100);
                    if($potongan > $voucher->max_potongan){
                        $potongan = $voucher->max_potongan;
                    }
                    
                    $dataLayanan->harga = $dataLayanan->harga - $potongan;
                }
            }
            

            $dataKategori = Kategori::where('id', $dataLayanan->kategori_id)->select('kode')->first();
            if (!$dataKategori) return response()->json(['status' => false, 'data' => 'Kode kategori tidak ditemukan']);
            $apicheck = new ApiCheckController();
            $data = ['status' => ['code' => 200]]; 
            $username = 'Not Found.'; 
            $dataMethod = Method::where('code', $request->payment_method)
                ->select('name', 'payment', 'tipe', 'code')
                ->first();
            if (!$dataMethod) return response()->json(['status' => false, 'data' => 'Metode pembayaran tidak valid']);
           $daftarGameValidasi = ['mobile-legends', 'free-fire', '8-ball-pool', 'point-blank' ,'arena-of-valor',
            'genshin-impact','dragon-raja', 'valorant', 'metal-slug-awakening', 'sausage-man', 'ea-sports-fc-mobile', 'undawn'
            ,'call-of-duty-mobile', 'pubg-mobile-tp', 'honor-of-kings-tp' , 'honkai-star-rail' ,'steam-wallet-code-indonesia'
            ,'free-fire-max', 'astra-knights-of-veda ', 'au2-mobile', 'advent-of-godlegends', 'aether-gazer', 'among-heroes-fantasy-samkok', 'angel-squad-dg', 'aov-dg', 'arcane-saga', 'arena-breakout', 'arena-mania-magic-heroes', 'asphalt-9-legends', 'atlantica-online-dg', 'astral-guardians-cyber-fantasy', 'auto-chess', ''];
 
            if(in_array($dataKategori->kode, $daftarGameValidasi)){
                if ($dataKategori->kode == 'mobile-legends') {
                    $data = $apicheck->check($request->uid, $request->zone, 'Mobile Legends');
                } else if($dataKategori->kode == "free-fire"){
                    $data = $apicheck->check($request->uid, null, 'Free Fire');
                } else if($dataKategori->kode == "free-fire-max"){
                    $data = $apicheck->check($request->uid, null, 'Free Fire MAX');
                } else if($dataKategori->kode == "honkai-star-rail"){
                    $data = $apicheck->check($request->uid, $request->zone, 'Honkai: Star Rail');
                } else if($dataKategori->kode == "pubg-mobile-tp"){
                    $data = $apicheck->check($request->uid, null, 'PUBG Mobile');
                } else if($dataKategori->kode == "honor-of-kings-tp"){
                    $data = $apicheck->check($request->uid, null, 'Honor of Kings');
                } else if($dataKategori->kode == "point-blank"){
                    $data = $apicheck->check($request->uid, null, 'Point Blank');
                } else if($dataKategori->kode == "arena-of-valor"){
                    $data = $apicheck->check($request->uid, null, 'Arena of Valor');
                } else if($dataKategori->kode == "genshin-impact"){
                    $data = $apicheck->check($request->uid, null, 'Genshin Impact');
                } else if($dataKategori->kode == "dragon-raja"){
                    $data = $apicheck->check($request->uid, null, 'Dragon Raja');
                } else if($dataKategori->kode == "super-sus"){
                    $data = $apicheck->check($request->uid, null, 'Super Sus');
                } elseif ($dataKategori->kode == "call-of-duty-mobile") {
                      $data = $apicheck->check($request->uid, null , 'Call of Duty Mobile');
                } elseif ($dataKategori->kode == "8-ball-pool") {
                      $data = $apicheck->check($request->uid, null , '8 Ball Pool');
                } elseif ($dataKategori->kode == "valorant") {
                      $data = $apicheck->check($request->uid, null , 'Valorant');
                } elseif ($dataKategori->kode == "metal-slug-awakening") {
                      $data = $apicheck->check($request->uid, null , 'Metal Slug Awakening');
                } elseif ($dataKategori->kode == "sausage-man") {
                      $data = $apicheck->check($request->uid, null , 'Sausage Man');
                } elseif ($dataKategori->kode == "ea-sports-fc-mobile") {
                      $data = $apicheck->check($request->uid, null , 'FC Mobile');
                } elseif ($dataKategori->kode == "undawn") {
                      $data = $apicheck->check($request->uid, null , 'Undawn');
                } elseif ($dataKategori->kode == "steam-wallet-code-indonesia") {
                      $data = $apicheck->check($request->uid, null , 'Steam Wallet Code - Indonesia');
                } elseif ($dataKategori->kode == "astra-knights-of-veda") {
                      $data = $apicheck->check($request->uid, $request->zone , 'ASTRA: Knights of Veda');
                } elseif ($dataKategori->kode == "au2-mobile") {
                      $data = $apicheck->check($request->uid, null , 'AU2 Mobile');
                } elseif ($dataKategori->kode == "advent-of-godlegends") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Advent of God:Legends');
                } elseif ($dataKategori->kode == "aether-gazer") {
                      $data = $apicheck->check($request->uid, null , 'Aether Gazer');
                } elseif ($dataKategori->kode == "among-heroes-fantasy-samkok") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Among Heroes: Fantasy Samkok');
                } elseif ($dataKategori->kode == "angel-squad-dg") {
                      $data = $apicheck->check($request->uid, null , 'Angel Squad (DG)');
                } elseif ($dataKategori->kode == "aov-dg") {
                      $data = $apicheck->check($request->uid, null , 'AoV (DG)');
                } elseif ($dataKategori->kode == "arcane-saga") {
                      $data = $apicheck->check($request->uid, null , 'Arcane Saga');
                } elseif ($dataKategori->kode == "arena-breakout") {
                      $data = $apicheck->check($request->uid, null , 'Arena Breakout');
                } elseif ($dataKategori->kode == "arena-mania-magic-heroes") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Arena Mania: Magic Heroes');
                } elseif ($dataKategori->kode == "asphalt-9-legends") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Asphalt 9: Legends');
                } elseif ($dataKategori->kode == "astral-guardians-cyber-fantasy") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Astral Guardians: Cyber Fantasy');
                } elseif ($dataKategori->kode == "atlantica-online-dg") {
                      $data = $apicheck->check($request->uid, null , 'Atlantica Online (DG)');
                } elseif ($dataKategori->kode == "auto-chess") {
                      $data = $apicheck->check($request->uid, null , 'Auto Chess ');
                } elseif ($dataKategori->kode == "azur-lane") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Azur Lane');
                } elseif ($dataKategori->kode == "bleach-mobile-3d") {
                      $data = $apicheck->check($request->uid, $request->zpne , 'BLEACH Mobile 3D');
                } elseif ($dataKategori->kode == "badlanders") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Badlanders');
                } elseif ($dataKategori->kode == "barbarq") {
                      $data = $apicheck->check($request->uid, $request->zone , 'BarbarQ');
                } elseif ($dataKategori->kode == "battlenet-dg") {
                      $data = $apicheck->check($request->uid, null , 'Battlenet (DG)');
                } elseif ($dataKategori->kode == "be-the-king-judge-destiny") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Be The King: Judge Destiny');
                } elseif ($dataKategori->kode == "bigo-live") {
                      $data = $apicheck->check($request->uid, null , 'Bigo Live');
                } elseif ($dataKategori->kode == "bigo-live-voucher") {
                      $data = $apicheck->check($request->uid, null , 'Bigo Live Voucher');
                } elseif ($dataKategori->kode == "Bilibili-dg") {
                      $data = $apicheck->check($request->uid, null , 'Bilibili (DG)');
                } elseif ($dataKategori->kode == "bioskop-online") {
                      $data = $apicheck->check($request->uid, null , 'Bioskop Online');
                } elseif ($dataKategori->kode == "blade-x-odyssey-of-heroes") {
                      $data = $apicheck->check($request->uid, null , 'Blade X: Odyssey of Heroes');
                } elseif ($dataKategori->kode == "bleach-mobile-3d-dg") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Bleach Mobile 3D (DG)');
                } elseif ($dataKategori->kode == "blizzard-gift-card-dg") {
                      $data = $apicheck->check($request->uid, null , 'Blizzard Gift Card (DG)');
                } elseif ($dataKategori->kode == "blood-strike") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Blood Strike');
                } elseif ($dataKategori->kode == "boxing-star-dg") {
                      $data = $apicheck->check($request->uid, null , 'Boxing Star (DG)');
                } elseif ($dataKategori->kode == "brawl-stars") {
                      $data = $apicheck->check($request->uid, null , 'Brawl Stars');
                } elseif ($dataKategori->kode == "captain-tsubasa-ace") {
                      $data = $apicheck->check($request->uid, null , 'Captain Tsubasa: Ace');
                } elseif ($dataKategori->kode == "captain-tsubasa-dream-team") {
                      $data = $apicheck->check($request->uid, null , 'Captain Tsubasa: Dream Team');
                } elseif ($dataKategori->kode == "city-of-crime-gang-wars") {
                      $data = $apicheck->check($request->uid, null , 'City of Crime: Gang Wars');
                } elseif ($dataKategori->kode == "clash-royale") {
                      $data = $apicheck->check($request->uid, null , 'Clash Royale');
                } elseif ($dataKategori->kode == "clash-of-clans") {
                      $data = $apicheck->check($request->uid, null , 'Clash of Clans');
                } elseif ($dataKategori->kode == "cloud-song-saga-of-skywalkers") {
                      $data = $apicheck->check($request->uid, null , 'Cloud Song: Saga of Skywalkers');
                } elseif ($dataKategori->kode == "cooking-adventure") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Cooking Adventure');
                } elseif ($dataKategori->kode == "crasher-origin") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Crasher Origin');
                } elseif ($dataKategori->kode == "dead-target-zombie-games-3d") {
                      $data = $apicheck->check($request->uid, null , 'DEAD TARGET: Zombie Games 3D');
                } elseif ($dataKategori->kode == "dg-mini-games-dg") {
                      $data = $apicheck->check($request->uid, null , 'DG Mini Games (DG)');
                } elseif ($dataKategori->kode == "dark-continent-mist") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Dark Continent: Mist');
                } elseif ($dataKategori->kode == "diablo-immortal") {
                      $data = $apicheck->check($request->uid, null , 'Diablo: Immortal');
                } elseif ($dataKategori->kode == "discord-subscription") {
                      $data = $apicheck->check($request->uid, null , 'Discord Subscription');
                } elseif ($dataKategori->kode == "garena-dg") {
                      $data = $apicheck->check($request->uid, null , 'Top Up Garena Shell (DG)');
                } elseif ($dataKategori->kode == "ragnarok-m-eternal-love-big-cat-coin") {
                      $data = $apicheck->check($request->uid, null , 'Ragnarok M: Eternal Love Big Cat Coin');
                } elseif ($dataKategori->kode == "laplace-m") {
                      $data = $apicheck->check($request->uid, null , 'Laplace M');
                } elseif ($dataKategori->kode == "speed-drifters") {
                      $data = $apicheck->check($request->uid, null , 'Speed Drifters');
                } elseif ($dataKategori->kode == "era-of-celestials") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Era of Celestials');
                } elseif ($dataKategori->kode == "higgs-domino") {
                      $data = $apicheck->check($request->uid, null , 'Higgs Domino');
                } elseif ($dataKategori->kode == "heroes-evolved") {
                      $data = $apicheck->check($request->uid, null , 'Heroes Evolved');
                } elseif ($dataKategori->kode == "lifeafter") {
                      $data = $apicheck->check($request->uid, $request->zone , 'LifeAfter');
                } elseif ($dataKategori->kode == "scroll-of-onmyoji-sakura-and-sword") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Scroll of Onmyoji: Sakura & Sword');
                } elseif ($dataKategori->kode == "marvel-snap") {
                      $data = $apicheck->check($request->uid, null , 'MARVEL SNAP');
                } elseif ($dataKategori->kode == "hago") {
                      $data = $apicheck->check($request->uid, null , 'Hago');
                } elseif ($dataKategori->kode == "tom-and-jerry-chase") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Tom and Jerry: Chase');
                } elseif ($dataKategori->kode == "one-punch-man-the-strongest") {
                      $data = $apicheck->check($request->uid, null , 'ONE PUNCH MAN: The Strongest');
                } elseif ($dataKategori->kode == "dragon-raja") {
                      $data = $apicheck->check($request->uid, null , 'Dragon Raja');
                } elseif ($dataKategori->kode == "ludo-club") {
                      $data = $apicheck->check($request->uid, null , 'Ludo Club');
                } elseif ($dataKategori->kode == "league-of-legends-wild-rift-dg") {
                      $data = $apicheck->check($request->uid, null , 'League of Legends : Wild Rift (DG)');
                } elseif ($dataKategori->kode == "league-of-legends") {
                      $data = $apicheck->check($request->uid, null , 'League of Legends');
                } elseif ($dataKategori->kode == "state-of-survival") {
                      $data = $apicheck->check($request->uid, null , 'State of Survival');
                } elseif ($dataKategori->kode == "ys-6-mobile-vng") {
                      $data = $apicheck->check($request->uid, null , 'YS 6 Mobile VNG');
                } elseif ($dataKategori->kode == "tower-of-fantasy-a") {
                      $data = $apicheck->check($request->uid, null , 'Tower of Fantasy (Slow)');
                } elseif ($dataKategori->kode == "mu-origin-3") {
                      $data = $apicheck->check($request->uid, null , 'MU ORIGIN 3');
                } elseif ($dataKategori->kode == "stumble-guys") {
                      $data = $apicheck->check($request->uid, null , 'Stumble Guys');
                } elseif ($dataKategori->kode == "honkai-impact-3") {
                      $data = $apicheck->check($request->uid, null , 'Honkai Impact 3');
                } elseif ($dataKategori->kode == "goddes-victory-nikke-tp") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Goddes Victory: Nikke (FAST)');
                } elseif ($dataKategori->kode == "ragnarok-retro-dg") {
                      $data = $apicheck->check($request->uid, null , 'Ragnarok Retro (DG)');
                } elseif ($dataKategori->kode == "ragnarok-x-next-generation") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Ragnarok X: Next Generation');
                } elseif ($dataKategori->kode == "revelation-infinite-journey") {
                      $data = $apicheck->check($request->uid, null , 'Revelation: Infinite Journey');
                } elseif ($dataKategori->kode == "lita") {
                      $data = $apicheck->check($request->uid, null , 'Lita');
                } elseif ($dataKategori->kode == "teen-patti-gold") {
                      $data = $apicheck->check($request->uid, null , 'Teen Patti Gold');
                } elseif ($dataKategori->kode == "hay-day") {
                      $data = $apicheck->check($request->uid, null , 'Hay Day');
                } elseif ($dataKategori->kode == "zepeto") {
                      $data = $apicheck->check($request->uid, null , 'ZEPETO');
                } elseif ($dataKategori->kode == "kings-choice") {
                      $data = $apicheck->check($request->uid, null , 'Kings Choice');
                } elseif ($dataKategori->kode == "harry-potter-magic-awakened") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Harry Potter: Magic Awakened');
                } elseif ($dataKategori->kode == "life-makeover") {
                      $data = $apicheck->check($request->uid, null , 'Life Makeover');
                } elseif ($dataKategori->kode == "brawl-stars") {
                      $data = $apicheck->check($request->uid, null , 'Brawl Stars');
                } elseif ($dataKategori->kode == "growtopia") {
                      $data = $apicheck->check($request->uid, null , 'Growtopia');
                } elseif ($dataKategori->kode == "identity-v") {
                      $data = $apicheck->check($request->uid, null , 'Identity V');
                } elseif ($dataKategori->kode == "farlight-84") {
                      $data = $apicheck->check($request->uid, null , 'Farlight 84');
                } elseif ($dataKategori->kode == "football-master-2") {
                      $data = $apicheck->check($request->uid, null , 'Football Master 2');
                } elseif ($dataKategori->kode == "eos-red") {
                      $data = $apicheck->check($request->uid, $request->zone , 'EOS RED');
                } elseif ($dataKategori->kode == "eggy-party") {
                      $data = $apicheck->check($request->uid, null , 'EGGY PARTY');
                } elseif ($dataKategori->kode == "snowbreak-containment-zone") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Snowbreak: Containment Zone');
                } elseif ($dataKategori->kode == "rhythm-hive") {
                      $data = $apicheck->check($request->uid, null , 'Rhythm Hive');
                } elseif ($dataKategori->kode == "asphalt-9-legends") {
                      $data = $apicheck->check($request->uid, null , 'Asphalt 9: Legends');
                } elseif ($dataKategori->kode == "teamfight-tactics-mobile") {
                      $data = $apicheck->check($request->uid, null , 'Teamfight Tactics Mobile');
                } elseif ($dataKategori->kode == "blood-strike") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Blood Strike');
                } elseif ($dataKategori->kode == "punishing-gray-raven") {
                      $data = $apicheck->check($request->uid, $request->zone , 'Punishing: Gray Raven');
                } elseif ($dataKategori->kode == "octopath-traveler-cotc") {
                      $data = $apicheck->check($request->uid, $request->zone , 'OCTOPATH TRAVELER: CotC');
                } elseif ($dataKategori->kode == "love-and-deepspace") {
                      $data = $apicheck->check($request->uid, null , 'Love and Deepspace');
                } elseif ($dataKategori->kode == "pixel-gun-3d") {
                      $data = $apicheck->check($request->uid, null , 'Pixel Gun 3D');
                } elseif ($dataKategori->kode == "the-legend-of-neverland-dg") {
                      $data = $apicheck->check($request->uid, null , 'The Legend of Neverland (DG)');
                } elseif ($dataKategori->kode == "heroic-uncle-kim-idle-rpg") {
                      $data = $apicheck->check($request->uid, null , 'Heroic Uncle Kim: Idle RPG');
                } elseif ($dataKategori->kode == "world-war-heroes") {
                      $data = $apicheck->check($request->uid, null , 'World War Heroes');
                } elseif ($dataKategori->kode == "moonlight-blade-m") {
                      $data = $apicheck->check($request->uid, null , 'Moonlight Blade M');
                } elseif ($dataKategori->kode == "king-of-avalon") {
                      $data = $apicheck->check($request->uid, null , 'King of Avalon');
                }
                
                if ($data['status']['code'] == 1) {
                    // If it's a real "Not Found" from API, we might want to block. 
                    // But if it's "gangguan" (service down), we should probably let them proceed to not lose sales.
                    if (strpos($data['data']['msg'] ?? '', 'gangguan') !== false || strpos($data['data']['msg'] ?? '', 'Error') !== false) {
                         $username = 'Username Check Error (Proceeding)';
                    } else {
                        return response()->json([
                            'status' => false,
                            'data' => isset($data['data']['msg']) ? $data['data']['msg'] : 'Username tidak ditemukan.'
                        ]);
                    }
                } else {
                    $username = isset($data['data']['username']) ? $data['data']['username'] : 'Not Found.';
                }
                
                
                // dataMethod already defined at the top
                if ($request->payment_method == "11" || $request->payment_method == "17") {
                    $dataLayanan->harga = $dataLayanan->harga + ($dataLayanan->harga * (0.70 / 100));
                } elseif ($request->payment_method == "20") {
                    $dataLayanan->harga = $dataLayanan->harga + ($dataLayanan->harga * (0.90 / 100));
                    
                } elseif ($request->payment_method == "23") {
                    $dataLayanan->harga = $dataLayanan->harga + ($dataLayanan->harga * (2 / 100));
                    
                } elseif ($request->payment_method == "13") {
                    $dataLayanan->harga = $dataLayanan->harga + ($dataLayanan->harga * (3 / 100));
                    
                } elseif ($request->payment_method == "12" || $request->payment_method == "14") {
                    $dataLayanan->harga = $dataLayanan->harga + ($dataLayanan->harga * (3 / 100));
                    
                } elseif ($request->payment_method == "1") {
                    $dataLayanan->harga = $dataLayanan->harga + 4900;
                } elseif ($request->payment_method == "4") {
                    $dataLayanan->harga = $dataLayanan->harga + 4000;
                } elseif($request->payment_method == "2" || $request->payment_method == "3" || $request->payment_method == "5" || $request->payment_method == "7" || $request->payment_method == "8"){
                    $dataLayanan->harga = $dataLayanan->harga + 2500;
                } elseif($request->payment_method == "9" || $request->payment_method == "10"){
                    $dataLayanan->harga = $dataLayanan->harga + 3500;
                    
                } elseif ($request->payment_method == "18" || $request->payment_method == "19") {
                    $dataLayanan->harga = $dataLayanan->harga + 2500;
                } elseif ($request->payment_method == "21") {
                    $dataLayanan->harga = $dataLayanan->harga + 1500;
                } elseif ($request->payment_method == "22") {
                    $dataLayanan->harga = $dataLayanan->harga + 3500;
                } else {
                    $dataLayanan->harga = $dataLayanan->harga;
                }
                
                            
                  $playerData = [ "User ID" => $request->uid ];
                if (!empty($request->zone)) { $playerData["Zone"] = $request->zone; }
                $playerData["Username"] = urldecode($username);
                $purchaseData = [ "Item" => $item->layanan, "Product" => $produk->nama ];
                $priceLabel = "Rp. " . number_format($dataLayanan->harga, 0, ".", ",");
                $methodName = ($dataMethod && isset($dataMethod->name)) ? ($dataMethod && isset($dataMethod->name) ? strtoupper($dataMethod->name) : 'UNKNOWN') : 'UNKNOWN';
                $sendData = $this->generatePremiumModal("Buat Pesanan", "Pastikan data akun Anda dan produk yang Anda pilih valid dan sesuai.", $playerData, $purchaseData, $priceLabel, $methodName);
                            
                return response()->json([
                    'status' => true,
                    'data' => $sendData
                ]);
            }else{
                
                if ($request->payment_method == "11" || $request->payment_method == "17") {
                    $dataLayanan->harga = $dataLayanan->harga + ($dataLayanan->harga * (0.70 / 100));
                } elseif ($request->payment_method == "20") {
                    $dataLayanan->harga = $dataLayanan->harga + ($dataLayanan->harga * (0.90 / 100));
                    
                } elseif ($request->payment_method == "23") {
                    $dataLayanan->harga = $dataLayanan->harga + ($dataLayanan->harga * (2 / 100));
                    
                } elseif ($request->payment_method == "13") {
                    $dataLayanan->harga = $dataLayanan->harga + ($dataLayanan->harga * (3 / 100));
                    
                } elseif ($request->payment_method == "12" || $request->payment_method == "14") {
                    $dataLayanan->harga = $dataLayanan->harga + ($dataLayanan->harga * (3 / 100));
                    
                } elseif ($request->payment_method == "1") {
                    $dataLayanan->harga = $dataLayanan->harga + 4900;
                } elseif ($request->payment_method == "4") {
                    $dataLayanan->harga = $dataLayanan->harga + 4000;
                } elseif($request->payment_method == "2" || $request->payment_method == "3" || $request->payment_method == "5" || $request->payment_method == "7" || $request->payment_method == "8"){
                    $dataLayanan->harga = $dataLayanan->harga + 2500;
                } elseif($request->payment_method == "9" || $request->payment_method == "10"){
                    $dataLayanan->harga = $dataLayanan->harga + 3500;
                    
                } elseif ($request->payment_method == "18" || $request->payment_method == "19") {
                    $dataLayanan->harga = $dataLayanan->harga + 2500;
                } elseif ($request->payment_method == "21") {
                    $dataLayanan->harga = $dataLayanan->harga + 1500;
                } elseif ($request->payment_method == "22") {
                    $dataLayanan->harga = $dataLayanan->harga + 3500;
                } else {
                    $dataLayanan->harga = $dataLayanan->harga;
                }
                
                if ($request->ktg_tipe === 'jokigendong') {
                    $playerData = [
                        "Nickname" => $request->nickname_joki,
                        "Role" => $request->loginvia_joki,
                        "Tanggal Main" => $request->tglmain_joki,
                        "Jam Booking" => $request->jambooking_joki,
                        "Catatan" => $request->catatan_joki
                    ];
                    $purchaseData = [ "Item" => $item->layanan, "Product" => $produk->nama ];
                    $priceLabel = "Rp. " . number_format($dataLayanan->harga, 0, ".", ",");
                    $sendData = $this->generatePremiumModal("Buat Pesanan", "Pastikan data akun jokigendong yang anda pilih valid dan sesuai.", $playerData, $purchaseData, $priceLabel, null);
                } elseif ($request->ktg_tipe === 'joki') {
                    $playerData = [
                        "Email" => $request->email_joki,
                        "Password" => $request->password_joki,
                        "Login Via" => $request->loginvia_joki,
                        "Nickname" => $request->nickname_joki,
                        "Request" => $request->request_joki,
                        "Catatan" => $request->catatan_joki
                    ];
                    $purchaseData = [ "Item" => $item->layanan, "Product" => $produk->nama ];
                    $priceLabel = "Rp. " . number_format($dataLayanan->harga, 0, ".", ",");
                    $sendData = $this->generatePremiumModal("Buat Pesanan", "Pastikan data akun Joki yang anda pilih valid dan sesuai.", $playerData, $purchaseData, $priceLabel, null);
                } elseif ($request->ktg_tipe === 'vilogml') {
                    $playerData = [
                        "Email" => $request->email_joki,
                        "Password" => $request->password_joki,
                        "Login Via" => $request->loginvia_joki,
                        "User ID" => $request->nickname_joki,
                        "Server ID" => $request->request_joki,
                        "Catatan" => $request->catatan_joki
                    ];
                    $purchaseData = [ "Item" => $produk->nama ];
                    $priceLabel = "Rp. " . number_format($dataLayanan->harga, 0, ".", ",");
                    $sendData = $this->generatePremiumModal("Buat Pesanan", "Pastikan data Vilog ML yang anda pilih valid dan sesuai.", $playerData, $purchaseData, $priceLabel, null);
                } else {
                    $playerData = [ "User ID" => $request->uid ];
                    if (!empty($request->zone)) { $playerData["Zone"] = $request->zone; }
                    $purchaseData = [ "Item" => $item->layanan, "Product" => $produk->nama ];
                    $priceLabel = "Rp. " . number_format($dataLayanan->harga, 0, ".", ",");
                    $methodName = isset($dataMethod) && $dataMethod ? ($dataMethod && isset($dataMethod->name) ? strtoupper($dataMethod->name) : 'UNKNOWN') : null;
                    $sendData = $this->generatePremiumModal("Buat Pesanan", "Pastikan data akun Anda dan produk yang Anda pilih valid dan sesuai.", $playerData, $purchaseData, $priceLabel, $methodName);
                }

                            
                            
                            
                
                return response()->json([
                    'status' => true,
                    'data' => $sendData
                ]);
            }

    }

    public function store(Request $request)
    {
   
        if($request->ktg_tipe === 'jokigendong') {
            $request->validate([
                'nickname_joki' => 'required|string|max:255',
                'tglmain_joki' => 'required|string|max:255',
                'jambooking_joki' => 'required|string|max:255',
                'loginvia_joki' => 'required',
                'catatan_joki' => 'required',
                'service' => 'required|numeric',
                'payment_method' => 'required',
                'nomor' => 'required|numeric',                   
            ]);
        } elseif($request->ktg_tipe === 'joki') {
            $request->validate([
                'email_joki' => 'required|string|max:255',
                'password_joki' => 'required|string|max:255',
                'loginvia_joki' => 'required|string|max:255',
                'nickname_joki' => 'required|string|max:255',
                'request_joki' => 'required|string|max:255',
                'catatan_joki' => 'required|string|max:255',
                'qty' => 'required|numeric|max:30',
                'service' => 'required|numeric',
                'payment_method' => 'required',
                'nomor' => 'required|numeric',
            ]);
        } elseif($request->ktg_tipe === 'vilogml') {
            $request->validate([
                'email_joki' => 'required|string|max:255',
                'password_joki' => 'required|string|max:255',
                'loginvia_joki' => 'required|string|max:255',
                'nickname_joki' => 'required|string|max:255',
                'request_joki' => 'required|string|max:255',
                'catatan_joki' => 'required|string|max:255',
                'qty' => 'required|numeric|max:30',
                'service' => 'required|numeric',
                'payment_method' => 'required',
                'nomor' => 'required|numeric',
            ]);
        } else {
            $request->validate([
                'uid' => 'required|max:25',
                'service' => 'required|numeric',
                'payment_method' => 'required',
                'nomor' => 'required|numeric',                
            ]);
        }
        

        if(Auth::check()){
            if(Auth::user()->role == "Member"){
                $dataLayanan = Layanan::where('id', $request->service)->select('layanan','harga_member AS harga','kategori_id', 'provider_id', 'provider', 'profit_member AS profit', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale')->first();
            }else if(Auth::user()->role == "Platinum"){
                $dataLayanan = Layanan::where('id', $request->service)->select('layanan','harga_platinum AS harga','kategori_id', 'provider_id', 'provider', 'profit_platinum AS profit', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale')->first();
            }else if(Auth::user()->role == "Gold" || Auth::user()->role == "Admin"){
                $dataLayanan = Layanan::where('id', $request->service)->select('layanan','harga_gold AS harga','kategori_id', 'provider_id', 'provider', 'profit_gold AS profit', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale')->first();
            }            
        }else{
            $dataLayanan = Layanan::where('id', $request->service)->select('layanan', 'harga AS harga', 'kategori_id', 'provider_id', 'provider', 'profit', 'is_flash_sale', 'expired_flash_sale', 'harga_flash_sale', 'stock_flash_sale')->first();
        }
        
        if (!$dataLayanan) {
            return response()->json([
                'status' => false,
                'data' => 'Layanan tidak ditemukan'
            ]);
        }

        // Cek Saldo Provider sebelum melanjutkan
        $provider = $dataLayanan->provider;
        $profitPercent = (float)($dataLayanan->profit ?? 0) / 100;
        $hargaAcuan = $dataLayanan->harga;
        if($dataLayanan->is_flash_sale == 1 && $dataLayanan->expired_flash_sale >= date('Y-m-d H:i:s') && $dataLayanan->stock_flash_sale > 0){
            $hargaAcuan = $dataLayanan->harga_flash_sale;
        }
        $estimasiHargaBeli = $hargaAcuan / (1 + $profitPercent);
        $currentSaldo = null;

        try {
            if ($provider == 'digiflazz') {
                $digi = new digiFlazzController;
                $resSaldo = $digi->cekSaldo();
                $currentSaldo = $resSaldo['data']['deposit'] ?? null;
            } elseif (in_array($provider, ['topupedia', 'bangjeff', 'aoshi'])) {
                $ctrl = null;
                if ($provider == 'topupedia') {
                    $ctrl = new TopupediaController;
                } elseif ($provider == 'bangjeff') {
                    $ctrl = new BangJeffController;
                } elseif ($provider == 'aoshi') {
                    $ctrl = new AoshiController;
                }
                
                if ($ctrl) {
                    $resSaldo = $ctrl->balance();
                    $currentSaldo = $resSaldo['data']['balance'] ?? null;
                }
            }

            if ($currentSaldo !== null && $currentSaldo < $estimasiHargaBeli) {
                return response()->json([
                    'status' => false,
                    'data' => 'Maaf, stok layanan ini sedang kosong (Saldo Provider Menipis). Mohon hubungi admin atau pilih produk lain.'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Gagal pengecekan saldo provider ($provider) di store: " . $e->getMessage());
        }
        
        if($dataLayanan->is_flash_sale == 1 && $dataLayanan->expired_flash_sale >= date('Y-m-d H:i:s') && $dataLayanan->stock_flash_sale > 0){
                $sisa = $dataLayanan->stock_flash_sale - 1;
                $updatesisa = Layanan::where('id', $request->service)->update(['stock_flash_sale' => $sisa]);
                $dataLayanan->harga = $dataLayanan->harga_flash_sale;
                
        }
        
      if (in_array($request->ktg_tipe, ['joki', 'jokigendong', 'vilogml'])) {
            $qty = $request->qty;
            if ($qty <= 0) {
                $qty = 1;
            }
            
            $dataLayanan->harga *= $qty;
        }

      
        
        if(isset($request->voucher)){
            $voucher = Voucher::where('kode', $request->voucher)->first();
            
            if($voucher && $voucher->stock > 0){
                $potongan = $dataLayanan->harga * ($voucher->promo / 100);
                if($potongan > $voucher->max_potongan){
                    $potongan = $voucher->max_potongan;
                }
                
                $dataLayanan->harga = round($dataLayanan->harga - $potongan);
                $voucher->decrement('stock');
            }
        }  
        
        
        $kategori = Kategori::where('id', $dataLayanan->kategori_id)->select('kode')->first();

         
        $unik = date('Hs');
        $characters = '0123456789'; 
        $code = '';
        
        for ($i = 0; $i < 8; $i++) { 
            $randomIndex = rand(0, strlen($characters) - 1);
            $code .= $characters[$randomIndex];
        }
        $kode_unik = $code;
        $order_id = env('APP_NAME').$unik.$kode_unik;
        $tokopay = new TokoPayController(); 
        $paydisini = new PaydisiniController();
        $tripay = new TripayController();
        $rand = rand(1,1000);
        $no_pembayaran = '';
        $amount = '';
        $reference = '';
        $api = DB::table('setting_webs')->where('id',1)->first();
        $dataMethod = Method::where('code', $request->payment_method)->select('name','payment','tipe','code')->first();
        if (!$dataMethod && $request->payment_method != "SALDO") {
            return response()->json(['status' => false, 'data' => 'Metode pembayaran tidak valid']);
        }
        
        if($request->payment_method == "SALDO"){
            $amount = $dataLayanan->harga;
        }else if($request->payment_method == "OVOO"){
            $amount = $dataLayanan->harga + $rand;
            $reference = '';            
            if($request->payment_method == "OVOO"){
                $no_pembayaran = $api->ovo_admin;
                if($amount < 10000){
                    return response()->json(['status' => false, 'data' => 'Minimum jumlah pembayaran untuk metode pembayaran ini adalah Rp 10.000']);
                }
            }else{
                $no_pembayaran = $api->gopay_admin;
                if($amount < 1000){
                    return response()->json(['status' => false, 'data' => 'Minimum jumlah pembayaran untuk metode pembayaran ini adalah Rp 1.000']);
                }
            }
         } else {
           if (!$dataMethod) return response()->json(['status' => false, 'data' => 'Metode pembayaran tidak valid']);
           if($dataMethod->payment == "tokopay") {
            $tokopayres = $tokopay->createOrder($dataLayanan->harga, $order_id, $request->payment_method);
            if($tokopayres['status'] != 'Success') return response()->json(['status' => false, 'data' => 'error']);
               
            if (isset($tokopayres['data'])) {
                $no_pembayaran = $tokopayres['data']['pay_url'];
            if (isset($tokopayres['data']['nomor_va'])) {
                $no_pembayaran = $tokopayres['data']['nomor_va'];
            } else if (isset($tokopayres['data']['qr_link'])) {
                $no_pembayaran = $tokopayres['data']['qr_link'];
            } else if (isset($tokopayres['data']['checkout_url'])) {
                $no_pembayaran = $tokopayres['data']['checkout_url'];
            }
           
            $reference = $tokopayres['data']['trx_id'];
            $amount = $tokopayres['data']['total_bayar'];
            }     
            
            } else if ($dataMethod->payment == "paydisini") {
                       $response = $paydisini->createTransaction(new Request([
                'unique_code' => $order_id,
                'service' => $request->payment_method,
                'amount' => $dataLayanan->harga,
                'note' => $dataLayanan->layanan,
                'valid_time' => 1800,
                'ewallet_phone' => $request->nomor,
                'type_fee' => 1
            ]));
            $responseData = $response->getData(true);
            
            if (isset($responseData['data'])) {
                $data = $responseData['data'];
                $no_pembayaran =$data['no_pembayaran']  ?? $data['checkout_url'] ??  '';
                $reference = $data['unique_code'] ?? '';
                $amount = $data['amount'] ?? $dataLayanan->harga;
            }
          } else if ($dataMethod->payment == "tripay") {
                $customer_name = $request->nickname ?? 'Customer';
                $customer_phone = $request->nomor ?? '081234567890';
                $customer_email = '';
                
                $response = $tripay->createTransaction(
                    $dataLayanan->harga,
                    $order_id,
                    $request->payment_method, 
                    $customer_name,
                    $customer_phone,
                    $customer_email,
                    [['name' => $dataLayanan->layanan, 'price' => $dataLayanan->harga, 'quantity' => 1]]
                );

                if ($response['status'] === 'Success') {
                    $no_pembayaran = $response['data']['qr_url'] ?? '';
                    
                    // Fallback to qr_string if qr_url is missing
                    if (empty($no_pembayaran) && !empty($response['data']['qr_string'])) {
                        $no_pembayaran = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($response['data']['qr_string']);
                    }
                    
                    // If still empty, use checkout_url
                    if (empty($no_pembayaran)) {
                        $no_pembayaran = $response['data']['checkout_url'] ?? $response['data']['pay_code'] ?? '';
                    }
                    
                    $reference = $response['data']['reference'] ?? '';
                    $amount = $response['data']['amount'] ?? $dataLayanan->harga;
                } else {
                    return response()->json(['status' => false, 'data' => $response['data']]);
                }
          }
         }

        
        if ($request->payment_method == "SALDO") {
           
            $pesan = 
            "*Pembayaran Berhasil*\n\n" .
            "No Invoice: *$order_id*\n" .
            "Layanan: *$dataLayanan->layanan*\n" .
            "ID : *$request->uid*\n" .
            "Server : *$request->zone*\n" .
            "Nickname : *$request->nickname*\n" .
            "Harga: *Rp. " . number_format($amount, 0, '.', ',') . "*\n" .
            "Status Pembayaran: *Dibayar*\n" .
            "Metode Pembayaran: *$request->payment_method*\n\n" .
            "*Invoice* : " . env("APP_URL") . "/id/invoices/$order_id\n\n" .
            "INI ADALAH PESAN OTOMATIS";
        } else {
            
       
            
            $pesan = 
            "*Menunggu Pembayaran*\n\n" .
            "No Invoice: *$order_id*\n" .
            "Layanan: *$dataLayanan->layanan*\n" .
            "ID : *$request->uid*\n" .
            "Server : *$request->zone*\n" .
            "Nickname : *$request->nickname*\n" .
            "Harga: *Rp. " . number_format(floatval($amount), 0, '.', ',') . "*\n" .
            "Status: *Menunggu Pembayaran*\n" .
            "Metode Pembayaran: *$request->payment_method*\n" .
            "Kode Bayar / Nomor VA : *".$no_pembayaran."*\n\n" .
            
            "*Harap Dibayar Sebelum 3 Jam!* Segera lakukan pembayaran sesuai dengan kode bayar / nomor VA yang tercantum. Pastikan nominal pembayaran juga sesuai dengan total bayar.\n\n" .
            "*Invoice* : " . env("APP_URL") . "/id/invoices/$order_id\n\n" .
             "INI ADALAH PESAN OTOMATIS";
        }
        
         $tipe = '';
                
            if ($request->ktg_tipe == 'joki') {
                $tipe = 'joki';
            } else if ($request->ktg_tipe == 'voucher') {
                $tipe = 'voucher';
            } else if ($request->ktg_tipe == 'vilogml') {
                $tipe = 'vilogml';
            } else if ($request->ktg_tipe == 'jokigendong') {
                $tipe = 'jokigendong';
            } else {
                $tipe = 'game';
            }

        

        if($request->payment_method != "SALDO"){
            
            $requestPesan = $this->msg($request->nomor,$pesan);
            $ipController = new IPAddressController();
            $ipAddress = $ipController->getIPAddress($request);
            
          
            $pembelian = new Pembelian();
            $pembelian->order_id = $order_id;
            if (Auth::check()) {
                $pembelian->username = Auth::user()->username;
            }
            $pembelian->user_id = ($request->ktg_tipe !== 'joki' && $request->ktg_tipe !== 'jokigendong'  && $request->ktg_tipe !== 'vilogml') ? $request->uid : '-';
            $pembelian->zone = ($request->ktg_tipe !== 'joki' && $request->ktg_tipe !== 'jokigendong'  && $request->ktg_tipe !== 'vilogml') ? $request->zone : '-';
            $pembelian->nickname = ($request->ktg_tipe !== 'joki' && $request->ktg_tipe !== 'jokigendong'  && $request->ktg_tipe !== 'vilogml') ? $request->nickname : ($request->ktg_tipe !== 'joki' ? $request->nickname_joki : '-');

            $pembelian->status = 'Pending';
            $pembelian->tipe_transaksi = ($request->ktg_tipe !== 'joki' && $request->ktg_tipe !== 'jokigendong' && $request->ktg_tipe !== 'vilogml') ? $tipe : $request->ktg_tipe;
            $pembelian->layanan = $dataLayanan->layanan;
            
            // Pastikan $amount adalah numerik
            $amount = floatval($amount);
            
            // Pastikan $dataLayanan->profit adalah numerik
            $dataLayananProfit = floatval($dataLayanan->profit);
            
            $pembelian->harga = $amount;
            $pembelian->profit = $amount * $dataLayananProfit / 100;
            $pembelian->ip_address = $ipAddress;
            $pembelian->save();

    
            $pembayaran = new Pembayaran();
            $pembayaran->order_id = $order_id;
            $pembayaran->harga = $amount;
            $pembayaran->no_pembayaran = $no_pembayaran;
            $pembayaran->no_pembeli = $request->nomor;
            $pembayaran->status = 'Belum Lunas';
            $pembayaran->metode = $request->payment_method;
            $pembayaran->reference = $reference;
            $pembayaran->save();
              
               
  if($request->ktg_tipe == 'joki' || $request->ktg_tipe == 'jokigendong' || $request->ktg_tipe == 'vilogml'){
                $jokian = DB::table('data_joki')->insert([
                    'order_id' => $order_id,
                    'email_joki' => $request->ktg_tipe !== 'jokigendong' ? $request->email_joki : '-',
                    'password_joki' => $request->ktg_tipe !== 'jokigendong' ? $request->password_joki : '-',
                    'loginvia_joki' => $request->loginvia_joki,
                    'nickname_joki' => $request->ktg_tipe !== 'jokigendong' ? $request->nickname_joki : '-',
                    'request_joki' => $request->ktg_tipe !== 'jokigendong' ? $request->request_joki : '-',
                    'catatan_joki' => $request->catatan_joki,
                   
            'tglmain_joki' => $request->ktg_tipe !== 'jokigendong' ? '-' : $request->tglmain_joki,
            'jambooking_joki' => $request->ktg_tipe !== 'jokigendong' ? '-' : $request->jambooking_joki,
                    'qty' => $request->qty,
                    'status_joki' => 'Pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            
            
        }else if($request->payment_method == "SALDO"){
            $user = User::where('username', Auth::user()->username)->first();

            if ($dataLayanan->harga > $user->balance) return response()->json(['status' => false, 'data' => 'Saldo anda tidak cukup']);

                 if($dataLayanan->provider == "digiflazz"){
                    $digi = new digiFlazzController;
                    $random_part = mt_rand(100000, 999999);
                    $provider_order_id = 'REF-HRY' . $random_part;
                    $order = $digi->order($request->uid, $request->zone, $dataLayanan->provider_id, $provider_order_id);
        
                    if ($order['data']['status'] == "Pending" || $order['data']['status'] == "Sukses") {
                        $order['status'] = true;
                    } else {
                        $order['status'] = false;
                    }    
                }else if($dataLayanan->provider == "bangjeff"){
                    $bangjeffo = new BangJeffController;
                
                    $requestData = [
                        [
                            'name' => 'ID',
                            'value' => $request->uid
                        ]
                    ];
                
                    if ($request->has('zone')) {
                        $requestData[] = [
                            'name' => 'Server',
                            'value' => $request->zone
                        ];
                    }
                    
                     $order = $bangjeffo->order($dataLayanan->provider_id, $order_id, 1, $requestData);
                     
                     if ($order['error'] == false) {
                        $provider_order_id = $order['data']['invoiceNumber'];
                        $order['status'] = true;
                    } else {
                        $order['status'] = false;
                    }
                }else if($dataLayanan->provider == "topupedia"){
                    $topupedia = new TopupediaController;
                
                    $requestData = [
                        [
                            'name' => 'ID',
                            'value' => $request->uid
                        ]
                    ];
                
                    if ($request->has('zone')) {
                        $requestData[] = [
                            'name' => 'Server',
                            'value' => $request->zone
                        ];
                    }
                    
                     $order = $topupedia->order($dataLayanan->provider_id, $order_id, 1, $requestData);
                     
                     if ($order['error'] == false) {
                        $provider_order_id = $order['data']['invoiceNumber'];
                        $order['status'] = true;
                    } else {
                        $order['status'] = false;
                    }
                }else if($dataLayanan->provider == "aoshi"){
                    $aoshi = new AoshiController;
                
                    $requestData = [
                        [
                            'name' => 'ID',
                            'value' => $request->uid
                        ]
                    ];
                
                    if ($request->has('zone')) {
                        $requestData[] = [
                            'name' => 'Server',
                            'value' => $request->zone
                        ];
                    }
                    
                     $order = $aoshi->order($dataLayanan->provider_id, $order_id, 1, $requestData);
                     
                     if ($order['error'] == false) {
                        $provider_order_id = $order['data']['invoiceNumber'];
                        $order['status'] = true;
                    } else {
                        $order['status'] = false;
                    }
                }else if($dataLayanan->provider == "joki"){
                    $provider_order_id = '';
                    $order['status'] = true;
                }else if($dataLayanan->provider == "jokigendong"){
                    $provider_order_id = '';
                    $order['status'] = true;
                }else if($dataLayanan->provider == "vilogml"){
                    $provider_order_id = '';
                    $order['status'] = true;
                }
            
            
            if($order['status']){
                
       
    
                $pesanSukses = 
                "*Pembelian Sukses*\n\n" .
                "No Invoice: *$order_id*\n" .
                "Layanan: *$dataLayanan->layanan*\n" .
                "ID : *$request->uid*\n" .
                "Server : *$request->zone*\n" .
                "Nickname : *$request->nickname*\n" .
                "Harga: *Rp. " . number_format($dataLayanan->harga, 0, '.', ',') . "*\n" .
                "Status Pembelian: *Sukses*\n" .
               "Metode Pembayaran: *$request->payment_method*\n\n" .
               "*Invoice* : " . env("APP_URL") . "/id/invoices/$order_id\n\n" .
               "INI ADALAH PESAN OTOMATIS";
               
               $pesanSuksesAdmin = 
                "*Pembelian Sukses*\n\n" .
                "No Invoice: *$order_id*\n" .
                "Layanan: *$dataLayanan->layanan*\n" .
                "ID : *$request->uid*\n" .
                "Server : *$request->zone*\n" .
                "Nickname : *$request->nickname*\n" .
                "Harga: *Rp. " . number_format($dataLayanan->harga, 0, '.', ',') . "*\n" .
                "Status Pembelian: *Sukses*\n" .
               "Metode Pembayaran: *$request->payment_method*\n\n" .
               
               "*Invoice* : " . env("APP_URL") . "/id/invoices/$order_id\n\n" .
               "INI ADALAH PESAN OTOMATIS";

                $requestPesanSukses = $this->msg($request->nomor, $pesanSukses);
                $requestPesanSuksesAdmin = $this->msg($api->nomor_admin, $pesanSuksesAdmin);

                $ipController = new IPAddressController();
                $ipAddress = $ipController->getIPAddress($request);
                
                $user->update([
                    'balance' => $user->balance - $dataLayanan->harga
                ]);
                
            
                $pembelian = new Pembelian();
                $pembelian->username = Auth::user()->username;
                $pembelian->order_id = $order_id;
                $pembelian->user_id = ($request->ktg_tipe !== 'joki' && $request->ktg_tipe !== 'jokigendong' && $request->ktg_tipe !== 'vilogml') ? $request->uid : '-';
                $pembelian->zone = ($request->ktg_tipe !== 'joki' && $request->ktg_tipe !== 'jokigendong' && $request->ktg_tipe !== 'vilogml') ? $request->zone : '-';
                $pembelian->nickname = ($request->ktg_tipe !== 'joki' && $request->ktg_tipe !== 'jokigendong' && $request->ktg_tipe !== 'vilogml') ? $request->nickname : '-';
                $pembelian->log = ($request->ktg_tipe !== 'joki' && $request->ktg_tipe !== 'jokigendong' && $request->ktg_tipe !== 'vilogml') ? json_encode($order) : '';
                $pembelian->status = ($request->ktg_tipe !== 'joki' && $request->ktg_tipe !== 'jokigendong' && $request->ktg_tipe !== 'vilogml') ? 'Proses' : 'Proses';

                $pembelian->layanan = $dataLayanan->layanan;
                $pembelian->harga = $dataLayanan->harga;
                $pembelian->profit = $dataLayanan->harga * $dataLayanan->profit / 100;
                $pembelian->provider_order_id = $provider_order_id ? $provider_order_id : "";
                $pembelian->tipe_transaksi = $tipe;
                $pembelian->ip_address = $ipAddress;
                $pembelian->save();

                $pembayaran = new Pembayaran();
                $pembayaran->order_id = $order_id;
                $pembayaran->harga = $dataLayanan->harga;
                $pembayaran->no_pembayaran = "Balance Payment";
                $pembayaran->no_pembeli = $request->nomor;
                $pembayaran->status = 'Lunas';
                $pembayaran->metode = $request->payment_method;
                $pembayaran->reference = $reference;
                $pembayaran->save();      
                
                 
               if($request->ktg_tipe == 'joki' || $request->ktg_tipe == 'jokigendong' || $request->ktg_tipe == 'vilogml' || $request->ktg_tipe == 'joki-ranked'){
                $jokian = DB::table('data_joki')->insert([
                    'order_id' => $order_id,
                    'email_joki' => $request->ktg_tipe !== 'jokigendong' ? $request->email_joki : '-',
                    'password_joki' => $request->ktg_tipe !== 'jokigendong' ? $request->password_joki : '-',
                    'loginvia_joki' => $request->loginvia_joki,
                    'nickname_joki' => $request->ktg_tipe !== 'jokigendong' ? $request->nickname_joki : '-',
                    'request_joki' => $request->ktg_tipe !== 'jokigendong' ? $request->request_joki : '-',
                    'catatan_joki' => $request->catatan_joki,
                    
            'tglmain_joki' => $request->ktg_tipe !== 'jokigendong' ? '-' : $request->tglmain_joki,
            'jambooking_joki' => $request->ktg_tipe !== 'jokigendong' ? '-' : $request->jambooking_joki,
                    'qty' => $request->qty,
                    'status_joki' => 'Proses',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }


                
            }else{
                return response()->json([
                    'status' => false,
                    'data' => 'Server Error'
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'order_id' => $order_id
        ]);
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
