<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Route;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();

        // 1. Cek apakah user adalah Pelaku Usaha
        if ($user && $user->hasRole('pelaku_usaha')) {
            return redirect()->intended(route('pelaku_usaha.dashboard'));
        }

        // 2. Cek apakah user adalah Petugas
        if ($user && $user->hasRole('petugas')) {
            if (Route::has('petugas.dashboard')) {
                return redirect()->intended(route('petugas.dashboard'));
            }

            return redirect()->intended(route('penjemputan'));
        }

        // 3. Cek apakah user adalah Admin
        if ($user && $user->hasRole('admin')) {
            if (Route::has('admin.dashboard')) {
                return redirect()->intended(route('admin.dashboard'));
            }
        }

        // 4. Default redirect buat Nasabah / User biasa
        return redirect()->intended(route('dashboard'));
    }
}