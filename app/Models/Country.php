<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends CachedModel
{
    protected $fillable = ['country_code','country_name'];
    public $timestamps = false;
}

