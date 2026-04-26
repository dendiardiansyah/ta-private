<?php

namespace Database\Seeders;

use App\Models\Poin;
use App\Models\Transaksi;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoinSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $transaksis = Transaksi::all();

        if ($transaksis->isEmpty()) {
            return;
        }

        foreach ($transaksis as $transaksi) {
            Poin::create([
                'nasabah_id' => $transaksi->nasabah_id,
                'transaksi_id' => $transaksi->transaksi_id,
                'jumlah_poin' => $faker->numberBetween(100, 2000),
                'tanggal_diberikan' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
            ]);
        }

        User::query()->update(['total_poin' => 0]);

        $totalPerUser = Poin::query()
            ->select('nasabah_id', DB::raw('SUM(jumlah_poin) as total_poin'))
            ->groupBy('nasabah_id')
            ->pluck('total_poin', 'nasabah_id');

        foreach ($totalPerUser as $nasabahId => $totalPoin) {
            User::where('id', $nasabahId)->update(['total_poin' => $totalPoin]);
        }
    }
}
