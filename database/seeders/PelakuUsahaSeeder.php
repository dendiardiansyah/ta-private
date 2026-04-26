<?php

namespace Database\Seeders;

use App\Models\PelakuUsaha;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PelakuUsahaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 5; $i++) {
            PelakuUsaha::create([
                'nama' => $faker->company,
                'password' => 'password',
                'alamat' => $faker->address,
                'nomor_telepon' => $faker->phoneNumber,
            ]);
        }
    }
}
