<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Berita;
use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email', [
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        ]);
    }

    public function showResetFormAfterOtp()
    {
        if (!Session::has('forgot_otp_verified') || Session::get('forgot_otp_verified') !== true) {
            return redirect()->route('password.request')->with('error', 'Silakan verifikasi nomor WhatsApp Anda terlebih dahulu.');
        }

        $userId = Session::get('forgot_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('password.request')->with('error', 'Pengguna tidak ditemukan.');
        }

        return view('auth.passwords.reset', [
            'username' => $user->username,
            'token' => 'verified_via_otp', // dummy token for compatibility
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Session::has('forgot_otp_verified') || Session::get('forgot_otp_verified') !== true) {
            return redirect()->route('password.request')->with('error', 'Sesi verifikasi Anda telah berakhir. Silakan ulangi proses.');
        }

        $userId = Session::get('forgot_user_id');
        $user = User::where('id', $userId)->where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['error' => 'Data pengguna tidak sesuai dengan sesi verifikasi.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session after success
        Session::forget(['forgot_otp', 'forgot_phone', 'forgot_user_id', 'forgot_otp_expires_at', 'forgot_otp_verified']);

        return redirect()->route('login')->with('success', 'Password Anda berhasil diperbarui. Silakan login dengan password baru.');
    }
}
