<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminKatalogController extends Controller
{
    public function index()
    {
        $jenisSampahs = JenisSampah::all();

        return view('katalog_admin', compact('jenisSampahs'));
    }

    public function create()
    {
        return view('tambah_katalog');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_sampah' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if ($request->hasFile('gambar')) {
            $validatedData['gambar'] = $request->file('gambar')->store('image', 'public');
        }

        JenisSampah::create($validatedData);

        return redirect()->route('admin.katalog')->with('success', 'Katalog berhasil ditambahkan!');
    }

    public function edit(int $jenis_sampah_id)
    {
        $jenisSampah = JenisSampah::findOrFail($jenis_sampah_id);

        return view('edit_katalog', compact('jenisSampah'));
    }

    public function update(Request $request, int $jenis_sampah_id)
    {
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_sampah' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $jenisSampah = JenisSampah::findOrFail($jenis_sampah_id);

        if ($request->hasFile('gambar')) {
            if ($jenisSampah->gambar) {
                Storage::disk('public')->delete($jenisSampah->gambar);
            }

            $validatedData['gambar'] = $request->file('gambar')->store('image', 'public');
        }

        $jenisSampah->update($validatedData);

        return redirect()->route('admin.katalog')->with('success', 'Katalog berhasil diperbarui!');
    }

    public function destroy(int $jenis_sampah_id)
    {
        $jenisSampah = JenisSampah::findOrFail($jenis_sampah_id);

        if ($jenisSampah->gambar) {
            Storage::disk('public')->delete($jenisSampah->gambar);
        }

        $jenisSampah->delete();

        return redirect()->route('admin.katalog')->with('success', 'Jenis sampah berhasil dihapus!');
    }
}
