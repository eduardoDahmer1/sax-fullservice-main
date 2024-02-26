<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends CachedModel
{
    protected $fillable = [
        'user_id', 
        'reference', 
        'amount', 
        'fee', 
        'created_at', 
        'updated_at', 
        'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }
}
