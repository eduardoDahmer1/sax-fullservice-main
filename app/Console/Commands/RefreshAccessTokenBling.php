<?php

namespace App\Console\Commands;

use App\Models\Generalsetting;
use App\Services\Bling;
use Illuminate\Console\Command;

class RefreshAccessTokenBling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bling:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh access token of Bling';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $gs = Generalsetting::first();
        $bling = new Bling($gs->bling_access_token, $gs->bling_refresh_token);
        $bling->refreshAccessToken();

        $gs->bling_access_token = $bling->access_token ?? $gs->bling_access_token;
        $gs->save();

        return 0;
    }
}
