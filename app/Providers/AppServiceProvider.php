<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\Analytics\GoogleAnalyticsReportingService;
use App\Support\AnalyticsConfiguration;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GoogleAnalyticsReportingService::class, function () {
            return GoogleAnalyticsReportingService::fromConfig();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        // Ensure compatibility with older MySQL versions / utf8mb4 indexes.
        Schema::defaultStringLength(191);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );

        // Configure mail settings from the database when available.
        try {
            if (Schema::hasTable('settings')) {
                $mailer = Setting::get('mail_mailer', config('mail.default'));
                config()->set('mail.default', $mailer);

                $host = Setting::get('mail_host', null);
                $port = Setting::get('mail_port', null);
                $username = Setting::get('mail_username', null);
                $password = Setting::get('mail_password', null);
                $fromAddress = Setting::get('mail_from_address', config('mail.from.address'));
                $fromName = Setting::get('mail_from_name', config('mail.from.name'));

                if ($host) {
                    config()->set('mail.mailers.smtp.host', $host);
                }
                if ($port) {
                    config()->set('mail.mailers.smtp.port', (int) $port);
                }
                if ($username !== null) {
                    config()->set('mail.mailers.smtp.username', $username);
                }
                if ($password !== null) {
                    config()->set('mail.mailers.smtp.password', $password);
                }

                config()->set('mail.from.address', $fromAddress);
                config()->set('mail.from.name', $fromName);

                config()->set(
                    'site.enquiry_recipient_email',
                    Setting::get('enquiry_recipient_email', $fromAddress)
                );

                AnalyticsConfiguration::syncToRuntime();
            }
        } catch (\Throwable $e) {
            // During initial install/migrations the settings table may not exist yet.
        }
    }
}
