<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Partner extends CachedModel
{
    use LogsActivity;


    protected $fillable = ['link','photo'];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('logo_sliders')
            ->logFillable()
            ->logOnlyDirty();
    }
}
