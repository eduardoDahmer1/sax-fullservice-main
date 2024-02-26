<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\Models\Generalsetting;
use App\Helpers\Helper;
use DOMDocument;
use App\Classes\XMLHelper;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CrudGenerator::class,
        Commands\GenerateThumbnails::class,
        Commands\UpdatePhotoReferences::class,
        Commands\CheckDiscrepancies::class,
        Commands\GenerateAccessToken::class,
        Commands\ProductImport::class,
        Commands\OrderExport::class
        //
    ];

    private $xml_helper;

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected function schedule(Schedule $schedule)
    {
        /**
         * Only execute the command, if the key in the .ENV is enabled.
         *
         */
        if (env("ENABLE_COMPRAS_PARAGUAI", false)) {
            $schedule->call(function () {
                $this->xml_helper = new XMLHelper();
                $this->xml_helper->updateComprasParaguai();
            });
        }

        if (Generalsetting::find(1)->is_lojaupdate) {
            $schedule->call(function () {
                $this->xml_helper = new XMLHelper();
                $this->xml_helper->updateLojaFacebook();
                $this->xml_helper->updateLojaGoogle();
            })->hourly();
        }

        if (config('app.thumbnails')) {
            if (Generalsetting::where('is_default', 1)->first()->ftp_folder) {
                $schedule->command('generate:thumbnails')->daily();
            }
        }

        if (env("ENABLE_MERCADO_LIVRE", false)) {
            $schedule->command('regenerate:token')->cron('0 */4 * * *'); //Every four hours
        }

        if (env("ENABLE_FEDEX_SHIPPING", false)) {
            $schedule->command('regenerate:token-fedex')->cron('*/50 * * * *'); //Every 50 minutes
        }

        $schedule->command('product:import')->hourly();

        $schedule->command('order:export')->hourly();

        $schedule->command('bling:refresh')->everyFourHours();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
