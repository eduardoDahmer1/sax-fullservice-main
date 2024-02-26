<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MelhorenvioConf extends CachedModel
{
    protected $table = 'melhorenvio';

    protected $fillable = [
        'production',
        'token',
        'from_postalcode',
        'receipt',
        'own_hand',
        'collect',
        'selected_services',
        'from_name',
        'from_phone',
        'from_email',
        'from_document',
        'from_company_document',
        'from_state_register',
        'from_address',
        'from_number',
        'from_complement',
        'from_district',
        'from_city',
        'from_state',
        'from_country',
        'from_postal_code',
        'from_note'
    ];

    protected $casts = [
        'selected_services' => 'array',
    ];
    
    public $timestamps = false;
}
