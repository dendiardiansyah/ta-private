<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenarikanPoin;
use Illuminate\Http\Request;

class AdminPenarikanPoinController extends Controller
{
    public function index()
    {
        $penarikanPoin = PenarikanPoin::with('nasabah')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.penarikan-poin.index', compact('penarikanPoin'));
    }

    public function approve($id)
    {
        $penarikan = PenarikanPoin::findOrFail($id);

        if ($penarikan->status_penarikan === 'diproses') {
            $penarikan->status_penarikan = 'selesai';
            $penarikan->save();
            return redirect()->route('admin.penarikan-poin.index')->with('success', 'Penarikan poin berhasil disetujui.');
        }

        return redirect()->route('admin.penarikan-poin.index')->with('error', 'Penarikan poin tidak dapat disetujui.');
    }

    public function reject($id)
    {
        $penarikan = PenarikanPoin::findOrFail($id);

        if ($penarikan->status_penarikan === 'diproses') {
            // Restore user's points
            $user = $penarikan->nasabah;
            $user->total_poin += $penarikan->jumlah_poin;
            $user->save();

            $penarikan->status_penarikan = 'ditolak';
            $penarikan->save();
            return redirect()->route('admin.penarikan-poin.index')->with('success', 'Penarikan poin berhasil ditolak.');
        }

        return redirect()->route('admin.penarikan-poin.index')->with('error', 'Penarikan poin tidak dapat ditolak.');
    }
}
