<?php

namespace App\Models;

use App\Models\CachedModel;

class FedexConf extends CachedModel
{
    protected $table = 'fedex';

    protected $fillable = ['client_id', 'client_secret', 'production', 'access_token'];
}