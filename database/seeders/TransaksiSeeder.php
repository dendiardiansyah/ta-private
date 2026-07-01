<?php

namespace Database\Seeders;

use App\Models\JenisSampah;
use App\Models\Role;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $userRoleId = Role::query()->where('name', 'user')->value('id');
        $petugasRoleId = Role::query()->where('name', 'petugas')->value('id');

        $nasabahIds = $userRoleId
            ? User::whereHas('roles', fn($q) => $q->where('roles.id', $userRoleId))->pluck('id')->all()
            : [];
        $jenisSampahList = JenisSampah::all();
        $petugasIds = $petugasRoleId
            ? User::whereHas('roles', fn($q) => $q->where('roles.id', $petugasRoleId))->pluck('id')->all()
            : [];

        if (empty($nasabahIds) || $jenisSampahList->isEmpty()) {
            return;
        }

        // Weighted-ish distribution so seeded dashboards look realistic
        $statusPool = [
            'Menunggu Petugas',
            'Menunggu Petugas',
            'Menunggu Petugas',
            'Menunggu Petugas',
            'Menuju Lokasi',
            'Menuju Lokasi',
            'Sedang Diangkut',
            'Sedang Diangkut',
            'Selesai',
            'Selesai',
            'Selesai',
            'Selesai',
            'Selesai',
        ];

        for ($i = 0; $i < 30; $i++) {
            $status = $faker->randomElement($statusPool);

            // Create transaksi WITHOUT jenis_sampah_id and jumlah (moved to detail)
            $transaksi = Transaksi::create([
                'nasabah_id' => $faker->randomElement($nasabahIds),
                'petugas_id' => empty($petugasIds) ? null : $faker->randomElement($petugasIds),
                'alamat_penjemputan' => $faker->address,
                'tanggal_transaksi' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
                'status' => $status,
            ]);

            // For non-Menunggu Petugas status, create transaksi_detail (petugas has input the details)
            if ($status !== 'Menunggu Petugas') {
                // Random 1-3 jenis sampah per transaksi
                $numItems = $faker->numberBetween(1, 3);
                $selectedJenis = $jenisSampahList->random(min($numItems, $jenisSampahList->count()));

                foreach ($selectedJenis as $jenis) {
                    TransaksiDetail::create([
                        'transaksi_id' => $transaksi->transaksi_id,
                        'jenis_sampah_id' => $jenis->jenis_sampah_id,
                        'berat' => $faker->randomFloat(2, 0.5, 25), // 0.5 to 25 kg with 2 decimals
                    ]);
                }
            }
            // For "Menunggu Petugas", no details yet (petugas hasn't arrived)
        }
    }
}
