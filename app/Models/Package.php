<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Package extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    protected $translatedAttributes = ['title', 'subtitle'];

    protected $fillable = ['user_id', 'price'];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('packages')
            ->logFillable()
            ->logOnlyDirty();
    }
}
