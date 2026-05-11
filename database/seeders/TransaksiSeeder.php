<?php

namespace Database\Seeders;

use App\Models\JenisSampah;
use App\Models\Role;
use App\Models\Transaksi;
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
        $jenisSampahIds = JenisSampah::pluck('jenis_sampah_id')->all();
        $petugasIds = $petugasRoleId
            ? User::whereHas('roles', fn($q) => $q->where('roles.id', $petugasRoleId))->pluck('id')->all()
            : [];

        if (empty($nasabahIds) || empty($jenisSampahIds)) {
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
        ];

        for ($i = 0; $i < 25; $i++) {
            $status = $faker->randomElement($statusPool);

            Transaksi::create([
                'nasabah_id' => $faker->randomElement($nasabahIds),
                'jenis_sampah_id' => $faker->randomElement($jenisSampahIds),
                'petugas_id' => empty($petugasIds) ? null : $faker->randomElement($petugasIds),
                'alamat_penjemputan' => $faker->address,
                'jumlah' => $faker->numberBetween(1, 50),
                'tanggal_transaksi' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
                'status' => $status,
            ]);
        }
    }
}
