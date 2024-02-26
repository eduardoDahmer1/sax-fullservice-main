<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationTranslation extends CachedModel
{
    public $timestamps = false;
    protected $fillable = ['text', 'warning_reason'];
}
