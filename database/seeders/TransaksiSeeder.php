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
        $adminRoleId = Role::query()->where('name', 'admin')->value('id');

        $nasabahIds = $userRoleId
            ? User::whereHas('roles', fn($q) => $q->where('roles.id', $userRoleId))->pluck('id')->all()
            : [];
        $jenisSampahIds = JenisSampah::pluck('jenis_sampah_id')->all();
        $adminIds = $adminRoleId
            ? User::whereHas('roles', fn($q) => $q->where('roles.id', $adminRoleId))->pluck('id')->all()
            : [];

        if (empty($nasabahIds) || empty($jenisSampahIds) || empty($adminIds)) {
            return;
        }

        for ($i = 0; $i < 25; $i++) {
            Transaksi::create([
                'nasabah_id' => $faker->randomElement($nasabahIds),
                'jenis_sampah_id' => $faker->randomElement($jenisSampahIds),
                'pelaku_usaha_id' => $faker->randomElement($adminIds),
                'alamat_penjemputan' => $faker->address,
                'jumlah' => $faker->numberBetween(1, 50),
                'tanggal_transaksi' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
                'status' => $faker->randomElement(['pending', 'disetujui', 'ditolak']),
            ]);
        }
    }
}
