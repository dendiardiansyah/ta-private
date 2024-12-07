<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PelakuUsaha;

class PelakuUsahaController extends Controller
{
    public function showLoginForm()
    {
        return view('pelaku_usaha/login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('nama', 'password');

        if (Auth::guard('pelaku_usaha')->attempt($credentials)) {
            return redirect()->route('pelaku_usaha.dashboard');
        }

        return back()->withErrors(['message' => 'Login gagal, cek kembali nama atau password Anda.']);
    }


    public function logout(Request $request)
    {
        Auth::guard('pelaku_usaha')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/pelaku-usaha/login');
    }
}
