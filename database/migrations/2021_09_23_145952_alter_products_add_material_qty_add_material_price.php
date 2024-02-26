<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsAddMaterialQtyAddMaterialPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('products', 'material_qty')){
            Schema::table('products', function (Blueprint $table) {
                $table->string('material_qty')->nullable()->default(null);;
            });
        }
        if(!Schema::hasColumn('products', 'material_price')){
            Schema::table('products', function (Blueprint $table) {
                $table->string('material_price')->nullable()->default(null);;
            });
        }
        // Set stock 1 and price 0 - customer needs to update it manually after migration rolls
        // It prevents undefined index offset and some other errors in production environment.
        $products = \DB::select('SELECT * FROM products WHERE material IS NOT NULL');
        foreach($products as $product){
            for($i = 0; $i < count(explode(",",$product->material)); $i++) {
                $material_qty[$i] = 1;
                $material_price[$i] = 0;
            }
            $stock = count(explode(",",$product->material));
            $qty = implode(",", $material_qty);
            $price = implode(",", $material_price);
            \DB::statement("UPDATE products SET material_qty = '$qty', material_price = '$price', stock = '$stock' WHERE id = $product->id");
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('products', 'material_qty')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('material_qty');
            });
        }
        if(Schema::hasColumn('products', 'material_price')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('material_price');
            });
        }
    }
}
