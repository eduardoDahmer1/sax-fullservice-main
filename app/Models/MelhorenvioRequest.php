<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MelhorenvioRequest extends CachedModel
{
    protected $table = 'melhorenvio_requests';

    protected $fillable = [
        'id',
        'uuid',
        'protocol',
        'service_id',
        'agency_id',
        'price',
        'status',
        'authorization_code',
        'tracking',
        'order_id',
        'preview_url',
        'created_at',
        'paid_at',
        'generated_at',
        'posted_at',
        'delivered_at',
        'canceled_at',
        'expired_at'
    ];

    public $timestamps = false;

    public function service()
    {
    	return $this->belongsTo('App\Models\MelhorenvioService', 'service_id')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }
}
