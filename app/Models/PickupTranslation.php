<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class PickupTranslation extends CachedModel
{
    use LogsActivity;


    public $timestamps = false;
    protected $fillable = ['location'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('pickups')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->put('pickup_id', $this->pickup_id);
        $activity->properties = $activity->properties->put('locale', $this->locale);
    }
}
