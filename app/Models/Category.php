<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    public $translatedAttributes = ['name'];
    protected $fillable = ['slug','photo','is_featured','image','status', 'is_customizable', 'ref_code', 'is_customizable_number', 'banner','link'];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('categories')
            ->logFillable()
            ->logOnlyDirty();
    }


    public function subs()
    {
        return $this->hasMany('App\Models\Subcategory')->where('status', '=', 1);
    }

    public function childs()
    {
        return $this->hasMany('App\Models\Childcategory')->where('status', '=', 1);
    }

    public function subs_order_by()
    {
        return $this->hasMany('App\Models\Subcategory')->orderBy('slug')->where('status', '=', 1);
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
    
    public function scopeWithProducts($query){
        return $query->has('products');
    }

    public function scopeWithoutProducts($query){
        return $query->doesntHave('products');
    }

    public function categories_galleries()
    {
        return $this->hasMany('App\Models\CategoryGallery');
    }

    public function scopeActive($query)
    {
        return $query->where('categories.status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('categories.status', 0);
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '-', $value);
    }

    public function attributes()
    {
        return $this->morphMany('App\Models\Attribute', 'attributable');
    }

    public function getBannerLinkAttribute()
    {
        return $this->banner ? asset('storage/images/categories/banners/'.$this->banner) : null;
    }
}
