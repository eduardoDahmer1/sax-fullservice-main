<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shippings', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('state_id')->references('id')->on('states')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shippings', function (Blueprint $table) {
            $table->dropForeign('shippings_city_id_foreign');
            $table->dropForeign('shippings_country_id_foreign');
            $table->dropForeign('shippings_state_id_foreign');
        });
    }
}
