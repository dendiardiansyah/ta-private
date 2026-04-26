<?php

namespace Database\Seeders;

use App\Models\JenisSampah;
use App\Models\PelakuUsaha;
use App\Models\Transaksi;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $nasabahIds = User::pluck('id')->all();
        $jenisSampahIds = JenisSampah::pluck('jenis_sampah_id')->all();
        $pelakuUsahaIds = PelakuUsaha::pluck('pelaku_usaha_id')->all();

        if (empty($nasabahIds) || empty($jenisSampahIds) || empty($pelakuUsahaIds)) {
            return;
        }

        for ($i = 0; $i < 25; $i++) {
            Transaksi::create([
                'nasabah_id' => $faker->randomElement($nasabahIds),
                'jenis_sampah_id' => $faker->randomElement($jenisSampahIds),
                'pelaku_usaha_id' => $faker->randomElement($pelakuUsahaIds),
                'alamat_penjemputan' => $faker->address,
                'jumlah' => $faker->numberBetween(1, 50),
                'tanggal_transaksi' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
                'status' => $faker->randomElement(['pending', 'disetujui', 'ditolak']),
            ]);
        }
    }
}
