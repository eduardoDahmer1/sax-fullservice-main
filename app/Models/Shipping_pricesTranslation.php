<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping_pricesTranslation extends CachedModel
{
    public $timestamps = false;
    protected $fillable = ['delivery_time'];
}
