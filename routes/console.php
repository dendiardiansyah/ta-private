<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('transaksi:normalize-status {--dry-run : Show what would change without updating the database}', function () {
    $dryRun = (bool) $this->option('dry-run');

    $rules = [
        [
            'to' => 'Menunggu Petugas',
            'from' => [
                'pending',
                'disetujui',
                'ditolak',
                'menunggu penjemputan',
                'menunggu petugas',
            ],
        ],
        [
            'to' => 'Menuju Lokasi',
            'from' => ['menuju lokasi'],
        ],
        [
            'to' => 'Sedang Diangkut',
            'from' => ['sedang diangkut'],
        ],
        [
            'to' => 'Selesai',
            'from' => ['selesai'],
        ],
    ];

    $this->info($dryRun ? 'Dry run: no updates will be applied.' : 'Normalizing transaksi.status values...');

    $totalAffected = 0;
    $totalUpdated = 0;

    // Handle NULL/empty statuses
    $nullQuery = DB::table('transaksi')->where(function ($q) {
        $q->whereNull('status')->orWhere('status', '');
    });
    $nullCount = (int) $nullQuery->count();
    if ($nullCount > 0) {
        $totalAffected += $nullCount;
        $this->line("- NULL/empty -> Menunggu Petugas: {$nullCount}");
        if (!$dryRun) {
            $totalUpdated += (int) $nullQuery->update(['status' => 'Menunggu Petugas']);
        }
    }

    foreach ($rules as $rule) {
        $from = $rule['from'];
        $to = $rule['to'];

        $placeholders = implode(',', array_fill(0, count($from), '?'));
        $query = DB::table('transaksi')
            ->whereRaw("LOWER(TRIM(status)) IN ({$placeholders})", $from)
            ->where('status', '!=', $to);
        $count = (int) $query->count();

        if ($count === 0) {
            continue;
        }

        $totalAffected += $count;
        $this->line('- ' . implode(', ', $from) . " -> {$to}: {$count}");

        if (!$dryRun) {
            $totalUpdated += (int) $query->update(['status' => $to]);
        }
    }

    if ($totalAffected === 0) {
        $this->info('No legacy statuses found. Nothing to normalize.');
        return;
    }

    if ($dryRun) {
        $this->info("Done (dry run). Would affect {$totalAffected} row(s). Run without --dry-run to apply.");
        return;
    }

    $this->info("Done. Updated {$totalUpdated} row(s) (matched {$totalAffected}).");
})->purpose('Normalize legacy transaksi.status values to current pickup workflow statuses');
