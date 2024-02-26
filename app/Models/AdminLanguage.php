<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AdminLanguage extends CachedModel
{
    use LogsActivity;


    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('admin_languages')
            ->logFillable()
            ->logOnlyDirty();
    }
}
