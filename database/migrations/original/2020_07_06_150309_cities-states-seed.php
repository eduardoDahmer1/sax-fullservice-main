<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CitiesStatesSeed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Artisan::call('db:seed', [
            '--class' => StatesTableSeeder::class
        ]);
        Artisan::call('db:seed', [
            '--class' => CitiesTableSeeder::class
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}