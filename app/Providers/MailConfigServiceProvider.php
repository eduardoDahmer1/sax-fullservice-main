<?php

namespace App\Providers;

use App\Models\Generalsetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Throwable;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            if (Schema::hasTable('generalsettings')) {
                $storeSettings = Generalsetting::where('is_default', 1)->first();

                if ($storeSettings) {
                    $mail_driver = "sendmail";

                    if ($storeSettings->header_email == 'smtp') {
                        $mail_driver = 'smtp';
                    }

                    $config = [
                        "driver" => $mail_driver ?? config('mail.driver'),
                        "host" => $storeSettings->smtp_host ?? config('mail.host'),
                        "port" => $storeSettings->smtp_port ?? config('mail.port'),
                        "from" => [
                            "address" => $storeSettings->from_email ?? config('mail.from.address'),
                            "name" => $storeSettings->from_name ?? config('mail.from.name')
                        ],
                        "encryption" => $storeSettings->email_encryption ?? config('mail.encryption'),
                        "username" => $storeSettings->smtp_user ?? config('mail.username'),
                        "password" => $storeSettings->smtp_pass ?? config('mail.password'),
                    ];

                    Config::set('mail', array_merge(Config::get('mail'), $config));
                }
            }
        } catch (Throwable $e) {
            return; //service provider boots everytime. Schema is not available at this point
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }
}
