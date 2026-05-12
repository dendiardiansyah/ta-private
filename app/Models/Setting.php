<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    public static function getString(string $key, ?string $default = null): ?string
    {
        $value = static::query()->where('key', $key)->value('value');

        if ($value === null) {
            return $default;
        }

        return (string) $value;
    }

    public static function getInt(string $key, int $default): int
    {
        $value = static::getString($key, null);

        if ($value === null) {
            return $default;
        }

        $value = (int) preg_replace('/[^0-9\-]/', '', $value);

        return $value === 0 ? $default : $value;
    }

    public static function setString(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function setInt(string $key, int $value): void
    {
        static::setString($key, (string) $value);
    }

    public static function pointRateRupiahPerPoint(): int
    {
        // 1 poin = Rp 1000 (default)
        $rate = static::getInt('point_rate_rp_per_point', 1000);

        return max(1, $rate);
    }
}
