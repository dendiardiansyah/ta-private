<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $now = now();
        $userRoleId = Role::query()->firstOrCreate(['name' => 'user'])->id;

        $demo = User::updateOrCreate(
            ['email' => 'nasabah@example.com'],
            [
                'name' => 'Nasabah Demo',
                'password' => Hash::make('password'),
                'total_poin' => 0,
                'email_verified_at' => now(),
            ]
        );

        DB::table('user_roles')->updateOrInsert(
            ['user_id' => $demo->id, 'role_id' => $userRoleId],
            ['created_at' => $now, 'updated_at' => $now]
        );

        for ($i = 1; $i <= 9; $i++) {
            $user = User::updateOrCreate(
                ['email' => "nasabah+{$i}@local.invalid"],
                [
                    'name' => $faker->name,
                    'password' => Hash::make('password'),
                    'total_poin' => 0,
                    'email_verified_at' => now(),
                ]
            );

            DB::table('user_roles')->updateOrInsert(
                ['user_id' => $user->id, 'role_id' => $userRoleId],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
