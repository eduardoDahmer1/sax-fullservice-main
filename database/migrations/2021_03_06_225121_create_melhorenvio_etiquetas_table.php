<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMelhorenvioEtiquetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('melhorenvio_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->nullable();
            $table->string('protocol', 255)->nullable();
            $table->integer('service_id')->nullable();
            $table->integer('agency_id')->nullable();
            $table->double('price')->nullable();
            $table->string('status', 255)->nullable();
            $table->string('authorization_code', 255)->nullable();
            $table->string('tracking', 255)->nullable();
            $table->integer('order_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('melhorenvio_requests');
    }
}
