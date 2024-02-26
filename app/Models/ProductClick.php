<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductClick extends CachedModel
{
	protected $table = 'product_clicks';
	protected $fillable = ['product_id'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
	}

}
