<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Subcategory extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    public $translatedAttributes = ['name'];
    protected $fillable = ['category_id','slug','status','ref_code', 'banner'];
    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('subcategories')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function childs()
    {
        return $this->hasMany('App\Models\Childcategory')->where('status', '=', 1);
    }

    public function childs_order_by()
    {
        return $this->hasMany('App\Models\Childcategory')->orderBy('slug')->where('status', '=', 1);
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
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
        return $this->banner ? asset('storage/images/subcategories/banners/'.$this->banner) : null;
    }
}
