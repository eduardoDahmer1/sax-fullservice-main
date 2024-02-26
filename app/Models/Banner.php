<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Banner extends CachedModel
{
    use LogsActivity;


    protected $fillable = ['photo','link','type'];

    protected $storeSettings;

    public function __construct()
    {
        $this->storeSettings = resolve('storeSettings');

        parent::__construct();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('banners')
            ->logFillable()
            ->logOnlyDirty();
    }


    public function stores()
    {
        return $this->belongsToMany('App\Models\Generalsetting', 'banner_store', 'banner_id', 'store_id');
    }

    public function scopeByStore($query)
    {
        return $query->whereHas('stores', function ($query) {
            $query->where('store_id', $this->storeSettings->id);
        });
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', 1);
    }
}
