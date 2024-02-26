<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends LocalizedModel
{
	public $translatedAttributes = ['text', 'warning_reason'];
	protected $fillable = ['user_id', 'attachments','admin_warning','status'];

	protected $with = ['translations'];

    public function user()
    {
    	return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

}
