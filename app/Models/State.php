<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends CachedModel
{
    protected $fillable = ['name', 'initial', 'country_id'];
    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}

