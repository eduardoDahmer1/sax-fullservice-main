<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductTranslation extends CachedModel
{
    use LogsActivity;


    public $timestamps = false;
    protected $hidden = ['details'];
    protected $fillable = [
        'name',
        'details',
        'ship',
        'policy',
        'meta_tag',
        'meta_description',
        'features',
        'tags'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('products')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->put('product_id', $this->product_id);
        $activity->properties = $activity->properties->put('locale', $this->locale);
    }
}
