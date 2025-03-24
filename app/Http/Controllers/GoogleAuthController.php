<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google’s OAuth page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function callback(Request $request)
    {
        try {
            // Ambil informasi user dari Google
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect('/')->with('error', 'Google authentication failed.');
        }

        // Cek apakah user sudah terdaftar
        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            // Jika belum ada, buat user baru
            $user = User::firstOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'password' => Hash::make(uniqid()), // Password random
                    'email_verified_at' => now(),
                ],
            );
        }

        // Login user dan regenerasi session
        Auth::login($user, true); // true untuk Remember Me
        $request->session()->regenerate();

        // Redirect ke dashboard
        return redirect('/dashboard');
    }
}
