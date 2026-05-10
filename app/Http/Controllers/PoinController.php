<?php

namespace App\Http\Controllers;

use App\Models\Poin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PoinController extends Controller
{
    // Menampilkan riwayat poin nasabah
    public function index()
    {
        // Ambil data poin berdasarkan nasabah yang sedang login
        // Kita ambil relasi transaksi agar bisa menampilkan data transaksi
        $poinRecords = Poin::where('nasabah_id', Auth::id())
            ->with('transaksi') // Relasi untuk mendapatkan data transaksi
            ->orderBy('tanggal_diberikan', 'desc') // Urutkan berdasarkan tanggal pemberian poin
            ->get();

        // Kirim data poin ke view

        return view('user.poin', compact('poinRecords'));
    }

    // Method untuk menangani penarikan poin

}
