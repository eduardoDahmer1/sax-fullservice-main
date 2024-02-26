<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomProd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('custom_prod')){
            Schema::create('custom_prod', function (Blueprint $table) {
                $table->increments('id');
                $table->string('customizable_name')->nullable();
                $table->string('customizable_logo')->nullable();
                $table->integer('product_id');
                $table->integer('order_id');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_prod', function (Blueprint $table) {
            $table->dropIfExists('custom_prod');
        });
    }
}