<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Page extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    public $translatedAttributes = ['title', 'details', 'meta_tag', 'meta_description'];
    protected $fillable = ['slug'];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('pages')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function getMetaTagAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }
}
