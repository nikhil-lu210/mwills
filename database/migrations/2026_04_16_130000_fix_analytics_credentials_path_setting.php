<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Local disk root is storage/app/private; credentials live at analytics/ga-credentials.json.
     * Older code stored app/analytics/... which resolves to the wrong folder.
     */
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        if (! Storage::disk('local')->exists('analytics/ga-credentials.json')) {
            return;
        }

        $correct = 'app/private/analytics/ga-credentials.json';
        if (Setting::get('analytics_credentials_path') === $correct) {
            return;
        }

        Setting::set('analytics_credentials_path', $correct);
    }
};
