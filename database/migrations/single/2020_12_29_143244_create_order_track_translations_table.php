<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTrackTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_track_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_track_id');
            $table->string('locale')->index();
            $table->text('title')->nullable();
            $table->text('text')->nullable();
            $table->unique(['order_track_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_track_translations');
    }
}
