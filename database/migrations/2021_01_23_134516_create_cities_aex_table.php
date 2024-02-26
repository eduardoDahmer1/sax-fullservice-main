<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesAexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities_aex', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo_ciudad');
            $table->string('denominacion');
            $table->string('codigo_departamento');
            $table->string('codigo_pais');
            $table->string('ubicacion_geografica');
            $table->string('departamento_denominacion');
            $table->string('pais_denominacion');
            $table->index('codigo_ciudad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities_aex');
    }
}
