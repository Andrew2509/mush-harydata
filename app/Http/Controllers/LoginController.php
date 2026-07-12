<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Berita;
use App\Models\PendingRefund;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login', [
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cek apakah role pengguna adalah Admin
            if ($user->role === 'Admin') {
                Auth::logout();
                return redirect()->route('login')->withErrors(['error' => 'Username / password mismatch']);
            }

            // Cek apakah role pengguna adalah Member, Platinum, atau Gold
            if (in_array($user->role, ['Member', 'Platinum', 'Gold'])) {
                // Proses pending refund berdasarkan nomor telepon
                $this->claimPendingRefunds($user);

                return redirect()->intended(route('home'));
            } else {
                Auth::logout();
                return redirect()->route('login')->withErrors(['error' => 'Username / password mismatch']);
            }
        }

        throw ValidationException::withMessages([
            'error' => ['Username / password mismatch'],
        ]);
    }

    /**
     * Claim pending refunds berdasarkan nomor telepon user
     */
    private function claimPendingRefunds($user)
    {
        if (empty($user->no_wa)) {
            return;
        }

        // Cari pending refunds yang cocok dengan nomor telepon user
        // Coba beberapa format nomor: 62xxx, 0xxx
        $nomorVariasi = [$user->no_wa];
        if (str_starts_with($user->no_wa, '62')) {
            $nomorVariasi[] = '0' . substr($user->no_wa, 2);
        } elseif (str_starts_with($user->no_wa, '0')) {
            $nomorVariasi[] = '62' . substr($user->no_wa, 1);
        }

        $pendingRefunds = PendingRefund::where('status', 'pending')
            ->whereIn('no_pembeli', $nomorVariasi)
            ->get();

        if ($pendingRefunds->isEmpty()) {
            return;
        }

        $totalRefund = 0;
        $refundDetails = [];

        foreach ($pendingRefunds as $refund) {
            $totalRefund += $refund->jumlah;
            $refundDetails[] = $refund->order_id . ' (Rp. ' . number_format($refund->jumlah, 0, '.', ',') . ')';

            $refund->update([
                'status' => 'claimed',
                'claimed_by' => $user->username,
            ]);
        }

        // Tambahkan ke saldo user
        $user->update([
            'balance' => $user->balance + $totalRefund,
        ]);

        Log::info('Pending refund claimed setelah login', [
            'username' => $user->username,
            'no_wa' => $user->no_wa,
            'total_refund' => $totalRefund,
            'jumlah_order' => count($refundDetails),
        ]);

        // Flash message untuk ditampilkan di halaman
        session()->flash('refund_claimed', [
            'total' => $totalRefund,
            'count' => count($refundDetails),
            'details' => $refundDetails,
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
