<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelakuUsaha extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pelaku_usaha')->insert([
            'nama' => 'agus',
            'password' => bcrypt('password123'),
            'alamat' => 'Jl. Duku',
            'nomor_telepon' => '081234567890',
        ]);
    }
}
