<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends CachedModel
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }
}
