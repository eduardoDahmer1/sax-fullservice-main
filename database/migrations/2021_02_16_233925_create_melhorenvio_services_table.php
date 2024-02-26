<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMelhorenvioServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('melhorenvio_services', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('company_id');
            $table->string('name', 255);            
            $table->string('type', 255);
            $table->double('insurance_min');
            $table->double('insurance_max');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('melhorenvio_services');
    }
}
