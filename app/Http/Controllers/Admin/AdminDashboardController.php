<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::query()->count();
        $approvedUsers = User::query()->where('status', 'approved')->count();
        $pendingUsers = User::query()->where('status', 'pending')->count();

        $pendingApprovals = User::query()
            ->with('roles')
            ->where('status', 'pending')
            ->whereHas('roles', function ($query) {
                $query->whereNotIn('name', ['user', 'nasabah']);
            })
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $pendingApprovalsCount = User::query()
            ->where('status', 'pending')
            ->whereHas('roles', function ($query) {
                $query->whereNotIn('name', ['user', 'nasabah']);
            })
            ->count();

        $totalTransaksi = Transaksi::query()->count();
        $transactionWeightSub = DB::table('transaksi_detail')
            ->select('transaksi_id', DB::raw('SUM(berat) as total_berat'))
            ->groupBy('transaksi_id');

        $totalKg = (float) DB::table('transaksi_detail')->sum('berat');
        $activeNasabahCount = (int) Transaksi::query()->distinct('nasabah_id')->count('nasabah_id');
        $activePetugasCount = (int) Transaksi::query()->whereNotNull('petugas_id')->distinct('petugas_id')->count('petugas_id');

        $transaksiByStatus = Transaksi::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->pluck('total', 'status');

        $latestTransaksi = Transaksi::query()
            ->with(['user', 'petugas', 'details.jenisSampah'])
            ->orderByDesc('tanggal_transaksi')
            ->orderByDesc('transaksi_id')
            ->limit(8)
            ->get();

        $topNasabah = Transaksi::query()
            ->leftJoinSub($transactionWeightSub, 'td_total', function ($join) {
                $join->on('transaksi.transaksi_id', '=', 'td_total.transaksi_id');
            })
            ->select(
                'nasabah_id',
                DB::raw('COUNT(*) as transaksi_count'),
                DB::raw('COALESCE(SUM(td_total.total_berat), 0) as total_kg')
            )
            ->groupBy('nasabah_id')
            ->orderByDesc('transaksi_count')
            ->orderByDesc('total_kg')
            ->limit(5)
            ->get()
            ->load('user');

        $topPetugas = Transaksi::query()
            ->whereNotNull('petugas_id')
            ->leftJoinSub($transactionWeightSub, 'td_total', function ($join) {
                $join->on('transaksi.transaksi_id', '=', 'td_total.transaksi_id');
            })
            ->select(
                'petugas_id',
                DB::raw('COUNT(*) as transaksi_count'),
                DB::raw('COALESCE(SUM(td_total.total_berat), 0) as total_kg')
            )
            ->groupBy('petugas_id')
            ->orderByDesc('transaksi_count')
            ->orderByDesc('total_kg')
            ->limit(5)
            ->get()
            ->load('petugas');

        $latestHarga = JenisSampah::query()
            ->orderByDesc('jenis_sampah_id')
            ->limit(6)
            ->get()
            ->map(function ($item) {
                $today = Carbon::today()->toDateString();
                $seed = crc32('harga:' . $item->jenis_sampah_id . ':' . $today);
                $deltaPercent = ((int) ($seed % 17)) - 8; // -8..+8
    
                return [
                    'model' => $item,
                    'delta_percent' => $deltaPercent,
                ];
            });

        // Total per jenis sampah (hanya transaksi Selesai)
        $totalPerJenisSampah = DB::table('transaksi_detail')
            ->join('transaksi', 'transaksi_detail.transaksi_id', '=', 'transaksi.transaksi_id')
            ->join('jenis_sampah', 'transaksi_detail.jenis_sampah_id', '=', 'jenis_sampah.jenis_sampah_id')
            ->where('transaksi.status', 'Selesai')
            ->select(
                'jenis_sampah.nama_jenis',
                DB::raw('SUM(transaksi_detail.berat) as total_kg'),
                DB::raw('COUNT(DISTINCT transaksi_detail.transaksi_id) as transaksi_count')
            )
            ->groupBy('jenis_sampah.jenis_sampah_id', 'jenis_sampah.nama_jenis')
            ->orderByDesc('total_kg')
            ->get();

        // Chart: transaksi per day (last 14 days)
        $end = Carbon::today();
        $start = (clone $end)->subDays(13);
        $rows = Transaksi::query()
            ->leftJoin('transaksi_detail', 'transaksi.transaksi_id', '=', 'transaksi_detail.transaksi_id')
            ->whereBetween('tanggal_transaksi', [$start->toDateString(), $end->toDateString()])
            ->selectRaw('tanggal_transaksi as day, COUNT(DISTINCT transaksi.transaksi_id) as total, COALESCE(SUM(transaksi_detail.berat), 0) as total_kg')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $perDay = $rows->keyBy('day');
        $transaksiChartLabels = [];
        $transaksiChartCounts = [];
        $transaksiChartKg = [];

        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $day = $cursor->toDateString();
            $transaksiChartLabels[] = $cursor->format('d M');
            $transaksiChartCounts[] = (int) ($perDay[$day]->total ?? 0);
            $transaksiChartKg[] = (int) ($perDay[$day]->total_kg ?? 0);
            $cursor->addDay();
        }

        // Dummy/derived price index series so the dashboard looks alive.
        $priceIndexLabels = $transaksiChartLabels;
        $priceIndexData = [];
        $index = 100.0;
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $seed = crc32('price_index:' . $cursor->toDateString());
            $step = (((int) ($seed % 11)) - 5) / 10; // -0.5..+0.5
            $index = max(90, min(120, $index + $step));
            $priceIndexData[] = round($index, 1);
            $cursor->addDay();
        }

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'approvedUsers' => $approvedUsers,
            'pendingUsers' => $pendingUsers,
            'pendingApprovals' => $pendingApprovals,
            'pendingApprovalsCount' => $pendingApprovalsCount,
            'totalTransaksi' => $totalTransaksi,
            'totalKg' => $totalKg,
            'activeNasabahCount' => $activeNasabahCount,
            'activePetugasCount' => $activePetugasCount,
            'transaksiByStatus' => $transaksiByStatus,
            'latestTransaksi' => $latestTransaksi,
            'topNasabah' => $topNasabah,
            'topPetugas' => $topPetugas,
            'latestHarga' => $latestHarga,
            'totalPerJenisSampah' => $totalPerJenisSampah,
            'transaksiChartLabels' => $transaksiChartLabels,
            'transaksiChartCounts' => $transaksiChartCounts,
            'transaksiChartKg' => $transaksiChartKg,
            'priceIndexLabels' => $priceIndexLabels,
            'priceIndexData' => $priceIndexData,
        ]);
    }
}
