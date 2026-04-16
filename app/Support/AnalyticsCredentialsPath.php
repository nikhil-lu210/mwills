<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * The local disk root is storage/app/private (see config/filesystems.php).
 * Credentials are stored at analytics/ga-credentials.json on that disk.
 * The settings table stores the path relative to the project storage/ directory
 * (e.g. app/private/analytics/ga-credentials.json) for compatibility with storage_path().
 */
final class AnalyticsCredentialsPath
{
    public const DISK_KEY = 'analytics/ga-credentials.json';

    public const SETTING_VALUE = 'app/private/analytics/ga-credentials.json';

    /**
     * Absolute filesystem path to the credentials file, or empty string if missing.
     */
    public static function resolveAbsolute(?string $storedInSetting): string
    {
        if (is_string($storedInSetting) && $storedInSetting !== '') {
            $attempt = storage_path(trim($storedInSetting, '/'));
            if (is_readable($attempt)) {
                return $attempt;
            }
        }

        if (Storage::disk('local')->exists(self::DISK_KEY)) {
            return Storage::disk('local')->path(self::DISK_KEY);
        }

        $legacy = storage_path('app/analytics/ga-credentials.json');
        if (is_readable($legacy)) {
            return $legacy;
        }

        return '';
    }

    public static function exists(?string $storedInSetting): bool
    {
        return self::resolveAbsolute($storedInSetting) !== '';
    }

    public static function deleteFromDisk(): void
    {
        Storage::disk('local')->delete(self::DISK_KEY);

        $legacy = storage_path('app/analytics/ga-credentials.json');
        if (is_file($legacy)) {
            @unlink($legacy);
        }
    }
}
