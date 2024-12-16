<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PelakuUsaha;
use App\Models\Transaksi;
use App\Models\JenisSampah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class PelakuUsahaController extends Controller
{
    public function showLoginForm()
    {
        return view('pelaku_usaha/login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('nama', 'password');

        if (Auth::guard('pelaku_usaha')->attempt($credentials)) {
            return redirect()->route('pelaku_usaha.dashboard');
        }

        return back()->withErrors(['message' => 'Login gagal, cek kembali nama atau password Anda.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('pelaku_usaha')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/pelaku-usaha/login');
    }

    public function showDashboard()
    {
        // Mengambil data transaksi, pastikan ada relasi 'user' di transaksi
        $transaksis = Transaksi::with(['user', 'jenisSampah'])->get();

        // Pastikan mengirimkan variabel ke view
        return view('dashboard_admin', compact('transaksis'));
        
    }

    public function showTransaksi()
    {
        // Ambil semua transaksi dengan relasi user dan jenisSampah
        $transaksis = Transaksi::with(['user', 'jenisSampah'])->get();

        return view('transaksi_pelaku_usaha', compact('transaksis'));
    }

    public function update(Request $request, $transaksi_id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
            'poin' => 'nullable|numeric|min:0', // Validasi poin, jika ada
        ]);

        // Cari transaksi berdasarkan ID
        $transaksi = Transaksi::findOrFail($transaksi_id);

        // Update status transaksi
        $transaksi->status = $request->status;
        $transaksi->save();

        // Jika status transaksi disetujui
        if ($request->status == 'disetujui') {
            // Ambil jumlah poin dari input form
            $jumlahPoin = $request->input('poin');

            // Pastikan nilai poin valid
            if ($jumlahPoin !== null && $jumlahPoin > 0) {
                // Ambil Nasabah yang terkait dengan transaksi
                $nasabah = $transaksi->user; // Asumsikan ada relasi 'user' di model Transaksi

                // Jika nasabah ditemukan, update total poin
                if ($nasabah) {
                    // Tambahkan poin yang baru ke total poin nasabah
                    $nasabah->total_poin += $jumlahPoin;
                    $nasabah->save();

                    // Simpan poin yang diberikan di tabel poin
                    DB::table('poin')->insert([
                        'nasabah_id' => $nasabah->id,
                        'jumlah_poin' => $jumlahPoin,
                        'transaksi_id' => $transaksi->transaksi_id,
                        'tanggal_diberikan' => now(),
                    ]);
                } else {
                    // Tangani kasus jika nasabah tidak ditemukan
                    session()->flash('status', 'Nasabah tidak ditemukan.');
                    return redirect()->route('pelaku_usaha.transaksi')
                        ->with('error', 'Nasabah tidak ditemukan.');
                }
            } else {
                // Tangani kasus jika poin tidak valid
                session()->flash('status', 'Jumlah poin tidak valid.');
                return redirect()->route('pelaku_usaha.transaksi')
                    ->with('error', 'Jumlah poin tidak valid.');
            }
        }

        // Mengarahkan kembali dengan pesan sukses
        session()->flash('status', 'Transaksi berhasil diperbarui!');
        return redirect()->route('pelaku_usaha.transaksi')
            ->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function showKatalog()
    {
        $jenisSampahs = JenisSampah::all(); // Mengambil semua data jenis sampah

        return view('katalog', compact('jenisSampahs'));
    }

    public function createKatalog()
    {
        return view('tambah_katalog');
    }

    public function addKatalog(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_sampah' => 'required|numeric',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        // Simpan gambar jika ada
        if ($request->hasFile('gambar')) {
            $validatedData['gambar'] = $request->file('gambar')->store('image', 'public');
        }

        // Simpan data ke database
        JenisSampah::create($validatedData);

        // Redirect ke halaman katalog dengan pesan sukses
        return redirect()->route('pelaku_usaha.katalog')->with('success', 'Katalog berhasil ditambahkan!');
    }



    public function index()
    {
        $jenisSampahs = JenisSampah::all(); // Ambil semua jenis sampah
        return view('katalog_admin', compact('jenisSampahs')); // Menampilkan daftar jenis sampah
    }
    public function editKatalog($jenis_sampah_id)
    {
        $jenisSampah = JenisSampah::findOrFail($jenis_sampah_id); // Ambil jenis sampah berdasarkan ID
        return view('edit_katalog', compact('jenisSampah')); // Menampilkan form edit
    }

    // Fungsi untuk mengupdate data katalog
    public function updateKatalog(Request $request, $jenis_sampah_id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_sampah' => 'required|numeric',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        // Untuk memastikan harga ada di dalam $validatedData


        // Cari jenis sampah berdasarkan ID
        $jenisSampah = JenisSampah::findOrFail($jenis_sampah_id);

        // Jika ada file gambar baru, simpan dan update
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($jenisSampah->gambar) {
                Storage::disk('public')->delete($jenisSampah->gambar);
            }

            // Simpan gambar baru
            $validatedData['gambar'] = $request->file('gambar')->store('image', 'public');
        }

        // Update data jenis sampah

        $jenisSampah->update($validatedData);


        // Redirect ke halaman katalog dengan pesan sukses
        return redirect()->route('pelaku_usaha.katalog')->with('success', 'Katalog berhasil diperbarui!');
    }



    public function deleteKatalog($id)
    {
        $jenisSampah = JenisSampah::findOrFail($id);

        $jenisSampah->delete();
        return redirect()->route('pelaku_usaha.katalog')->with('success', 'Jenis sampah berhasil dihapus!');
    }
}
