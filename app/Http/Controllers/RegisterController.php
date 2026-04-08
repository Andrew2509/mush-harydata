<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PendingRefund;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Berita;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register', [
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|min:3|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|max:255',
            'no_wa' => 'required|numeric|unique:users,no_wa'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Cek apakah sudah diverifikasi via WhatsApp (Server-side session check)
        if (!Session::has('otp_verified') || Session::get('otp_verified') !== true) {
            return redirect()->back()->with('error', 'Silakan verifikasi nomor WhatsApp Anda terlebih dahulu menggunakan kode OTP.')->withInput();
        }

        // Hapus session OTP setelah berhasil digunakan
        Session::forget(['whatsapp_otp', 'whatsapp_phone', 'otp_expires_at', 'otp_verified']);

        // Hash password
        $hashedPassword = Hash::make($request->password);

        // Sanitasi nomor WhatsApp
        $no_wa = $request->no_wa;
        if ($no_wa[0] == '0') {
            $no_wa = '62' . substr($no_wa, 1);
        }

        // Cek pending refunds sebelum buat user
        $nomorVariasi = [$no_wa, $request->no_wa];
        if (str_starts_with($no_wa, '62')) {
            $nomorVariasi[] = '0' . substr($no_wa, 2);
        }
        $pendingRefunds = PendingRefund::where('status', 'pending')
            ->whereIn('no_pembeli', $nomorVariasi)
            ->get();

        $initialBalance = 0;
        $refundCount = 0;

        // Simpan data pengguna
        $user = new User();
        $user->name = htmlspecialchars($request->nama, ENT_QUOTES, 'UTF-8');
        $user->username = htmlspecialchars($request->username, ENT_QUOTES, 'UTF-8');
        $user->email = htmlspecialchars($request->email, ENT_QUOTES, 'UTF-8');
        $user->password = $hashedPassword;
        $user->no_wa = htmlspecialchars($no_wa, ENT_QUOTES, 'UTF-8');
        $user->role = 'Member';

        // Jika ada pending refund, tambahkan ke saldo awal
        if ($pendingRefunds->isNotEmpty()) {
            foreach ($pendingRefunds as $refund) {
                $initialBalance += $refund->jumlah;
                $refundCount++;
                $refund->update([
                    'status' => 'claimed',
                    'claimed_by' => $request->username,
                ]);
            }

            Log::info('Pending refund claimed saat registrasi', [
                'username' => $request->username,
                'no_wa' => $no_wa,
                'total_refund' => $initialBalance,
                'jumlah_order' => $refundCount,
            ]);
        }

        $user->balance = $initialBalance;
        $user->save();

        $successMessage = 'Berhasil melakukan pendaftaran, silakan masuk menggunakan akun Anda.';
        if ($refundCount > 0) {
            $successMessage .= ' Dana pengembalian sebesar Rp. ' . number_format($initialBalance, 0, '.', ',') . ' dari ' . $refundCount . ' transaksi gagal telah ditambahkan ke saldo Anda.';
        }

        return redirect(route('login'))->with('success', $successMessage);
    }
}
