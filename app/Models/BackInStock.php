<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackInStock extends CachedModel
{
    protected $fillable = ['product_id', 'email'];

	protected $table = "back_in_stock";

    public function product()
    {
    	return $this->belongsTo('App\Models\Product')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }
}

