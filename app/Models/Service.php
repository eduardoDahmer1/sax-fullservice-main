<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Service extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    protected $translatedAttributes = ['title', 'details'];
    protected $fillable = ['user_id', 'photo', 'link'];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('services')
            ->logFillable()
            ->logOnlyDirty();
    }
}
