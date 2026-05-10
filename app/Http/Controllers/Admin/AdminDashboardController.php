<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['user', 'jenisSampah'])
            ->where('status', 'pending')
            ->orderByDesc('transaksi_id')
            ->paginate(10);

        return view('admin.dashboard', compact('transaksis'));
    }
}
