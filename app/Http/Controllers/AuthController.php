<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Kalau udah login, langsung lempar ke halaman utama, nggak usah login lagi
        if (Auth::guard('akun')->check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $aturanValidasi = [
            'username' => 'required|string',
            'password' => 'required|string',
        ];

        // Captcha cuma wajib diisi kalau fiturnya lagi aktif
        if (config('services.recaptcha.enabled')) {
            $aturanValidasi['g-recaptcha-response'] = 'required';
        }

        $request->validate($aturanValidasi, [
            'g-recaptcha-response.required' => 'Silakan centang captcha terlebih dahulu!',
        ]);

        // Verifikasi ke Google cuma dijalanin kalau captcha aktif
        if (config('services.recaptcha.enabled')) {
            $verifikasi = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret'),
                'response' => $request->input('g-recaptcha-response'),
            ]);

            if (!$verifikasi->json('success')) {
                \Log::info('Captcha gagal, respon Google:', $verifikasi->json());
                return back()->withErrors(['captcha' => 'Verifikasi captcha gagal, silakan coba lagi!'])->withInput();
            }
        }

        $kredensial = $request->only('username', 'password');

        if (Auth::guard('akun')->attempt($kredensial)) {
            $request->session()->regenerate();
            return redirect()->route('home')->with('sukses', 'Login berhasil!');
        }

        return back()->withErrors(['username' => 'Username atau password salah!'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('akun')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}