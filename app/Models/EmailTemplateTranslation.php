<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class EmailTemplateTranslation extends CachedModel
{
    use LogsActivity;

    public $timestamps = false;
    protected $fillable = ['email_subject', 'email_body'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('email_templates')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->put('email_template_id', $this->email_template_id);
        $activity->properties = $activity->properties->put('locale', $this->locale);
    }
}
