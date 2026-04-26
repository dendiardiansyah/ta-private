<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PelakuUsahaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "pelaku_usaha+{$i}@local.invalid"],
                [
                    'name' => $faker->company,
                    'role' => 'pelaku_usaha',
                    'password' => Hash::make('password'),
                    'alamat' => $faker->address,
                    'nomor_telepon' => $faker->phoneNumber,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
