<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Poin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetugasTransaksiController extends Controller
{
    public function index()
    {
        // Hanya melihat transaksi yang ditugaskan kepadanya
        $transaksis = Transaksi::with(['user', 'jenisSampah'])
            ->where('petugas_id', Auth::id())
            ->orderByDesc('transaksi_id')
            ->paginate(10);

        return view('petugas.penjemputan', compact('transaksis'));
    }

    public function update(Request $request, $transaksi_id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Petugas,Menuju Lokasi,Sedang Diangkut,Selesai',
        ]);

        $transaksi = Transaksi::with(['user', 'jenisSampah'])->findOrFail($transaksi_id);

        // Pastikan hanya petugas yang ditugaskan yang bisa update
        if ($transaksi->petugas_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Jika status sudah selesai, tidak bisa diubah lagi
        if ($transaksi->status === 'Selesai') {
            return redirect()->back()->with('error', 'Transaksi yang sudah selesai tidak dapat diubah statusnya.');
        }

        $statusLama = $transaksi->status;
        $statusBaru = $request->status;

        $transaksi->status = $statusBaru;
        $transaksi->save();

        // Point Reward Integration: Jika status diubah menjadi "Selesai"
        if ($statusBaru === 'Selesai' && $statusLama !== 'Selesai') {
            $nasabah = $transaksi->user;
            $jenisSampah = $transaksi->jenisSampah;

            if ($nasabah && $jenisSampah && $jenisSampah->harga_sampah > 0) {
                // Perhitungan poin: jumlah (kg) * harga/poin per kg dari jenis sampah
                // atau jika ada field poin_per_kg, sesuaikan dengan logic. Di sini kita asumsikan harga_sampah sebagai poin per kg
                // atau admin previously just added manual points. For automation, let's use harga_sampah * jumlah
                $jumlahPoin = ceil($transaksi->jumlah * $jenisSampah->harga_sampah);

                if ($jumlahPoin > 0) {
                    DB::transaction(function () use ($nasabah, $transaksi, $jumlahPoin) {
                        $nasabah->total_poin += $jumlahPoin;
                        $nasabah->save();

                        Poin::create([
                            'nasabah_id' => $nasabah->id,
                            'jumlah_poin' => $jumlahPoin,
                            'transaksi_id' => $transaksi->transaksi_id,
                            'tanggal_diberikan' => now(),
                        ]);
                    });
                }
            }
        }

        return redirect()->back()->with('success', 'Status penjemputan berhasil diperbarui.');
    }
}