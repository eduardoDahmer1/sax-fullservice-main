<?php

namespace App\Models;

class Shipping_prices extends LocalizedModel
{
    public $translatedAttributes = ['delivery_time'];
    
    protected $fillable = ['shipping_id', 'country_id', 'state_id', 'city_id', 'price', 'price_per_kilo', 'price_free_shipping', 'status'];

    public $timestamps = false;

    protected $table = 'shipping_prices';

    protected $with = ['translations'];

}