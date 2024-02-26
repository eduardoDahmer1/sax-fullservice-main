<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Generalsetting;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CartAbandonment extends Model
{
    public function __construct() {
        parent::__construct();
    }

    protected $fillable = [
        'temp_cart',
    ];

    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function tempCart(): Attribute
    {
        return Attribute::make(
            get: function ($value){
                $data = json_decode($value, true);
                if (!$data) {
                    return new Cart;
                }

                return new Cart($data);
            },
        );
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }
}
