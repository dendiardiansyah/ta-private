<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create("id_ID");

        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $users[] = [
                "name" => $faker->name,
                "email" => $faker->unique()->safeEmail,
                "password" => Hash::make("password"),
                "total_poin" => $faker->numberBetween(0, 5000),
                "email_verified_at" => now(),
                "created_at" => now(),
                "updated_at" => now(),
            ];
        }
        DB::table("users")->insert($users);

        $jenisSampah = [
            ["nama_jenis" => "Plastik", "deskripsi" => "Botol plastik.", "harga_sampah" => 2000, "gambar" => "plastik.jpg"],
            ["nama_jenis" => "Kertas", "deskripsi" => "Karton.", "harga_sampah" => 1500, "gambar" => "kertas.jpg"],
            ["nama_jenis" => "Kaca", "deskripsi" => "Botol kaca.", "harga_sampah" => 3000, "gambar" => "kaca.jpg"],
        ];
        DB::table("jenis_sampah")->insert($jenisSampah);

        $pelakuUsaha = [];
        for ($i = 0; $i < 3; $i++) {
            $pelakuUsaha[] = [
                "nama" => $faker->company,
                "password" => Hash::make("password"),
                "alamat" => $faker->address,
                "nomor_telepon" => $faker->phoneNumber,
                "created_at" => now(),
                "updated_at" => now(),
            ];
        }
        DB::table("pelaku_usaha")->insert($pelakuUsaha);

        $userIds = DB::table("users")->pluck("id")->toArray();
        $jenisSampahIds = DB::table("jenis_sampah")->pluck("jenis_sampah_id")->toArray();
        $pelakuUsahaIds = DB::table("pelaku_usaha")->pluck("pelaku_usaha_id")->toArray();

        for ($i = 0; $i < 10; $i++) {
            $transaksiId = DB::table("transaksi")->insertGetId([
                "nasabah_id" => $faker->randomElement($userIds),
                "jenis_sampah_id" => $faker->randomElement($jenisSampahIds),
                "pelaku_usaha_id" => $faker->randomElement($pelakuUsahaIds),
                "alamat_penjemputan" => $faker->address,
                "jumlah" => $faker->numberBetween(1, 100),
                "tanggal_transaksi" => $faker->date(),
                "status" => $faker->randomElement(["menunggu penjemputan", "selesai"]),
            ]);

            DB::table("poin")->insert([
                "nasabah_id" => $faker->randomElement($userIds),
                "transaksi_id" => $transaksiId,
                "jumlah_poin" => $faker->numberBetween(100, 1000),
                "tanggal_diberikan" => $faker->date(),
            ]);
        }

        for ($i = 0; $i < 5; $i++) {
            DB::table("penarikan_poin")->insert([
                "nasabah_id" => $faker->randomElement($userIds),
                "jumlah_poin" => $faker->numberBetween(500, 2000),
                "jumlah_uang" => $faker->numberBetween(50000, 200000),
                "status_penarikan" => $faker->randomElement(["Dalam Proses", "Selesai"]),
                "tanggal_penarikan" => $faker->date(),
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }
    }
}
