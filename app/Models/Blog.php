<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Blog extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    protected $translatedAttributes = ['title', 'details', 'meta_tag', 'meta_description', 'tags'];

    protected $fillable = ['category_id', 'photo', 'source', 'views', 'updated_at', 'status'];

    protected $dates = ['created_at'];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('blogs')
            ->logFillable()
            ->logOnlyDirty();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function category()
    {
        return $this->belongsTo('App\Models\BlogCategory', 'category_id')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function getTagsAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getMetaTagAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }
}
