<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        // 1) Extend users with pelaku usaha profile fields (optional)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'total_poin')) {
                $table->integer('total_poin')->default(0);
            }
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable();
            }
            if (!Schema::hasColumn('users', 'nomor_telepon')) {
                $table->string('nomor_telepon')->nullable();
            }
        });

        // For sqlite testing we don't need the legacy migration logic.
        // Also: keep the legacy table so any existing FK constraints remain valid.
        if ($driver === 'sqlite') {
            return;
        }

        // Ensure admin role exists
        $adminRoleId = null;
        $pelakuUsahaRoleId = null;
        if (Schema::hasTable('roles')) {
            $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
            if (!$adminRoleId) {
                $adminRoleId = DB::table('roles')->insertGetId([
                    'name' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $pelakuUsahaRoleId = DB::table('roles')->where('name', 'pelaku_usaha')->value('id');
            if (!$pelakuUsahaRoleId) {
                $pelakuUsahaRoleId = DB::table('roles')->insertGetId([
                    'name' => 'pelaku_usaha',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 2) Migrate legacy pelaku_usaha accounts into users (role-based)
        if (Schema::hasTable('pelaku_usaha')) {
            $now = now();
            $pelakuUsahas = DB::table('pelaku_usaha')->get();

            foreach ($pelakuUsahas as $pelakuUsaha) {
                $email = 'pelaku_usaha_' . $pelakuUsaha->pelaku_usaha_id . '@local.invalid';

                $existingUser = DB::table('users')->where('email', $email)->first();

                if (!$existingUser) {
                    $userId = DB::table('users')->insertGetId([
                        'name' => $pelakuUsaha->nama,
                        'email' => $email,
                        'password' => $pelakuUsaha->password,
                        'total_poin' => 0,
                        'alamat' => $pelakuUsaha->alamat,
                        'nomor_telepon' => $pelakuUsaha->nomor_telepon,
                        'created_at' => $pelakuUsaha->created_at ?? $now,
                        'updated_at' => $pelakuUsaha->updated_at ?? $now,
                    ]);

                    if ($adminRoleId && Schema::hasTable('user_roles')) {
                        DB::table('user_roles')->updateOrInsert(
                            ['user_id' => $userId, 'role_id' => $adminRoleId],
                            ['created_at' => $now, 'updated_at' => $now]
                        );
                    }

                    if ($pelakuUsahaRoleId && Schema::hasTable('user_roles')) {
                        DB::table('user_roles')->updateOrInsert(
                            ['user_id' => $userId, 'role_id' => $pelakuUsahaRoleId],
                            ['created_at' => $now, 'updated_at' => $now]
                        );
                    }
                } else {
                    DB::table('users')
                        ->where('id', $existingUser->id)
                        ->update([
                            'alamat' => $existingUser->alamat ?? $pelakuUsaha->alamat,
                            'nomor_telepon' => $existingUser->nomor_telepon ?? $pelakuUsaha->nomor_telepon,
                            'updated_at' => $now,
                        ]);

                    if ($adminRoleId && Schema::hasTable('user_roles')) {
                        DB::table('user_roles')->updateOrInsert(
                            ['user_id' => $existingUser->id, 'role_id' => $adminRoleId],
                            ['created_at' => $existingUser->created_at ?? $now, 'updated_at' => $now]
                        );
                    }

                    if ($pelakuUsahaRoleId && Schema::hasTable('user_roles')) {
                        DB::table('user_roles')->updateOrInsert(
                            ['user_id' => $existingUser->id, 'role_id' => $pelakuUsahaRoleId],
                            ['created_at' => $existingUser->created_at ?? $now, 'updated_at' => $now]
                        );
                    }
                }
            }

            // 3) Drop legacy FK first so we can safely remap IDs
            if (Schema::hasTable('transaksi') && Schema::hasColumn('transaksi', 'pelaku_usaha_id')) {
                try {
                    Schema::table('transaksi', function (Blueprint $table) {
                        $table->dropForeign('transaksi_pelaku_usaha_id_foreign');
                    });
                } catch (Throwable $e) {
                    // ignore if FK name differs
                }
            }

            // 4) Re-map transaksi.pelaku_usaha_id from legacy pelaku_usaha_id => users.id
            if (Schema::hasTable('transaksi') && Schema::hasColumn('transaksi', 'pelaku_usaha_id')) {
                DB::statement(
                    "UPDATE transaksi t\n" .
                    "JOIN users u ON u.email = CONCAT('pelaku_usaha_', t.pelaku_usaha_id, '@local.invalid')\n" .
                    "SET t.pelaku_usaha_id = u.id\n" .
                    "WHERE t.pelaku_usaha_id IS NOT NULL"
                );
            }

            // 5) Add new FK transaksi.pelaku_usaha_id -> users.id
            if (Schema::hasTable('transaksi') && Schema::hasColumn('transaksi', 'pelaku_usaha_id')) {
                Schema::table('transaksi', function (Blueprint $table) {
                    $table->foreign('pelaku_usaha_id')->references('id')->on('users')->onDelete('cascade');
                });
            }

            // 6) Drop legacy table
            Schema::drop('pelaku_usaha');
        }
    }

    public function down(): void
    {
        // Recreate the legacy table (data will not be restored)
        Schema::create('pelaku_usaha', function (Blueprint $table) {
            $table->id('pelaku_usaha_id');
            $table->string('nama');
            $table->string('password');
            $table->text('alamat')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->timestamps();
        });

        // Switch FK back to legacy table (values won't match; keep nullable)
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['pelaku_usaha_id']);
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->foreign('pelaku_usaha_id')->references('pelaku_usaha_id')->on('pelaku_usaha')->onDelete('cascade');
        });

        // Drop user profile columns
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nomor_telepon')) {
                $table->dropColumn('nomor_telepon');
            }
            if (Schema::hasColumn('users', 'alamat')) {
                $table->dropColumn('alamat');
            }
        });
    }
};
