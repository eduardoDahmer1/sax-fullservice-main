<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class UserTranslation extends CachedModel
{
    use LogsActivity;


    public $timestamps = false;
    protected $fillable = ['shop_message'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('customers')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->put('user_id', $this->user_id);
        $activity->properties = $activity->properties->put('locale', $this->locale);
    }
}
