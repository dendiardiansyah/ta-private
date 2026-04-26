<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        User::updateOrCreate(
            ['email' => 'nasabah@example.com'],
            [
                'name' => 'Nasabah Demo',
                'password' => Hash::make('password'),
                'total_poin' => 0,
                'email_verified_at' => now(),
            ]
        );

        for ($i = 0; $i < 9; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'total_poin' => 0,
                'email_verified_at' => now(),
            ]);
        }
    }
}
