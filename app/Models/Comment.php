<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Comment extends CachedModel
{
    use LogsActivity;


    protected $fillable = ['product_id', 'user_id','text'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('comments')
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

    public function replies()
    {
        return $this->hasMany('App\Models\Reply');
    }
}
