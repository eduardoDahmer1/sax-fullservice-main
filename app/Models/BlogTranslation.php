<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class BlogTranslation extends CachedModel
{
    use LogsActivity;


    public $timestamps = false;
    protected $fillable = ['title', 'details', 'meta_tag', 'meta_description', 'tags'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('blogs')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->put('blog_id', $this->blog_id);
        $activity->properties = $activity->properties->put('locale', $this->locale);
    }
}
