<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $now = now();
        $adminRoleId = Role::query()->firstOrCreate(['name' => 'admin'])->id;

        for ($i = 1; $i <= 3; $i++) {
            $user = User::updateOrCreate(
                ['email' => "admin+{$i}@local.invalid"],
                [
                    'name' => $faker->company,
                    'password' => Hash::make('password'),
                    'alamat' => $faker->address,
                    'nomor_telepon' => $faker->phoneNumber,
                    'email_verified_at' => $now,
                ]
            );

            DB::table('user_roles')->updateOrInsert(
                ['user_id' => $user->id, 'role_id' => $adminRoleId],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
