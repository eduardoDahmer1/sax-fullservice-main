<?php

namespace App\Providers;

use App\Models\WeddingProduct;
use App\Models\WishlistGroup;
use App\Policies\WeddingProductPolicy;
use App\Policies\WishlistGroupPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        WishlistGroup::class => WishlistGroupPolicy::class,
        WeddingProduct::class => WeddingProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
