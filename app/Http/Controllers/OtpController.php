<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        $phone = $request->phone;
        
        // Format phone number to 62...
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '+')) {
            $phone = substr($phone, 1);
        }

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);
        
        // Store in session for 5 minutes
        Session::put('whatsapp_otp', $otp);
        Session::put('whatsapp_phone', $phone);
        Session::put('otp_expires_at', now()->addMinutes(5));

        // Get WA Key from settings
        $settings = DB::table('setting_webs')->where('id', 1)->first();
        $waKey = $settings->wa_key;
        
        if (!$waKey) {
            return response()->json(['success' => false, 'message' => 'WhatsApp Gateway belum dikonfigurasi oleh admin.']);
        }

        $message = "Kode OTP pendaftaran Mustopup Anda adalah: *$otp*. Berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.";

        try {
            $response = Http::withHeaders([
                'Authorization' => $waKey,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] == true) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Gagal mengirim WhatsApp: ' . ($result['reason'] ?? 'Unknown error')]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    public function sendOtpForgot(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        $phone = $request->phone;
        
        // Format phone number
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '+')) {
            $phone = substr($phone, 1);
        }

        // Check if user exists
        $user = \App\Models\User::where('no_wa', $phone)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Nomor WhatsApp tidak terdaftar dalam sistem kami.']);
        }

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);
        
        // Store in session
        Session::put('forgot_otp', $otp);
        Session::put('forgot_phone', $phone);
        Session::put('forgot_user_id', $user->id);
        Session::put('forgot_otp_expires_at', now()->addMinutes(5));

        // Get WA Key
        $settings = DB::table('setting_webs')->where('id', 1)->first();
        $waKey = $settings->wa_key;
        
        if (!$waKey) {
            return response()->json(['success' => false, 'message' => 'WhatsApp Gateway belum dikonfigurasi oleh admin.']);
        }

        $message = "Kode OTP lupa password Mustopup Anda adalah: *$otp*. Berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.";

        try {
            $response = Http::withHeaders([
                'Authorization' => $waKey,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] == true) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Gagal mengirim WhatsApp: ' . ($result['reason'] ?? 'Unknown error')]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    public function verifyOtpForgot(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $storedOtp = Session::get('forgot_otp');
        $expiresAt = Session::get('forgot_otp_expires_at');

        if (!$storedOtp || !$expiresAt || now()->greaterThan($expiresAt)) {
            return response()->json(['success' => false, 'message' => 'Kode OTP kadaluarsa atau tidak ditemukan.']);
        }

        if ($request->otp == $storedOtp) {
            Session::put('forgot_otp_verified', true);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Kode OTP salah.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $storedOtp = Session::get('whatsapp_otp');
        $expiresAt = Session::get('otp_expires_at');

        if (!$storedOtp || !$expiresAt || now()->greaterThan($expiresAt)) {
            return response()->json(['success' => false, 'message' => 'Kode OTP kadaluarsa atau tidak ditemukan.']);
        }

        if ($request->otp == $storedOtp) {
            Session::put('otp_verified', true);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Kode OTP salah.']);
    }

    public function sendProfileOtp(Request $request)
    {
        $user = Auth()->user();
        $phone = $user->no_wa;
        
        if (!$phone) {
            return response()->json(['success' => false, 'message' => 'Nomor WhatsApp Anda tidak terdaftar.']);
        }

        // Format phone number
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '+')) {
            $phone = substr($phone, 1);
        }

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);
        
        // Store in session
        Session::put('profile_otp', $otp);
        Session::put('profile_otp_expires_at', now()->addMinutes(5));

        // Get WA Key
        $settings = DB::table('setting_webs')->where('id', 1)->first();
        $waKey = $settings->wa_key;
        
        if (!$waKey) {
            return response()->json(['success' => false, 'message' => 'WhatsApp Gateway belum dikonfigurasi oleh admin.']);
        }

        $message = "Kode OTP perubahan profil/password Mustopup Anda adalah: *$otp*. Berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.";

        try {
            $response = Http::withHeaders([
                'Authorization' => $waKey,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] == true) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Gagal mengirim WhatsApp: ' . ($result['reason'] ?? 'Unknown error')]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    public function verifyProfileOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $storedOtp = Session::get('profile_otp');
        $expiresAt = Session::get('profile_otp_expires_at');

        if (!$storedOtp || !$expiresAt || now()->greaterThan($expiresAt)) {
            return response()->json(['success' => false, 'message' => 'Kode OTP kadaluarsa atau tidak ditemukan.']);
        }

        if ($request->otp == $storedOtp) {
            Session::put('profile_otp_verified', true);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Kode OTP salah.']);
    }
}
