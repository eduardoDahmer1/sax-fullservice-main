<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Socialsetting extends CachedModel
{
    use LogsActivity;


    protected $fillable = ['facebook', 'twitter','linkedin', 'dribble', 'instagram', 'youtube', 'y_status', 'f_status', 't_status', 'l_status', 'd_status','i_status','f_check','g_check','fclient_id','fclient_secret','fredirect','gclient_id','gclient_secret','gredirect'];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('socials')
            ->logFillable()
            ->logOnlyDirty();
    }
}
