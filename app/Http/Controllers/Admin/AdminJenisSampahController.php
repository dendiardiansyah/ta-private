<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminJenisSampahController extends Controller
{
    /**
     * Display a listing of all Jenis Sampah
     */
    public function index()
    {
        $jenisSampahs = JenisSampah::all();

        return view('admin.jenis-sampah.index', compact('jenisSampahs'));
    }

    /**
     * Show the form for creating a new Jenis Sampah
     */
    public function create()
    {
        return view('admin.jenis-sampah.create');
    }

    /**
     * Store a newly created Jenis Sampah in database
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_sampahs',
            'deskripsi' => 'nullable|string',
            'harga_sampah' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if ($request->hasFile('gambar')) {
            $validatedData['gambar'] = $request->file('gambar')->store('jenis-sampah', 'public');
        }

        JenisSampah::create($validatedData);

        return redirect()->route('admin.jenis-sampah.index')
            ->with('success', 'Jenis Sampah berhasil ditambahkan!');
    }

    /**
     * Show the form for editing a Jenis Sampah
     */
    public function edit(JenisSampah $jenisSampah)
    {
        return view('admin.jenis-sampah.edit', compact('jenisSampah'));
    }

    /**
     * Update a Jenis Sampah in the database
     */
    public function update(Request $request, JenisSampah $jenisSampah)
    {
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_sampahs,nama_jenis,' . $jenisSampah->id,
            'deskripsi' => 'nullable|string',
            'harga_sampah' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($jenisSampah->gambar) {
                Storage::disk('public')->delete($jenisSampah->gambar);
            }

            $validatedData['gambar'] = $request->file('gambar')->store('jenis-sampah', 'public');
        }

        $jenisSampah->update($validatedData);

        return redirect()->route('admin.jenis-sampah.index')
            ->with('success', 'Jenis Sampah berhasil diperbarui!');
    }

    /**
     * Delete a Jenis Sampah from the database
     */
    public function destroy(JenisSampah $jenisSampah)
    {
        // Delete image if exists
        if ($jenisSampah->gambar) {
            Storage::disk('public')->delete($jenisSampah->gambar);
        }

        $jenisSampah->delete();

        return redirect()->route('admin.jenis-sampah.index')
            ->with('success', 'Jenis Sampah berhasil dihapus!');
    }
}
