<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Database\CacheQueryBuilder;
use Cache;

class CachedModel extends Model
{
    use CacheQueryBuilder;

    public static function boot()
    {
        static::creating(function ($model) {
            Cache::store('request')->tags($model->getTable())->flush();
        });

        static::updating(function ($model) {
            Cache::store('request')->tags($model->getTable())->flush();
        });

        static::deleting(function ($model) {
            Cache::store('request')->tags($model->getTable())->flush();
        });
        
        parent::boot();
    }
}
