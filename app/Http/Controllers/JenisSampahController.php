<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;

class TransaksiController extends Controller
{
    public function create()
    {
        // Mengambil semua data jenis sampah
        $jenisSampah = JenisSampah::all();

        // Menyimpan data ke session
        session()->flash('jenisSampah', $jenisSampah);

        // Redirect ke halaman form penjemputan
        return redirect()->route('penjemputan.create');
    }
}
