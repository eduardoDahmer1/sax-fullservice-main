<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrderTrackTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_track_translations', function (Blueprint $table) {
            $table->foreign('order_track_id')->references('id')->on('order_tracks')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_track_translations', function (Blueprint $table) {
            $table->dropForeign('order_track_translations_order_track_id_foreign');
        });
    }
}
