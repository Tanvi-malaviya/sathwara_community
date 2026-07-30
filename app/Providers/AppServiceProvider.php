<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('settings')) {
                $mailer = Setting::get('mail_mailer');
                if ($mailer) {
                    $encryption = Setting::get('mail_encryption');
                    config([
                        'mail.default' => $mailer,
                        'mail.mailers.smtp.host' => Setting::get('mail_host', config('mail.mailers.smtp.host')),
                        'mail.mailers.smtp.port' => Setting::get('mail_port', config('mail.mailers.smtp.port')),
                        'mail.mailers.smtp.username' => Setting::get('mail_username', config('mail.mailers.smtp.username')),
                        'mail.mailers.smtp.password' => Setting::get('mail_password', config('mail.mailers.smtp.password')),
                        'mail.mailers.smtp.encryption' => ($encryption === 'null' || strtolower($encryption) === 'none') ? null : $encryption,
                        'mail.from.address' => Setting::get('mail_from_address', config('mail.from.address')),
                        'mail.from.name' => Setting::get('mail_from_name', config('mail.from.name')),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Silence provider errors before database migrations run
        }
    }
}
