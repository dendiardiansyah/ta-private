<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\User;

class PelakuUsahaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $now = now();
        $pelakuRoleId = Role::query()->firstOrCreate(['name' => 'pelaku_usaha'])->id;

        for ($i = 1; $i <= 5; $i++) {
            $user = User::updateOrCreate(
                ['email' => "pelaku_usaha+{$i}@local.invalid"],
                [
                    'name' => $faker->company,
                    'password' => Hash::make('password'),
                    'alamat' => $faker->address,
                    'nomor_telepon' => $faker->phoneNumber,
                    'email_verified_at' => now(),
                ]
            );

            DB::table('user_roles')->updateOrInsert(
                ['user_id' => $user->id, 'role_id' => $pelakuRoleId],
                ['created_at' => $now, 'updated_at' => $now]
            );

            DB::table('pelaku_usaha_profiles')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'nama_usaha' => $user->name,
                    'alamat' => $user->alamat,
                    'nomor_telepon' => $user->nomor_telepon,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}
