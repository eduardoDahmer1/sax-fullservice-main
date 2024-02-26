<?php

namespace App\Observers;

use App\Models\Brand;
use App\Models\Product;

class BrandObserver
{
    /**
     * Handle the Brand "saved" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function saved(Brand $brand)
    {
        // disable all products from specific brand
        if ($brand->status == 0) {
            Product::where('brand_id', $brand->id)->update(['status' => 0]);
        }
    }
}
