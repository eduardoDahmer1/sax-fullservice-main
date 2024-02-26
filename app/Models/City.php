<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends CachedModel
{
    protected $fillable = ['name','state_id'];
    public $timestamps = false;

    protected $table = 'cities';

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
