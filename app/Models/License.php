<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class License extends CachedModel
{
    use LogsActivity;

    protected static $logName = 'licenses';

    protected $fillable = ['product_id', 'login', 'password', 'code', 'available'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('licenses')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
