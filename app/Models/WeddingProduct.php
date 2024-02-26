<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class WeddingProduct extends Pivot
{
    protected $table = 'wedding_products';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_wedding_product', 'wedding_product_id', 'order_id');
    }
}
