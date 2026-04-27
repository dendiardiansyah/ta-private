<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('roles') || !Schema::hasTable('user_roles')) {
            return;
        }

        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $pelakuUsahaRoleId = DB::table('roles')->where('name', 'pelaku_usaha')->value('id');

        if (!$adminRoleId || !$pelakuUsahaRoleId) {
            return;
        }

        $now = now();

        $userIds = DB::table('user_roles')
            ->where('role_id', $pelakuUsahaRoleId)
            ->pluck('user_id');

        if ($userIds->isEmpty()) {
            return;
        }

        $rows = $userIds
            ->unique()
            ->map(fn($userId) => [
                'user_id' => $userId,
                'role_id' => $adminRoleId,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->values()
            ->all();

        // MySQL: INSERT IGNORE, SQLite: INSERT OR IGNORE
        DB::table('user_roles')->insertOrIgnore($rows);
    }

    public function down(): void
    {
        // Intentionally left blank.
        // Removing admin roles could revoke legitimate admins.
    }
};
