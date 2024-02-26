<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTableAddCityStateCountryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('city_id')->unsigned()->nullable(); 
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
            $table->integer('state_id')->unsigned()->nullable(); 
            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->integer('country_id')->nullable(); 
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['users_city_id_foreign']);
            $table->dropColumn('city_id');
            $table->dropForeign(['users_state_id_foreign']);
            $table->dropColumn('state_id');
            $table->dropForeign(['users_country_id_foreign']);
            $table->dropColumn('country_id');
        });
    }
}
