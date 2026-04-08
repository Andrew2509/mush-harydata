<?php

namespace App\Http\Controllers\leaderboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Berita;

class LeaderboardController extends Controller 
{
    public function leaderboard()
    {
        $top10Today = DB::table('pembelians')
            ->select('username', DB::raw('SUM(harga) as total_harga'))
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'Sukses')
            ->whereNotNull('username')
            ->groupBy('username')
            ->orderBy('total_harga', 'desc')
            ->limit(10)
            ->get();

        $top10ThisWeek = DB::table('pembelians')
            ->select('username', DB::raw('SUM(harga) as total_harga'))
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status', 'Sukses')
            ->whereNotNull('username')
            ->groupBy('username')
            ->orderBy('total_harga', 'desc')
            ->limit(10)
            ->get();

        $top10ThisMonth = DB::table('pembelians')
            ->select('username', DB::raw('SUM(harga) as total_harga'))
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('status', 'Sukses')
            ->whereNotNull('username')
            ->groupBy('username')
            ->orderBy('total_harga', 'desc')
            ->limit(10)
            ->get();

        return view('pages.leaderboard', [
            'top10Today' => $top10Today,
            'top10ThisWeek' => $top10ThisWeek,
            'top10ThisMonth' => $top10ThisMonth,
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        ]);
    }
}
