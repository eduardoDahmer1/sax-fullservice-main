<?php

namespace App\Providers;

use App\Models\MercadoLivre;
use Illuminate\Support\ServiceProvider;

class MercadoLivreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mercadolivre', function() {
            return new MercadoLivre();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
