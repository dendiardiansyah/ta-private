<?php

namespace Database\Seeders;

use App\Models\PenarikanPoin;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PenarikanPoinSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $users = User::where('total_poin', '>=', 100)->get();

        foreach ($users as $user) {
            $remainingPoin = (int) $user->total_poin;
            $totalPenarikan = $faker->numberBetween(1, 2);

            for ($i = 0; $i < $totalPenarikan; $i++) {
                if ($remainingPoin < 100) {
                    break;
                }

                $maxPoin = min($remainingPoin, 2000);
                $jumlahPoin = $faker->numberBetween(100, $maxPoin);

                PenarikanPoin::create([
                    'nasabah_id' => $user->id,
                    'jumlah_poin' => $jumlahPoin,
                    'jumlah_uang' => $jumlahPoin * 100,
                    'status_penarikan' => $faker->randomElement(['diproses', 'selesai']),
                    'tanggal_penarikan' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                ]);

                $remainingPoin -= $jumlahPoin;
            }

            User::where('id', $user->id)->update(['total_poin' => $remainingPoin]);
        }
    }
}
