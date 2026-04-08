<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Pembayaran;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Layanan;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function create()
    {
        $total_users = User::count();
        $active_users = User::whereNotNull('updated_at')->count();
        $total_games = Kategori::count();
        $total_services = Layanan::count();
        $users = User::selectRaw('DATE(created_at) as date, COUNT(*) as total_users')
                     ->groupBy('date')
                     ->get();
        $userChartData = $users->map(function ($item) {
            return [
                'tanggal' => Carbon::parse($item->date)->toDateString(),
                'pengguna' => $item->total_users,
            ];
        })->toArray();
        $visitors = Cache::get('visitors', []);
        $visitorChartData = [];
        foreach ($visitors as $date => $visitorsArray) {
            $visitorChartData[] = [
                'tanggal' => Carbon::parse($date)->toDateString(),
                'pengunjung' => count($visitorsArray),
            ];
        }
        $date = now();
        $dayPlusOne = Carbon::Create($date)->addDays(1);
        $pastWeek = Carbon::create($date)->subWeeks(1);

        $arrayDate = [];
        $grafik = Pembelian::whereBetween('created_at', [$pastWeek, $dayPlusOne])->orderBy('created_at', 'asc')->get();

        foreach($grafik as $data){
            $pembelian = Pembelian::whereDate('created_at', $data->created_at)->count();
            array_push($arrayDate,array('y' => Carbon::parse($data->created_at)->isoFormat('D/M/Y'), 'a' => $pembelian));
        }

        $totalPembelian = Pembelian::whereDay('created_at', $date->day)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->sum('harga');

        $banyakPembelian = Pembelian::whereDay('created_at', $date->day)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->count();

        $totalPembelianSuccess = Pembelian::whereDay('created_at', $date->day)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->where('status', 'Sukses')
            ->sum('harga');

        $banyakPembelianSuccess = Pembelian::whereDay('created_at', $date->day)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->where('status', 'Sukses')
            ->count();

        $totalPembelianBatal = Pembelian::whereDay('created_at', $date->day)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->where(function($query) {
                $query->where('status', 'Batal')
                      ->orWhere('status', 'Gagal');
            })
            ->sum('harga');

        $banyakPembelianBatal = Pembelian::whereDay('created_at', $date->day)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->where(function($query) {
                $query->where('status', 'Batal')
                      ->orWhere('status', 'Gagal');
            })
            ->count();

        $totalPembelianPending = Pembelian::whereDay('created_at', $date->day)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->where('status', 'Pending')
            ->sum('harga');

        $banyakPembelianPending = Pembelian::whereDay('created_at', $date->day)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->where('status', 'Pending')
            ->count();

        $totalPembayaran = Pembayaran::whereDay('created_at', $date->day)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->where('status', 'Lunas')
            ->sum('harga');

        $banyakPembayaran = Pembayaran::whereDay('created_at', $date->day)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->where('status', 'Lunas')
            ->count();

        // --- Recent Transactions (latest 10 orders) ---
        $recent_transactions = Pembelian::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // --- Popular Games (top 5 categories by real purchase count via join) ---
        $popular_games = Kategori::select('kategoris.nama', 'kategoris.thumbnail', DB::raw('count(pembelians.id) as total_topups'))
            ->leftJoin('layanans', 'layanans.kategori_id', '=', 'kategoris.id')
            ->leftJoin('pembelians', 'pembelians.layanan', '=', 'layanans.layanan')
            ->groupBy('kategoris.id', 'kategoris.nama', 'kategoris.thumbnail')
            ->orderBy('total_topups', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'total_pembelian' => $totalPembelian,
            'total_pembelian_batal' => $totalPembelianBatal,
            'total_pembelian_pending' => $totalPembelianPending,
            'total_pembelian_success' => $totalPembelianSuccess,
            'banyak_pembelian' => $banyakPembelian,
            'banyak_pembelian_batal' => $banyakPembelianBatal,
            'banyak_pembelian_pending' => $banyakPembelianPending,
            'banyak_pembelian_success' => $banyakPembelianSuccess,
            'total_deposit' => $totalPembayaran,
            'banyak_deposit' => $banyakPembayaran,
            'total_keseluruhan_pembelian' => Pembelian::sum('harga'),
            'banyak_keseluruhan_pembelian' => Pembelian::count(),
            'total_keseluruhan_pembelian_berhasil' => Pembelian::where('status', 'Sukses')->orWhere('status', 'Success')->sum('harga'),
            'banyak_keseluruhan_pembelian_berhasil' => Pembelian::where('status', 'Sukses')->orWhere('status', 'Success')->count(),
            'total_keseluruhan_pembelian_pending' => Pembelian::where('status', 'Pending')->sum('harga'),
            'banyak_keseluruhan_pembelian_pending' => Pembelian::where('status', 'Pending')->count(),
            'total_keseluruhan_pembelian_batal' => Pembelian::where(function($query) {
                $query->where('status', 'Batal')
                      ->orWhere('status', 'Gagal');
            })->sum('harga'),
            'banyak_keseluruhan_pembelian_batal' => Pembelian::where(function($query) {
                $query->where('status', 'Batal')
                      ->orWhere('status', 'Gagal');
            })->count(),
            'total_keseluruhan_deposit' => Pembayaran::where('status', 'Lunas')->sum('harga'),
            'banyak_keseluruhan_deposit' => Pembayaran::where('status', 'Lunas')->count(),
            'keuntungan_bersih' => Pembelian::where('status', 'Sukses')->orWhere('status', 'Success')->sum('profit'),
            'morris_data' => json_encode($arrayDate),
            'grafik_chart' => json_encode($userChartData),
            'total_users' => $total_users,
            'active_users' => $active_users,
            'total_games' => $total_games,
            'total_services' => $total_services,
            'visitor_chart' => json_encode($visitorChartData),
            'recent_transactions' => $recent_transactions,
            'popular_games' => $popular_games,
        ]);
    }
}
