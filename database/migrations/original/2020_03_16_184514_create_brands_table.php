<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('id');               //Id
            $table->string('name');                 //Name
            $table->string('slug');                 //Slug
            $table->string('image')->nullable();    //Image
            $table->integer('status')->default(0);  //Status
            $table->integer('partner')->nullable(); //Partner
            $table->integer('order')->nullable();   //Order
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands');
    }
}
