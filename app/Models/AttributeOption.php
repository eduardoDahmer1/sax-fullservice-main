<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class AttributeOption extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    public $translatedAttributes = ['name', 'description'];
    protected $fillable = ['attribute_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('attribute_options')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function attribute()
    {
        return $this->belongsTo('App\Models\Attribute')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_id = auth('admin')->user()->id;
        $activity->causer_type = Admin::class;
    }
}
