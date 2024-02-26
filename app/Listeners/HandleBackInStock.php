<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\BackInStock;
use App\Classes\GeniusMailer;

class HandleBackInStock
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
     * @param  BackInStock  $event
     * @return void
     */
    public function handle(\App\Events\BackInStock $event)
    {
        $product = $event->product();
        $back_in_stocks = BackInStock::where('product_id', $product->id)->get();
        if($back_in_stocks->isEmpty()) return;
        $product_url = route('front.product', $product->slug);

        $storeSettings = $event->storeSettings();

        $email_list = [];

        foreach($back_in_stocks as $back_in_stock) {
            array_push($email_list, $back_in_stock->email);
        }

        if($storeSettings->is_smtp) {
            foreach($email_list as $key => $email) {
                $maildata = [
                    'to' => $email,
                    'type' => "back_in_stock",
                    'cname' => "",
                    'oamount' => "",
                    'aname' => "",
                    'aemail' => "",
                    'onumber' => "",
                    'product' => '<a target="_blank" href='.$product_url.'> '.$product->name.' </a>'
                ];
                $mailer = new GeniusMailer();
                $mailer->sendAutoMail($maildata);
            }

            BackInStock::where('product_id', $product->id)->delete();
        }
    }
}
