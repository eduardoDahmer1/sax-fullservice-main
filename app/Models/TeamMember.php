<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TeamMember extends CachedModel
{
    use LogsActivity;


    protected $fillable = ['name', 'category_id', 'photo', 'whatsapp', 'skype', 'email'];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('team_members')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function category()
    {
        return $this->belongsTo(TeamMemberCategory::class, 'category_id');
    }
}
