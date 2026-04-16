<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * If a service account JSON already exists in storage but no DB path is set, register it.
     */
    public function up(): void
    {
        if (Setting::get('analytics_credentials_path')) {
            return;
        }

        $candidates = [
            'app/private/analytics/ga-credentials.json',
            'app/analytics/ga-credentials.json',
            'app/google-analytics-service-account.json',
        ];

        foreach ($candidates as $relative) {
            $path = storage_path(trim($relative, '/'));
            if (is_readable($path)) {
                Setting::set('analytics_credentials_path', $relative);

                return;
            }
        }
    }
};
