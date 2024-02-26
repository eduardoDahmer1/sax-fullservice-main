<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipping_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->string('subtitle')->nullable();

            $table->unique(['shipping_id', 'locale']);
            $table->foreign('shipping_id')->references('id')->on('shippings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_translations');
    }
}
