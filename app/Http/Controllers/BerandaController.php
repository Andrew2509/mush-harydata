<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Berita;
use App\Models\Tabpills;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    public function create()
    {
        $config = DB::table('setting_webs')->where('id', 1)->first();
        $customOrder = [8507, 8639, 8640, 8641, 8644, 8664];
        $kategori = Kategori::where('status', 'active')->get();
        $mlbb = Kategori::whereIn('id', $customOrder)
            ->orderByRaw("FIELD(id, " . implode(',', $customOrder) . ")")
            ->get()
            ->map(function ($item) {
                $item->tipe = "mlbb";
                return $item;
            });
        $banner = Berita::whereIn('tipe', ['banner', 'banner_beranda'])->get();
        $logoheader = Berita::where('tipe', 'logoheader')->latest()->first();
        $logofooter = Berita::where('tipe', 'logofooter')->latest()->first();
        $popup = Berita::where('tipe', 'popupp')->latest()->first();
        $pay_method = \App\Models\Method::all();

        $flashsale = \App\Models\Layanan::join('kategoris', 'kategoris.id', '=', 'layanans.kategori_id')
            ->join('paket_layanans', 'paket_layanans.layanan_id', '=', 'layanans.id')
            ->select('kategoris.thumbnail AS gmr_thumb', 'kategoris.kode AS kode_game', 'layanans.*', 'paket_layanans.product_logo')
            ->where('layanans.is_flash_sale', 1)
            ->where('layanans.expired_flash_sale', '>=', now())
            ->where('layanans.stock_flash_sale', '>', 0)
            ->get();

        $ratings = DB::table('ratings')
            ->join('pembelians', 'ratings.rating_id', '=', 'pembelians.order_id')
            ->join('pembayarans', 'ratings.rating_id', '=', 'pembayarans.order_id')
            ->leftJoin('kategoris', 'ratings.kategori_id', '=', 'kategoris.id')
            ->select('ratings.bintang', 'ratings.comment', 'ratings.id', 'ratings.created_at', 'pembelians.username', 'pembelians.layanan', 'pembayarans.no_pembeli', 'kategoris.nama AS kategori_nama')
            ->orderByDesc('ratings.id')
            ->limit(10)
            ->get();

        $total_users = \App\Models\User::count();
        $total_games = \App\Models\Kategori::where('status', 'active')->count();
        $total_products = \App\Models\Layanan::count();
        $total_transactions = \App\Models\Pembelian::count();

        $viewData = [
            'kategori' => $kategori,
            'mlbb' => $mlbb,
            'banner' => $banner,
            'logoheader' => $logoheader,
            'logofooter' => $logofooter,
            'popup' => $popup,
            'pay_method' => $pay_method,
            'flashsale' => $flashsale,
            'ratings' => $ratings,
            'total_users' => $total_users,
            'total_games' => $total_games,
            'total_products' => $total_products,
            'total_transactions' => $total_transactions,
        ];

        if (!$config) {
            $config = (object)[
                'id' => 1,
                'judul_web' => config('app.name', 'Mustopup'),
                'deskripsi_web' => 'Store Topup Game Terpercaya',
                'warna1' => '#f5c754',
                'warna2' => '#0f172a',
                'warna3' => '#1e293b',
            ];
        }

        return view('index', $viewData)->with([
            'title' => $config->judul_web ?? config('app.name', 'Mustopup'),
            'description' => $config->deskripsi_web ?? 'Store Topup Game Terpercaya',
            'config' => $config
        ]);
    }

    public function cariIndex(Request $request)
    {
        if($request->ajax()){
            $requestData = $request->validate([
                'data' => 'required|string',
            ]);

            $data = Kategori::where('nama', 'LIKE', '%'.$requestData['data'].'%')
                            ->where('status', 'active')
                            ->limit(6)
                            ->get();

            $res = '';
            foreach($data as $d){
                $themeColor = '#00E5FF';
                $namaLower = strtolower($d->nama);
                if(str_contains($namaLower, 'free fire')) $themeColor = '#f97316';
                elseif(str_contains($namaLower, 'genshin')) $themeColor = '#c084fc';
                elseif(str_contains($namaLower, 'valorant')) $themeColor = '#ef4444';
                elseif(str_contains($namaLower, 'pubg')) $themeColor = '#fbbf24';

                $res .= '
                    <a href="'.url("/id").'/'.$d->kode.'" class="group flex items-center p-4 rounded-2xl cursor-pointer hover:bg-white/5 transition-all duration-200 border border-transparent hover:border-white/10" style="--theme-color: '.$themeColor.';">
                        <div class="relative flex-shrink-0">
                            <div class="absolute inset-0 rounded-full blur-md opacity-0 group-hover:opacity-30 transition-opacity bg-[var(--theme-color)]"></div>
                            <div class="w-14 h-14 rounded-full overflow-hidden border border-white/10 relative z-10 transition-colors group-hover:border-[var(--theme-color)]">
                                <img alt="'.$d->nama.'" class="w-full h-full object-cover" src="'.$d->thumbnail.'"/>
                            </div>
                        </div>
                        <div class="ml-5 flex-grow">
                            <h3 class="text-white font-bold text-lg tracking-wide transition-colors group-hover:text-[var(--theme-color)]" style="font-family: \'Orbitron\', sans-serif;">'.$d->nama.'</h3>
                            <p class="text-sm text-gray-400" style="font-family: \'Rajdhani\', sans-serif;">'.($d->sub_nama ?? 'Mustopup').'</p>
                        </div>
                        <div class="opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                            <span class="material-symbols-outlined text-[var(--theme-color)]">chevron_right</span>
                        </div>
                    </a>';
            }
            return $res;
        }
    }
}
