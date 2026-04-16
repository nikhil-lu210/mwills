<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AnalyticsConfiguration
{
    /**
     * Load analytics values from the settings table into Laravel config.
     * Call after migrations are available (e.g. from AppServiceProvider::boot).
     */
    public static function syncToRuntime(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $measurement = Setting::get('ga4_measurement_id');
        $property = Setting::get('google_analytics_property_id');
        $credentials = Setting::get('analytics_credentials_path');
        $ttl = Setting::get('analytics_cache_ttl');

        config([
            'analytics.measurement_id' => self::nonEmptyString($measurement),
            'analytics.property_id' => self::nonEmptyString($property),
            'analytics.credentials_path' => self::nonEmptyString($credentials),
            'analytics.cache_ttl_seconds' => $ttl !== null && $ttl !== ''
                ? max(60, min(86400, (int) $ttl))
                : (int) config('analytics.cache_ttl_seconds', 300),
        ]);
    }

    public static function clearReportCaches(): void
    {
        foreach ([7, 30, 90] as $days) {
            Cache::forget('analytics.dashboard.'.$days);
            Cache::forget('analytics.content.'.$days);
        }
    }

    private static function nonEmptyString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $s = trim((string) $value);

        return $s === '' ? null : $s;
    }
}
