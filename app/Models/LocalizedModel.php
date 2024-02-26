<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class LocalizedModel extends CachedModel implements TranslatableContract
{
    use Translatable;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        //$lang = Language::find(1);
        //$this->setDefaultLocale($lang->locale);
    }
}
