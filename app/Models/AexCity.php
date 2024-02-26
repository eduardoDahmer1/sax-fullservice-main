<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AexCity extends CachedModel
{
    protected $fillable = [
                            'codigo_ciudad',
                            'denominacion',
                            'codigo_departamento',
                            'codigo_pais',
                            'ubicacion_geografica',
                            'departamento_denominacion',
                            'pais_denominacion'
                        ];

    public $timestamps = false;    
}
