<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends CachedModel
{
    protected $fillable = ['email'];
    public $timestamps = false;
}
