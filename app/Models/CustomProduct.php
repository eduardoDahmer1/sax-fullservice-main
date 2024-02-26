<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomProduct extends CachedModel
{
    protected $table = 'custom_prod';
    protected $fillable = ['customizable_name', 'customizable_logo', 'agree_terms', 'product_id', 'order_id'];
    public $timestamps = false;
}
