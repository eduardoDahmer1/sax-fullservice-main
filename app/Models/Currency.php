<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Currency extends CachedModel
{
    use LogsActivity;


    protected $fillable = ['name', 'sign', 'value','decimal_separator','thousands_separator','decimal_digits','is_default', 'description'];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('currencies')
            ->logFillable()
            ->logOnlyDirty();
    }
}
