<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Brand extends CachedModel
{
    use LogsActivity;


    protected $fillable = ['name', 'slug', 'image', 'partner','ref_code', 'banner'];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('brands')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImageAttribute($value)
    {
        if (
            !File::exists(public_path('storage/images/brands/' . $value)) ||
            File::isDirectory($value)
        ) {
            return null;
        }

        return $value;
    }

    public function getThumbnailAttribute($value)
    {
        if (!$this->image) {
            return asset('assets/images/noimage.png');
        }
        if (!$value) {
            return asset('assets/images/noimage.png');
        }
        if (!File::exists(public_path('storage/images/thumbnails/' . $value)) || File::isDirectory($value)) {
            return asset('assets/images/noimage.png');
        }
        return asset('storage/images/thumbnails/'.$value);
    }

    // Filter Active Tags
    public function scopeActive($query)
    {
        return $query->where('brands.status', 1);
    }

    // Filter Inactive Tags
    public function scopeInactive($query)
    {
        return $query->where('brands.status', 0);
    }

    // Filter if there are products
    public function scopeWithProducts($query)
    {
        return $query->has('products');
    }
    
    // Filter if there are no products
    public function scopeWithoutProducts($query)
    {
        return $query->doesntHave('products');
    }
    
}
