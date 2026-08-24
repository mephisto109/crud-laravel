<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekLevel
{
    // $levelDiizinkan itu daftar level yang boleh masuk, dikirim dari route (misal: level:1,2)
    public function handle(Request $request, Closure $next, ...$levelDiizinkan): Response
    {
        $levelUser = Auth::guard('akun')->user()->level;

        if (!in_array($levelUser, $levelDiizinkan)) {
            abort(403, 'Kamu nggak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}