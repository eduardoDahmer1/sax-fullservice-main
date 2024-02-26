<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class GeneralsettingTranslation extends CachedModel
{
    use LogsActivity;


    public $timestamps = false;
    protected $fillable = [
        'title',
        'footer',
        'copyright',
        'cod_text',
        'popup_title',
        'popup_text',
        'maintain_text',
        'bancard_text',
        'mercadopago_text',
        'cielo_text',
        'pagseguro_text',
        'pagopar_text',
        'bank_text',
        'pagarme_text',
        'rede_text',
        'page_not_found_text',
        'policy',
        'crow_policy',
        'privacy_policy',
        'vendor_policy'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('generalsetting')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->put('generalsetting_id', $this->generalsetting_id);
        $activity->properties = $activity->properties->put('locale', $this->locale);
    }
}
