<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\NotifiesWithAppToast;
use App\Models\Setting;
use App\Services\Analytics\GoogleAnalyticsReportingService;
use App\Support\AnalyticsConfiguration;
use App\Support\AnalyticsCredentialsPath;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class SiteSettingsAnalytics extends Component
{
    use NotifiesWithAppToast;
    use WithFileUploads;

    public string $ga4_measurement_id = '';

    public string $google_analytics_property_id = '';

    public string $analytics_cache_ttl = '300';

    /** @var TemporaryUploadedFile|null */
    public $credentialsFile = null;

    public function mount(): void
    {
        $this->ga4_measurement_id = (string) Setting::get('ga4_measurement_id', '');
        $this->google_analytics_property_id = (string) Setting::get('google_analytics_property_id', '');
        $this->analytics_cache_ttl = (string) Setting::get('analytics_cache_ttl', '300');

        if (Storage::disk('local')->exists(AnalyticsCredentialsPath::DISK_KEY)) {
            $current = Setting::get('analytics_credentials_path');
            if ($current !== AnalyticsCredentialsPath::SETTING_VALUE) {
                Setting::set('analytics_credentials_path', AnalyticsCredentialsPath::SETTING_VALUE);
                AnalyticsConfiguration::syncToRuntime();
            }
        }
    }

    public function removeAnalyticsCredentials(): void
    {
        AnalyticsCredentialsPath::deleteFromDisk();

        Setting::set('analytics_credentials_path', null);
        AnalyticsConfiguration::syncToRuntime();
        AnalyticsConfiguration::clearReportCaches();
        if (app()->bound(GoogleAnalyticsReportingService::class)) {
            app(GoogleAnalyticsReportingService::class)->clearCachedClient();
        }
        app()->forgetInstance(GoogleAnalyticsReportingService::class);

        $this->notifyAppToast(__('Service account file removed.'));
    }

    public function save(): void
    {
        $this->ga4_measurement_id = trim($this->ga4_measurement_id);
        $this->google_analytics_property_id = preg_replace('/\D+/', '', $this->google_analytics_property_id ?? '');

        $this->validate([
            'ga4_measurement_id' => ['nullable', 'string', 'max:64'],
            'google_analytics_property_id' => ['nullable', 'string', 'max:24', 'regex:/^[0-9]*$/'],
            'analytics_cache_ttl' => ['required', 'integer', 'min:60', 'max:86400'],
            'credentialsFile' => ['nullable', 'file', 'max:10240'],
        ], [
            'google_analytics_property_id.regex' => __('Property ID must contain digits only.'),
        ]);

        if ($this->ga4_measurement_id !== '' && ! preg_match('/^(G-[A-Z0-9]+|GTM-[A-Z0-9]+)$/i', $this->ga4_measurement_id)) {
            $this->addError('ga4_measurement_id', __('Use a valid Measurement ID (e.g. G-XXXXXXXX) or a GTM container ID.'));

            return;
        }

        if ($this->credentialsFile) {
            $contents = $this->credentialsFile->get();
            $decoded = json_decode($contents, true);
            if (! is_array($decoded) || ($decoded['type'] ?? null) !== 'service_account') {
                $this->addError('credentialsFile', __('This file is not a valid Google service account JSON key.'));

                return;
            }

            Storage::disk('local')->makeDirectory(dirname(AnalyticsCredentialsPath::DISK_KEY));
            Storage::disk('local')->put(AnalyticsCredentialsPath::DISK_KEY, $contents);
            Setting::set('analytics_credentials_path', AnalyticsCredentialsPath::SETTING_VALUE);
        }

        Setting::set('ga4_measurement_id', $this->ga4_measurement_id !== '' ? $this->ga4_measurement_id : null);
        Setting::set('google_analytics_property_id', $this->google_analytics_property_id !== '' ? $this->google_analytics_property_id : null);
        Setting::set('analytics_cache_ttl', (string) $this->analytics_cache_ttl);

        AnalyticsConfiguration::syncToRuntime();
        AnalyticsConfiguration::clearReportCaches();
        if (app()->bound(GoogleAnalyticsReportingService::class)) {
            app(GoogleAnalyticsReportingService::class)->clearCachedClient();
        }
        app()->forgetInstance(GoogleAnalyticsReportingService::class);

        $this->credentialsFile = null;

        $this->notifyAppToast(__('Settings saved.'));
    }

    public function getHasAnalyticsCredentialsProperty(): bool
    {
        return AnalyticsCredentialsPath::exists(Setting::get('analytics_credentials_path'));
    }

    public function render()
    {
        return view('livewire.admin.settings.analytics')
            ->layout('layouts.app.sidebar', [
                'title' => __('Site settings'),
                'breadcrumbs' => [
                    ['label' => __('Dashboard'), 'href' => route('dashboard')],
                    ['label' => __('Settings'), 'href' => route('admin.settings.general')],
                    ['label' => __('Analytics'), 'href' => null],
                ],
            ]);
    }
}
