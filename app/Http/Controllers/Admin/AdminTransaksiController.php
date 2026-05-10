<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['user', 'jenisSampah'])
            ->orderByDesc('transaksi_id')
            ->paginate(10);

        return view('admin.transaksi', compact('transaksis'));
    }

    public function update(Request $request, int $transaksi_id)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
            'poin' => 'nullable|numeric|min:0',
        ]);

        $transaksi = Transaksi::with('user')->findOrFail($transaksi_id);
        $transaksi->status = $request->string('status')->toString();
        $transaksi->save();

        if ($transaksi->status === 'disetujui') {
            $jumlahPoin = $request->input('poin');

            if ($jumlahPoin !== null && (float) $jumlahPoin > 0) {
                $nasabah = $transaksi->user;

                if ($nasabah) {
                    $nasabah->total_poin += (int) $jumlahPoin;
                    $nasabah->save();

                    DB::table('poin')->insert([
                        'nasabah_id' => $nasabah->id,
                        'jumlah_poin' => (int) $jumlahPoin,
                        'transaksi_id' => $transaksi->transaksi_id,
                        'tanggal_diberikan' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('admin.transaksi')->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
