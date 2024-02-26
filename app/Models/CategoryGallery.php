<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryGallery extends CachedModel
{
    protected $table = 'categories_gallery';
    protected $fillable = ['category_id','customizable_gallery', 'thumbnail'];
    public $timestamps = false;
}
