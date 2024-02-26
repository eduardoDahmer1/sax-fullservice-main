<?php

namespace App\Listeners;

use App\Events\PublishedWeddingList;
use App\Mail\WeddingListPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWeddingListNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Providers\PublishedWeddingList  $event
     * @return void
     */
    public function handle(PublishedWeddingList $event)
    {
        Mail::to($event->user->email)
            ->bcc(config('mail.from.address'))
            ->send(new WeddingListPublished($event->user));
    }
}
