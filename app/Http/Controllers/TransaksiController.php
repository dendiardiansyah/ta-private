<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // Menampilkan form untuk membuat transaksi baru
    public function create()
    {
        // Cek apakah user sudah punya alamat
        $user = Auth::user();
        
        if (empty($user->alamat)) {
            return redirect()->route('profile.show')
                ->with('error', 'Mohon lengkapi alamat Anda terlebih dahulu sebelum mengajukan penjemputan.');
        }

        // Nasabah hanya perlu input tanggal, alamat auto-filled
        // Jenis sampah dan berat akan diinput oleh Petugas
        return view('user.penjemputan');
    }

    // Menyimpan transaksi baru ke database
    public function store(Request $request)
    {
        // Validasi data dari form - hanya tanggal_transaksi
        $request->validate([
            'tanggal_transaksi' => 'required|date',
        ]);

        // Auto-Assignment Logic: Petugas with least active transactions
        $petugas = \App\Models\User::whereHas('roles', function ($q) {
            $q->where('name', 'petugas');
        })->withCount([
                    'assignedTransaksi as active_tasks_count' => function ($q) {
                        $q->where('status', '!=', 'Selesai');
                    }
                ])->orderBy('active_tasks_count', 'asc')->first();

        // Gunakan alamat dari profil user
        $alamatPenjemputan = Auth::user()->alamat;

        // Membuat transaksi baru (tanpa jenis sampah dan berat - akan diinput oleh petugas)
        Transaksi::create([
            'nasabah_id' => Auth::id(),
            'petugas_id' => $petugas ? $petugas->id : null,
            'alamat_penjemputan' => $alamatPenjemputan,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'status' => 'Menunggu Petugas'
        ]);

        // Redirect setelah berhasil menyimpan transaksi
        session()->flash('message', 'Permintaan penjemputan berhasil dibuat!');

        // Redirect setelah berhasil menyimpan transaksi
        return redirect()->route('penjemputan.create');
    }

    // Menampilkan riwayat transaksi penjemputan
    public function history()
    {
        // Ambil data transaksi beserta detail dan poin yang terkait
        $transaksis = Transaksi::where('nasabah_id', Auth::id())
            ->with(['user', 'poin', 'details.jenisSampah'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        // Kirim data transaksi dan poin ke view
        return view('user.riwayat_penjemputan', compact('transaksis'));
    }




    // Menampilkan form untuk mengedit transaksi
    public function edit($transaksi_id)
    {
        // Ambil data transaksi yang akan diedit berdasarkan transaksi_id dan nasabah_id
        $transaksi = Transaksi::where('transaksi_id', $transaksi_id)
            ->where('nasabah_id', Auth::id())
            ->firstOrFail();

        if ($transaksi->status !== 'Menunggu Petugas' && $transaksi->status !== 'pending') {
            return redirect()->route('penjemputan.history')->with('error', 'Transaksi sedang/sudah diproses, tidak bisa diubah!');
        }

        // Nasabah hanya bisa edit tanggal, alamat auto-filled
        return view('user.edit_penjemputan', compact('transaksi'));
    }

    public function update(Request $request, $transaksi_id)
    {
        // Validasi inputan form - hanya tanggal_transaksi
        $request->validate([
            'tanggal_transaksi' => 'required|date',
        ]);

        // Ambil data transaksi untuk diperbarui
        $transaksi = Transaksi::where('transaksi_id', $transaksi_id)
            ->where('nasabah_id', Auth::id())
            ->firstOrFail();

        if ($transaksi->status !== 'Menunggu Petugas' && $transaksi->status !== 'pending') {
            return redirect()->route('penjemputan.history')->with('error', 'Transaksi sedang/sudah diproses, tidak bisa diubah!');
        }

        // Gunakan alamat dari profil user
        $alamatPenjemputan = Auth::user()->alamat;

        // Update data transaksi
        $transaksi->update([
            'alamat_penjemputan' => $alamatPenjemputan,
            'tanggal_transaksi' => $request->tanggal_transaksi,
        ]);

        // Redirect setelah berhasil
        return redirect()->route('penjemputan.history')->with('success', 'Penjemputan berhasil diperbarui!');
    }


    public function destroy($transaksi_id)
    {
        // Cari transaksi berdasarkan transaksi_id dan nasabah_id
        $transaksi = Transaksi::where('transaksi_id', $transaksi_id)
            ->where('nasabah_id', Auth::id())
            ->first();

        // Jika transaksi ditemukan, hapus
        if ($transaksi) {
            if ($transaksi->status !== 'Menunggu Petugas' && $transaksi->status !== 'pending') {
                return redirect()->route('penjemputan.history')->with('error', 'Transaksi sedang/sudah diproses, tidak bisa dibatalkan/dihapus!');
            }
            $transaksi->delete();
            return redirect()->route('penjemputan.history')->with('success', 'Penjemputan berhasil dihapus!');
        }

        // Jika transaksi tidak ditemukan, beri respon error
        return redirect()->route('penjemputan.history')->with('error', 'Penjemputan tidak ditemukan!');
    }
}
