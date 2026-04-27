<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('pelaku_usaha_profiles')) {
            Schema::create('pelaku_usaha_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

                // Business-specific / niche data
                $table->string('nama_usaha')->nullable();
                $table->text('alamat')->nullable();
                $table->string('nomor_telepon')->nullable();

                // For traceability when migrating from legacy pelaku_usaha table
                $table->unsignedBigInteger('legacy_pelaku_usaha_id')->nullable()->unique();

                $table->timestamps();
            });
        }

        // Backfill profiles for users who already have pelaku_usaha role.
        if (Schema::hasTable('roles') && Schema::hasTable('user_roles')) {
            $pelakuRoleId = DB::table('roles')->where('name', 'pelaku_usaha')->value('id');

            if ($pelakuRoleId) {
                $now = now();

                $userIds = DB::table('user_roles')
                    ->where('role_id', $pelakuRoleId)
                    ->pluck('user_id');

                if ($userIds->isNotEmpty()) {
                    // Insert missing profiles, copying the current user fields as initial business profile.
                    foreach ($userIds->unique()->chunk(500) as $chunk) {
                        $users = DB::table('users')
                            ->whereIn('id', $chunk->all())
                            ->get(['id', 'name', 'alamat', 'nomor_telepon']);

                        $rows = [];
                        foreach ($users as $user) {
                            $rows[] = [
                                'user_id' => $user->id,
                                'nama_usaha' => $user->name,
                                'alamat' => $user->alamat,
                                'nomor_telepon' => $user->nomor_telepon,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }

                        DB::table('pelaku_usaha_profiles')->insertOrIgnore($rows);
                    }
                }
            }
        }

        // If legacy table still exists (fresh migration on older schema), migrate its niche fields into profiles.
        if (Schema::hasTable('pelaku_usaha')) {
            $now = now();

            $legacyRows = DB::table('pelaku_usaha')->get();

            foreach ($legacyRows as $legacy) {
                $email = 'pelaku_usaha_' . $legacy->pelaku_usaha_id . '@local.invalid';

                $user = DB::table('users')->where('email', $email)->first();
                if (!$user) {
                    $userId = DB::table('users')->insertGetId([
                        'name' => $legacy->nama,
                        'email' => $email,
                        'password' => $legacy->password,
                        'created_at' => $legacy->created_at ?? $now,
                        'updated_at' => $legacy->updated_at ?? $now,
                    ]);
                } else {
                    $userId = $user->id;
                }

                DB::table('pelaku_usaha_profiles')->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'nama_usaha' => $legacy->nama,
                        'alamat' => $legacy->alamat,
                        'nomor_telepon' => $legacy->nomor_telepon,
                        'legacy_pelaku_usaha_id' => $legacy->pelaku_usaha_id,
                        'updated_at' => $now,
                        'created_at' => $legacy->created_at ?? $now,
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaku_usaha_profiles');
    }
};
