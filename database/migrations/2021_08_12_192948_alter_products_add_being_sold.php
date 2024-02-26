<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsAddBeingSold extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('products', 'being_sold')) {
            Schema::table('products', function(Blueprint $table){
                $table->boolean('being_sold')->default(0);
            });
        }
        if(!Schema::hasColumn('products', 'vendor_min_price')) {
            Schema::table('products', function(Blueprint $table){
                $table->double('vendor_min_price')->default(0);
            });
        }
        if(!Schema::hasColumn('products', 'vendor_max_price')) {
            Schema::table('products', function(Blueprint $table){
                $table->double('vendor_max_price')->default(0);
            });
        }
        // Update the fields above at existing products
        if(config("features.marketplace")){
            foreach(\App\Models\Product::all() as $prod){
                $base_prod = \App\Models\Product::isActive()
                ->select('products.*', DB::raw('count(*) as count'))
                ->where('slug', $prod->slug)->groupBy('mpn')->groupBy('category_id')->groupBy('brand_id')->first();
                if($base_prod){
                    if($base_prod->count > 1){
                        if($prod->user_id == 0){
                            $prod->being_sold = 1;
                            $prod->update();
                        }
                        $max = $base_prod->vendor_max_price;
                        $min = $base_prod->vendor_min_price;
                        foreach(\App\Models\Product::where('slug', $base_prod->slug)->where('user_id', '!=', 0)->get() as $v_prod){
                            // Price higher than max price
                            if($v_prod->price >= $max){
                                $max = $v_prod->price;
                            }
                            // Price lower than min price
                            if($v_prod->price <= $min){
                                $min = $v_prod->price;
                            }
                            // Price between both but there's no such price at Database (just set current price as min/max)
                            // Prevents an old price to be related with admin product if there's no Vendor product with that price anymore.
                            if($v_prod->price >= $min && $v_prod->price <= $max){
                                if(!\App\Models\Product::where('user_id', '!=', 0)->where('slug', $base_prod->slug)->where('price', $min)->first()){
                                    $min = $v_prod->price;
                                }
                                if(!\App\Models\Product::where('user_id', '!=', 0)->where('slug', $base_prod->slug)->where('price', $max)->first()){
                                    $max = $v_prod->price;
                                }
                            }
                            $base_prod->vendor_min_price = $min;
                            $base_prod->vendor_max_price = $max;
                            $base_prod->update();
                        }
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('products', 'being_sold')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('being_sold');
            });
        }
        if(Schema::hasColumn('products', 'vendor_min_price')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('vendor_min_price');
            });
        }
        if(Schema::hasColumn('products', 'vendor_max_price')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('vendor_max_price');
            });
        }
    }
}
