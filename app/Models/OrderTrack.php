<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderTrack extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    public $translatedAttributes = ['title', 'text'];

    protected $fillable = ['order_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('order_tracks')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }
}
