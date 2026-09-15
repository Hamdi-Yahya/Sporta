<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /** Proses login — redirect sesuai role (FR-A2) */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $ip = $request->ip();
        $email = strtolower($request->input('email'));
        $lockKey = 'login_lock:' . $email . ':' . $ip;
        $attemptsKey = 'login_attempts:' . $email . ':' . $ip;

        // Cek apakah sedang terkunci
        if (Cache::has($lockKey)) {
            $seconds = Cache::get($lockKey) - time();
            $minutes = ceil($seconds / 60);
            if ($minutes < 1) $minutes = 1;
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Akun terkunci, silakan coba lagi dalam {$minutes} menit.",
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Berhasil login, hapus riwayat percobaan dan kunci
            Cache::forget($lockKey);
            Cache::forget($attemptsKey);
            
            $request->session()->regenerate();

            return match (Auth::user()->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'owner' => redirect()->route('owner.dashboard'),
                default => redirect()->route('player.dashboard'),
            };
        }

        // Jika gagal, catat percobaan
        $attempts = Cache::get($attemptsKey, 0) + 1;
        Cache::put($attemptsKey, $attempts, now()->addHours(24));

        if ($attempts >= 5) {
            $penaltyTier = $attempts - 5;
            // penaltyTier 0 = 1 menit
            // penaltyTier 1 = 5 menit
            // penaltyTier 2 = 10 menit, dst
            $minutes = $penaltyTier == 0 ? 1 : $penaltyTier * 5;
            
            Cache::put($lockKey, time() + ($minutes * 60), now()->addMinutes($minutes));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
