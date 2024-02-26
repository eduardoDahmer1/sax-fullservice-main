<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Shipping extends LocalizedModel
{
    use LogsActivity;


    protected $with = ['translations'];

    protected $translatedAttributes = ['title', 'subtitle', 'delivery_time'];

    protected $fillable = [
        'user_id',
        'price',
        'shipping_type',
        'price_free_shipping',
        'price_per_kilo',
        'status',
        'local_shipping',
        'city_id',
        'state_id',
        'country_id',
        'cep_start',
        'cep_end',
        'is_region',
    ];

    protected $casts = [
        'cep_start' => 'integer',
        'cep_end' => 'integer'
    ];

    public $timestamps = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('shippings')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Set the correct cep.
     *
     * @param  integer  $value
     * @return void
     */

    public function setCepStartAttribute($value)
    {
        $this->attributes['cep_start'] =  preg_replace("/[^0-9]/", "", $value);
    }

    public function setCepEndAttribute($value)
    {
        $this->attributes['cep_end'] =  preg_replace("/[^0-9]/", "", $value);
    }
}
