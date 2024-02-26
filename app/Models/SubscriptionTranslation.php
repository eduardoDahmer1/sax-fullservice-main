<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionTranslation extends CachedModel
{
    public $timestamps = false;
    protected $fillable = ['title', 'details'];
}
