<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends CachedModel
{
    public function order()
    {
    	return $this->belongsTo('App\Models\Order')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

    public function receipt()
    {
    	return $this->belongsTo('App\Models\Order', 'receipt')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\User','vendor_id')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

    public function product()
    {
    	return $this->belongsTo('App\Models\Product')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

    public function conversation()
    {
        return $this->belongsTo('App\Models\AdminUserConversation')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeNotRead($query)
    {
        return $query->where('is_read', false);
    }

    public static function count(){
        return self::notRead()->orderBy('id', 'DESC')->count();
    }

}
