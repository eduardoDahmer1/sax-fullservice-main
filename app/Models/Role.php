<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    public $translatedAttributes = ['name'];
    protected $fillable = ['section'];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('roles')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function admins()
    {
        return $this->hasMany('App\Models\Admin');
    }


    public function sectionCheck($value)
    {
        $sections = explode(" , ", $this->section);
        if (in_array($value, $sections)) {
            return true;
        } else {
            return false;
        }
    }
}
