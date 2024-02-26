<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends CachedModel
{
    protected $fillable = ['product_id','photo'];
    public $timestamps = false;

    public function getPhotoUrlAttribute()
    {
        if (
            filter_var($this->photo, FILTER_SANITIZE_URL)
            && !file_exists('storage/images/galleries/' . $this->photo)
        ) {
            return $this->photo;
        }

        return asset('storage/images/galleries/' . $this->photo);
    }
}
