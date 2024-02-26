<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\BackInStock;
use App\Events\PublishedWeddingList;
use App\Events\WatchPix;
use App\Listeners\HandleBackInStock;
use App\Listeners\HandleWatchPix;
use App\Listeners\SendWeddingListNotification;
use App\Models\Order;
use App\Observers\OrderObserver;


use App\Models\Brand;
use App\Observers\BrandObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */    

    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        BackInStock::class => [
            HandleBackInStock::class
        ],
        WatchPix::class => [
            HandleWatchPix::class
        ],
        PublishedWeddingList::class => [
            SendWeddingListNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {   
        parent::boot();
        Brand::observe(BrandObserver::class);
    }
}
