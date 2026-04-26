<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            JenisSampahSeeder::class,
            PelakuUsahaSeeder::class,
            TransaksiSeeder::class,
            PoinSeeder::class,
            PenarikanPoinSeeder::class,
        ]);
    }
}
