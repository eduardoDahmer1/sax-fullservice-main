<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Seotool extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    public $translatedAttributes = ['meta_keys','meta_description'];
    protected $fillable = [
        'google_analytics',
        'facebook_pixel',
        'tag_manager_head',
        'tag_manager_body'
    ];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('seotools')
            ->logFillable()
            ->logOnlyDirty();
    }
}
