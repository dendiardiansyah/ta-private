<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;  // Model untuk transaksi
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    // Menampilkan form untuk membuat transaksi baru
    public function create()
    {
        return view('penjemputan'); // Menampilkan form transaksi
    }

    // Menyimpan transaksi baru ke database
    public function store(Request $request)
    {
        // Validasi data dari form
        $request->validate([
            'alamat_penjemputan' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
            'tanggal_transaksi' => 'required|date',
        ]);

        // Membuat transaksi baru
        $transaksi = Transaksi::create([
            'pelaku_usaha_id' => null, // Menyimpan pelaku usaha dari pengguna yang login
            'alamat_penjemputan' => $request->alamat_penjemputan,
            'jumlah' => $request->jumlah,
            'tanggal_transaksi' => $request->tanggal_transaksi,
        ]);

        // Redirect setelah berhasil menyimpan transaksi
        return redirect()->route('penjemputan.create')->with('success', 'Transaksi berhasil dibuat!');
    }
}
