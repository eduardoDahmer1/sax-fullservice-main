<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class PackageTranslation extends Model
{
    use LogsActivity;


    public $timestamps = false;
    protected $fillable = ['title', 'subtitle'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('packages')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->put('package_id', $this->package_id);
        $activity->properties = $activity->properties->put('locale', $this->locale);
    }
}
