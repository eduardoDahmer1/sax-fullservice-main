<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_product', function (Blueprint $table) {

            $table->id();
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('pickup_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('pickup_id')->references('id')->on('pickups')->onDelete('cascade');
            $table->unsignedInteger('stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void 
     */
    public function down()
    {
        Schema::dropIfExists('pickup_product');
    }
};
