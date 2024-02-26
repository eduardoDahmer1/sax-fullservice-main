<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MelhorenvioService extends CachedModel
{
    protected $table = 'melhorenvio_services';

    protected $fillable = [
        'id',
        'company_id',
        'name',
        'type',
        'insurance_min',
        'insurance_max'
    ];

    public $timestamps = false;

    public function company()
    {
        return $this->belongsTo('App\Models\MelhorenvioCompany', 'company_id')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }
}
