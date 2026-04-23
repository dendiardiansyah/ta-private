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
        $transaksis = Transaksi::with(['user', 'jenisSampah'])->paginate(10);
        return view('dashboard_admin', compact('transaksis'));
    }

    public function showTransaksi()
    {
        $transaksis = Transaksi::with(['user', 'jenisSampah'])->get();
        return view('transaksi_pelaku_usaha', compact('transaksis'));
    }

    public function update(Request $request, $transaksi_id)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
            'poin' => 'nullable|numeric|min:0',
        ]);

        $transaksi = Transaksi::findOrFail($transaksi_id);
        $transaksi->status = $request->status;
        $transaksi->save();

        if ($request->status == 'disetujui') {
            $jumlahPoin = $request->input('poin');

            if ($jumlahPoin !== null && $jumlahPoin > 0) {
                $nasabah = $transaksi->user;

                if ($nasabah) {
                    $nasabah->total_poin += $jumlahPoin;
                    $nasabah->save();

                    DB::table('poin')->insert([
                        'nasabah_id' => $nasabah->id,
                        'jumlah_poin' => $jumlahPoin,
                        'transaksi_id' => $transaksi->transaksi_id,
                        'tanggal_diberikan' => now(),
                    ]);
                } else {
                    session()->flash('status', 'Nasabah tidak ditemukan.');
                    return redirect()->route('pelaku_usaha.transaksi')
                        ->with('error', 'Nasabah tidak ditemukan.');
                }
            } else {
                session()->flash('status', 'Jumlah poin tidak valid.');
                return redirect()->route('pelaku_usaha.transaksi')
                    ->with('error', 'Jumlah poin tidak valid.');
            }
        }

        session()->flash('status', 'Transaksi berhasil diperbarui!');
        return redirect()->route('pelaku_usaha.transaksi')
            ->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function showKatalog()
    {
        $jenisSampahs = JenisSampah::all();
        return view('katalog', compact('jenisSampahs'));
    }

    public function createKatalog()
    {
        return view('tambah_katalog');
    }

    public function addKatalog(Request $request)
    {
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_sampah' => 'required|numeric',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if ($request->hasFile('gambar')) {
            $validatedData['gambar'] = $request->file('gambar')->store('image', 'public');
        }

        JenisSampah::create($validatedData);
        return redirect()->route('pelaku_usaha.katalog')->with('success', 'Katalog berhasil ditambahkan!');
    }

    public function index()
    {
        $jenisSampahs = JenisSampah::all();
        return view('katalog_admin', compact('jenisSampahs'));
    }

    public function editKatalog($jenis_sampah_id)
    {
        $jenisSampah = JenisSampah::findOrFail($jenis_sampah_id);
        return view('edit_katalog', compact('jenisSampah'));
    }

    public function updateKatalog(Request $request, $jenis_sampah_id)
    {
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_sampah' => 'required|numeric',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $jenisSampah = JenisSampah::findOrFail($jenis_sampah_id);

        if ($request->hasFile('gambar')) {
            if ($jenisSampah->gambar) {
                Storage::disk('public')->delete($jenisSampah->gambar);
            }

            $validatedData['gambar'] = $request->file('gambar')->store('image', 'public');
        }

        $jenisSampah->update($validatedData);
        return redirect()->route('pelaku_usaha.katalog')->with('success', 'Katalog berhasil diperbarui!');
    }

    public function deleteKatalog($id)
    {
        $jenisSampah = JenisSampah::findOrFail($id);
        $jenisSampah->delete();
        return redirect()->route('pelaku_usaha.katalog')->with('success', 'Jenis sampah berhasil dihapus!');
    }
}
