<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class AlterProductsAddColorQtyAddColorPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('products', 'color_qty')){
            Schema::table('products', function (Blueprint $table) {
                $table->string('color_qty')->nullable();
            });
        }
        if(!Schema::hasColumn('products', 'color_price')){
            Schema::table('products', function (Blueprint $table) {
                $table->string('color_price')->nullable();
            });
        }
        // Set stock 1 and price 0 - customer needs to update it manually after migration rolls
        // It prevents undefined index offset and some other errors in production environment.
        $products = \DB::select('SELECT * FROM products WHERE color IS NOT NULL');
        foreach($products as $product){
            $regex_color = preg_replace('/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/', '1', $product->color);
            $regex_price = preg_replace('/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/', '0', $product->color);
            \DB::statement("UPDATE products SET color_qty = '$regex_color', color_price = '$regex_price' WHERE id = $product->id");
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('products', 'color_qty')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('color_qty');
            });
        }
        if(Schema::hasColumn('products', 'color_price')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('color_price');
            });
        }
    }
}
