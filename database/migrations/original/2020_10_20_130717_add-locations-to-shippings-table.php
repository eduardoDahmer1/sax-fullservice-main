<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationsToShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shippings', function (Blueprint $table) {
            $table->integer('city_id')->unsigned()->nullable();
            $table->integer('state_id')->unsigned()->nullable();
            $table->integer('country_id')->nullable();

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
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
            $table->dropForeign('shippings_state_id_foreign');
            $table->dropForeign('shippings_country_id_foreign');

            $table->dropColumn('city_id');
            $table->dropColumn('state_id');
            $table->dropColumn('country_id');
        });
    }
}
