<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $alamat
 * @property string|null $nomor_telepon
 * @property int|null $total_poin
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'total_poin', // Menyimpan total poin pengguna
        'alamat',
        'nomor_telepon',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke tabel penarikan_poin
    public function penarikanPoin()
    {
        return $this->hasMany(PenarikanPoin::class, 'nasabah_id');
    }

    // Relasi ke tabel poin
    public function poin()
    {
        return $this->hasMany(Poin::class, 'nasabah_id');
    }

    public function assignedTransaksi()
    {
        return $this->hasMany(Transaksi::class, 'petugas_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function pelakuUsahaProfile(): HasOne
    {
        return $this->hasOne(PelakuUsahaProfile::class, 'user_id');
    }

    /**
     * @param  array<int, string>  $roleNames
     */
    public function hasAnyRole(array $roleNames): bool
    {
        $roleNames = array_values(array_filter(array_map(fn($r) => strtolower(trim((string) $r)), $roleNames)));

        $expanded = [];
        foreach ($roleNames as $name) {
            $expanded[] = $name;

            // Backward-compat aliasing: some code/UX refers to Nasabah as "nasabah",
            // while DB/seeders use "user".
            if ($name === 'user') {
                $expanded[] = 'nasabah';
            }

            if ($name === 'nasabah') {
                $expanded[] = 'user';
            }

        }

        $roleNames = array_values(array_unique(array_filter($expanded)));

        if ($roleNames === []) {
            return false;
        }

        return $this->roles()->whereIn(DB::raw('LOWER(name)'), $roleNames)->exists();
    }

    public function hasRole(string $roleName): bool
    {
        return $this->hasAnyRole([$roleName]);
    }
}
