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

        $roles = [
            'user' => ['email' => 'nasabah@example.com', 'name' => 'Nasabah Demo'],
            'admin' => ['email' => 'admin@example.com', 'name' => 'Admin Demo'],
            'petugas' => ['email' => 'petugas@example.com', 'name' => 'Petugas Demo'],
            'pelaku_usaha' => ['email' => 'pelaku_usaha@example.com', 'name' => 'Pelaku Usaha Demo'],
        ];

        $userRoleId = null;

        foreach ($roles as $roleName => $data) {
            $roleId = Role::query()->firstOrCreate(['name' => $roleName])->id;

            if ($roleName === 'user') {
                $userRoleId = $roleId;
            }

            $demo = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'total_poin' => 0,
                    'email_verified_at' => now(),
                ]
            );

            DB::table('user_roles')->updateOrInsert(
                ['user_id' => $demo->id, 'role_id' => $roleId],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }

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
