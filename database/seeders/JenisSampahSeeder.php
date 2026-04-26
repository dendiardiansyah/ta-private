<?php

namespace Database\Seeders;

use App\Models\JenisSampah;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class JenisSampahSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $jenisList = [
            'Plastik',
            'Kertas',
            'Kaca',
            'Logam',
            'Kardus',
            'Elektronik',
            'Botol',
            'Kaleng',
        ];

        foreach ($jenisList as $jenis) {
            JenisSampah::create([
                'nama_jenis' => $jenis,
                'deskripsi' => $faker->sentence(10),
                'harga_sampah' => $faker->numberBetween(1000, 7000),
                'gambar' => $faker->optional(0.4)->randomElement(['plastik.jpg', 'kertas.jpg', 'kaca.jpg']),
            ]);
        }
    }
}
