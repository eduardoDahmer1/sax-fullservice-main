<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Report extends CachedModel
{
    use LogsActivity;


    protected $fillable = ['product_id', 'user_id','title','note'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('reports')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }
}
