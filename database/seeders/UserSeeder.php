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
                'role' => 'user',
                'password' => Hash::make('password'),
                'total_poin' => 0,
                'email_verified_at' => now(),
            ]
        );

        for ($i = 1; $i <= 9; $i++) {
            User::updateOrCreate(
                ['email' => "nasabah+{$i}@local.invalid"],
                [
                    'name' => $faker->name,
                    'role' => 'user',
                    'password' => Hash::make('password'),
                    'total_poin' => 0,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
