<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;


class MercadoLivre extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mercadolivre';
    }
}