<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGsAddCorreiosFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->boolean('is_correios');
            $table->integer('correios_cep')->nullable();
            $table->integer('correios_width')->nullable();
            $table->integer('correios_height')->nullable();
            $table->integer('correios_length')->nullable();
            $table->double('correios_weight', 8, 2)->nullable();
       }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn('is_correios');
            $table->dropColumn('correios_cep');
            $table->dropColumn('correios_width');
            $table->dropColumn('correios_height');
            $table->dropColumn('correios_length');
            $table->dropColumn('correios_weight');
       });
    }
}
