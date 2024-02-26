<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements JWTSubject
{
    use LogsActivity;


    protected $guard = 'admin';

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role_id', 'photo', 'created_at', 'updated_at', 'remember_token','shop_name'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('staff')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function IsSuper()
    {
        if ($this->role_id == 0) {
            return true;
        }
        return false;
    }

    public function sectionCheck($value)
    {
        if ($this->IsSuper()) {
            return true;
        }

        $sections = explode(" , ", $this->role->section);
        if (in_array($value, $sections)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset("storage/images/admins/{$this->photo}") : asset('assets/images/user.jpg');
    }
}
