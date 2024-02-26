<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMelhorenvioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('melhorenvio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('production')->default(false);
            $table->text('token');
            $table->boolean('insurance')->default(false);
            $table->boolean('receipt')->default(false);
            $table->boolean('ownhand')->default(false);
            $table->boolean('collect')->default(false);
            $table->json('selected_services');
            $table->string('from_name');
            $table->string('from_phone');
            $table->string('from_email');
            $table->string('from_document');
            $table->string('from_company_document');
            $table->string('from_state_register');
            $table->string('from_address');
            $table->string('from_number');
            $table->string('from_complement');
            $table->string('from_district');
            $table->string('from_city');
            $table->string('from_state');
            $table->string('from_country');
            $table->string('from_postal_code');
            $table->string('from_note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('melhorenvio');
    }
}
