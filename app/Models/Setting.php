<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key. Results are cached for the request.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = 'setting:' . $key;

        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = static::query()->where('key', $key)->first();

            return $setting?->value ?? $default;
        });
    }

    /**
     * Set a setting value. Clears the cache for this key.
     */
    public static function set(string $key, ?string $value): void
    {
        $setting = static::query()->firstOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        $setting->value = $value;
        $setting->save();
        Cache::forget('setting:' . $key);
    }
}
