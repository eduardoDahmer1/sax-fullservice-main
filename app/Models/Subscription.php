<?php

namespace App\Models;

class Subscription extends LocalizedModel
{
    public $translatedAttributes = ['title', 'details'];
    protected $fillable = ['price', 'days', 'allowed_products'];
    public $timestamps = false;

    protected $with = ['translations'];
}
