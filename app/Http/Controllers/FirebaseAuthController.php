<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FirebaseAuthController extends Controller
{
    public function handleGoogleLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'uid' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Check if username exists, if not use email prefix
            $username = explode('@', $request->email)[0];
            $baseUsername = $username;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $username,
                'no_wa' => '', // Initialize with empty string to avoid "no default value" error
                'password' => Hash::make(Str::random(16)),
                'role' => 'Member',
                'balance' => 0,
            ]);
        }

        Auth::login($user, true);

        return response()->json([
            'success' => true,
            'redirect' => route('home')
        ]);
    }
}
