<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BankAccount extends CachedModel
{
    use LogsActivity;


    protected $fillable = [
        'name', 'info', 'status'
    ];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('bank_accounts')
            ->logFillable()
            ->logOnlyDirty();
    }
}
