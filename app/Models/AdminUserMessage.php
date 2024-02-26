<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AdminUserMessage extends CachedModel
{
    use LogsActivity;


    protected $fillable = ['conversation_id','message','user_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('admin_messages')
            ->logFillable()
            ->logOnlyDirty();
    }

    public function conversation()
    {
        return $this->belongsTo('App\Models\AdminUserConversation', 'conversation_id')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }
}
