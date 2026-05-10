<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use App\Models\Transaksi;  // Model untuk transaksi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // Menampilkan form untuk membuat transaksi baru
    public function create()
    {
        // Mengambil data jenis sampah
        $jenisSampah = JenisSampah::all();


        // dd($jenisSampah);

        // Mengirim data jenis sampah ke view
        return view('user.penjemputan', compact('jenisSampah'));
    }

    // Menyimpan transaksi baru ke database
    public function store(Request $request)
    {
        // Validasi data dari form
        $request->validate([
            'jenis_sampah_id' => 'required|exists:jenis_sampah,jenis_sampah_id', // Validasi jenis_sampah_id dengan primary key
            'alamat_penjemputan' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
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

        // Membuat transaksi baru
        Transaksi::create([
            'nasabah_id' => Auth::id(),
            'jenis_sampah_id' => $request->jenis_sampah_id, // Menyimpan jenis sampah yang dipilih
            'petugas_id' => $petugas ? $petugas->id : null,
            'alamat_penjemputan' => $request->alamat_penjemputan,
            'jumlah' => $request->jumlah,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'status' => 'Menunggu Petugas'
        ]);

        // Redirect setelah berhasil menyimpan transaksi
        session()->flash('message', 'Transaksi berhasil dibuat!');

        // Redirect setelah berhasil menyimpan transaksi
        return redirect()->route('penjemputan.create');
    }

    // Menampilkan riwayat transaksi penjemputan
    // Controller TransaksiController
    public function history()
    {
        // Ambil data transaksi beserta poin yang terkait
        $transaksis = Transaksi::where('nasabah_id', Auth::id())
            ->with('user', 'poin') // Menambahkan relasi poin
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

        // Ambil semua data jenis sampah
        $jenisSampah = JenisSampah::all();

        // Tampilkan tampilan form edit dengan data transaksi dan jenis sampah
        return view('user.edit_penjemputan', compact('transaksi', 'jenisSampah'));
    }

    public function update(Request $request, $transaksi_id)
    {
        // Validasi inputan form
        $request->validate([
            'jenis_sampah_id' => 'required|exists:jenis_sampah,jenis_sampah_id',
            'alamat_penjemputan' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
            'tanggal_transaksi' => 'required|date',
        ]);

        // Ambil data transaksi untuk diperbarui
        $transaksi = Transaksi::where('transaksi_id', $transaksi_id)
            ->where('nasabah_id', Auth::id())
            ->firstOrFail();

        if ($transaksi->status !== 'Menunggu Petugas' && $transaksi->status !== 'pending') {
            return redirect()->route('penjemputan.history')->with('error', 'Transaksi sedang/sudah diproses, tidak bisa diubah!');
        }

        // Update data transaksi
        $transaksi->update([
            'jenis_sampah_id' => $request->jenis_sampah_id,
            'alamat_penjemputan' => $request->alamat_penjemputan,
            'jumlah' => $request->jumlah,
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
