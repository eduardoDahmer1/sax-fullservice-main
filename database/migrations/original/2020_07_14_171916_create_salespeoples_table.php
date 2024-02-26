<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalespeoplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salespeoples', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->index();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('whatsapp');
            $table->string('skype')->nullable();
            $table->string('email')->nullable();
            $table->foreign('category_id')->references('id')->on('salespeople_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salespeoples');
    }
}
