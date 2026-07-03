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
        // Filter details array to only include rows with non-empty jenis_sampah_id and berat
        $detailsArray = [];
        if ($request->has('details') && is_array($request->details)) {
            foreach ($request->details as $detail) {
                if (!empty($detail['jenis_sampah_id']) && !empty($detail['berat'])) {
                    $detailsArray[] = $detail;
                }
            }
        }

        $request->merge(['details_filtered' => $detailsArray]);

        $request->validate([
            'status' => 'required|in:Menunggu Petugas,Menuju Lokasi,Sedang Diangkut,Selesai',
            'details_filtered' => 'nullable|array',
            'details_filtered.*.jenis_sampah_id' => 'required|exists:jenis_sampah,jenis_sampah_id',
            'details_filtered.*.berat' => 'required|numeric|min:0.01|max:999999.99',
        ], [
            'details_filtered.*.jenis_sampah_id.required' => 'Jenis sampah harus dipilih untuk setiap item.',
            'details_filtered.*.jenis_sampah_id.exists' => 'Jenis sampah yang dipilih tidak valid.',
            'details_filtered.*.berat.required' => 'Berat harus diisi untuk setiap item.',
            'details_filtered.*.berat.min' => 'Berat minimal 0.01 kg untuk setiap item.',
            'details_filtered.*.berat.numeric' => 'Berat harus berupa angka untuk setiap item.',
        ]);

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
        if ($statusBaru === 'Selesai' && empty($request->details_filtered)) {
            return redirect()->back()->with('error', 'Mohon input jenis sampah dan berat sebelum menyelesaikan transaksi.');
        }

        DB::transaction(function () use ($request, $transaksi, $statusLama, $statusBaru) {
            // Update status
            $transaksi->status = $statusBaru;
            $transaksi->save();

            // Jika ada detail yang diinput, simpan atau update
            if (!empty($request->details_filtered)) {
                // Hapus detail lama jika ada
                $transaksi->details()->delete();

                // Simpan detail baru dari validated/filtered data
                foreach ($request->details_filtered as $detail) {
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
