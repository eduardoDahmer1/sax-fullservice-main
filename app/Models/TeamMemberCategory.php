<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class TeamMemberCategory extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    protected $table = 'team_member_categories';

    public $translatedAttributes = ['name'];
    protected $guarded = ['id'];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('team_member_categories')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function team_members()
    {
        return $this->hasMany(TeamMember::class, 'category_id');
    }

    public function scopeWithWhatsapp($query)
    {
        return $query->whereHas(
            'team_members',
            function (
                Builder $query
            ) {
                $query->whereNotNull('whatsapp');
            }
        );
    }
}
