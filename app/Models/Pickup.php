<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Pickup extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    protected $translatedAttributes = ['location'];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('pickups')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
