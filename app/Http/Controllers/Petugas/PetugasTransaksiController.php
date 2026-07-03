<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\JenisSampah;
use App\Models\Poin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetugasTransaksiController extends Controller
{
    public function index()
    {
        // Hanya melihat transaksi yang ditugaskan kepadanya dengan detail
        $transaksis = Transaksi::with(['user', 'details.jenisSampah'])
            ->where('petugas_id', Auth::id())
            ->orderByDesc('transaksi_id')
            ->paginate(10);

        // Load jenis sampah untuk form input
        $jenisSampahList = JenisSampah::orderBy('nama_jenis')->get();

        return view('petugas.penjemputan', compact('transaksis', 'jenisSampahList'));
    }

    public function update(Request $request, $transaksi_id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Menunggu Petugas,Menuju Lokasi,Sedang Diangkut,Selesai',
        ]);

        $validJenisSampahIds = JenisSampah::pluck('jenis_sampah_id')->all();

        // Ambil hanya baris detail yang benar-benar valid.
        // Ini mencegah satu row kosong/aneh menggagalkan seluruh submit.
        $details = [];
        foreach ((array) $request->input('details', []) as $detail) {
            $jenisSampahId = $detail['jenis_sampah_id'] ?? null;
            $berat = $detail['berat'] ?? null;

            if ($jenisSampahId === null || $jenisSampahId === '' || $berat === null || $berat === '') {
                continue;
            }

            if (!is_numeric($jenisSampahId) || !is_numeric($berat) || (float) $berat < 0.01) {
                continue;
            }

            if (!in_array((int) $jenisSampahId, $validJenisSampahIds, true)) {
                continue;
            }

            $details[] = [
                'jenis_sampah_id' => (int) $jenisSampahId,
                'berat' => number_format((float) $berat, 2, '.', ''),
            ];
        }

        $transaksi = Transaksi::with(['user', 'details'])->findOrFail($transaksi_id);

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

        // Jika status diubah ke "Selesai", pastikan detail sudah diinput
        if ($statusBaru === 'Selesai' && empty($details)) {
            return redirect()->back()->with('error', 'Mohon input jenis sampah dan berat sebelum menyelesaikan transaksi.');
        }

        DB::transaction(function () use ($details, $transaksi, $statusLama, $statusBaru) {
            // Update status
            $transaksi->status = $statusBaru;
            $transaksi->save();

            // Jika ada detail yang diinput, simpan atau update
            if (!empty($details)) {
                // Hapus detail lama jika ada
                $transaksi->details()->delete();

                // Simpan detail baru dari validated/filtered data
                foreach ($details as $detail) {
                    TransaksiDetail::create([
                        'transaksi_id' => $transaksi->transaksi_id,
                        'jenis_sampah_id' => $detail['jenis_sampah_id'],
                        'berat' => $detail['berat'],
                    ]);
                }

                // Reload details untuk perhitungan poin
                $transaksi->load('details.jenisSampah');
            }

            // Point Reward Integration: Jika status diubah menjadi "Selesai"
            if ($statusBaru === 'Selesai' && $statusLama !== 'Selesai') {
                $nasabah = $transaksi->user;

                if ($nasabah && $transaksi->details->isNotEmpty()) {
                    // Perhitungan poin dari semua detail
                    $totalPoin = 0;

                    foreach ($transaksi->details as $detail) {
                        if ($detail->jenisSampah && $detail->jenisSampah->harga_sampah > 0) {
                            // Formula: ceil(berat × harga_sampah_per_kg)
                            $poinPerItem = ceil($detail->berat * $detail->jenisSampah->harga_sampah);
                            $totalPoin += $poinPerItem;
                        }
                    }

                    if ($totalPoin > 0) {
                        // Update total poin nasabah
                        $nasabah->total_poin += $totalPoin;
                        $nasabah->save();

                        // Catat ke ledger poin
                        Poin::create([
                            'nasabah_id' => $nasabah->id,
                            'jumlah_poin' => $totalPoin,
                            'transaksi_id' => $transaksi->transaksi_id,
                            'tanggal_diberikan' => now(),
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Status penjemputan berhasil diperbarui.');
    }
}
