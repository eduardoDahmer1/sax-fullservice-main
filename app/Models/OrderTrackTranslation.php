<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderTrackTranslation extends CachedModel
{
    use LogsActivity;


    public $timestamps = false;
    protected $fillable = ['title', 'text'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('order_tracks')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->put('order_track_id', $this->order_track_id);
        $activity->properties = $activity->properties->put('locale', $this->locale);
    }
}
