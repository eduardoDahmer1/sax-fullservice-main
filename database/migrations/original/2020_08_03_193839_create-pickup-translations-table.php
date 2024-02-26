<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickupTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pickup_id')->unsigned();
            $table->string('locale')->index();
            $table->string('location');

            $table->unique(['pickup_id', 'locale']);
            $table->foreign('pickup_id')->references('id')->on('pickups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pickup_translations');
    }
}
