<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MelhorenvioCompany extends CachedModel
{
    protected $table = 'melhorenvio_companies';

    protected $fillable = [
        'id',
        'name',
        'picture'
    ];

    public $timestamps = false;

    public function services()
    {
        return $this->hasMany('App\Models\MelhorenvioService', 'company_id');
    }
}
