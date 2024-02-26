<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPickupTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pickup_translations', function (Blueprint $table) {
            $table->foreign('pickup_id')->references('id')->on('pickups')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pickup_translations', function (Blueprint $table) {
            $table->dropForeign('pickup_translations_pickup_id_foreign');
        });
    }
}
