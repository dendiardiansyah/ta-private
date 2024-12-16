<?php

namespace App\Http\Controllers;

use App\Models\PenarikanPoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenarikanPoinController extends Controller
{
    // Menampilkan halaman penarikan poin
    // PenarikanController.php
    public function index()
    {
        // Ambil riwayat penarikan poin yang sudah dilakukan oleh user
        $penarikanPoin = PenarikanPoin::where('nasabah_id', Auth::id())->get();

        return view('penarikan', compact('penarikanPoin'));
    }

    // Proses penarikan poin
    public function store(Request $request)
    {
        // Validasi input jumlah poin yang ingin ditarik
        $request->validate([
            'jumlah_poin' => 'required|integer|min:100',
        ]);

        $user = Auth::user();

        // Cek apakah pengguna memiliki cukup poin untuk ditarik
        if ($user->total_poin < $request->jumlah_poin) {
            return redirect()->back()->with('error', 'Poin Anda tidak cukup untuk ditarik.');
        }

        // Simpan data penarikan poin
        $penarikan = PenarikanPoin::create([
            'nasabah_id' => $user->id,
            'jumlah_poin' => $request->jumlah_poin,
            'jumlah_uang' => $this->hitungNilaiUang($request->jumlah_poin),
            'status_penarikan' => 'diproses',  // Status awal penarikan
        ]);

        // Kurangi total poin pengguna
        $user->total_poin -= $request->jumlah_poin;
        $user->save();

        // Beri tahu pengguna bahwa penarikan poin berhasil
        return redirect()->route('penarikan')->with('success', 'Penarikan poin berhasil diajukan.');
    }

    // Fungsi untuk menghitung nilai uang berdasarkan jumlah poin
    private function hitungNilaiUang($jumlahPoin)
    {
        $nilaiPerPoin = 100;  // Asumsi nilai per poin (misalnya, 1 poin = 100 uang)
        return $jumlahPoin * $nilaiPerPoin;  // Mengembalikan hasil perhitungan
    }
}
