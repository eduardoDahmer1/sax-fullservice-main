<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMercadolivreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercadolivre', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('authorization_code')->nullable();
            $table->string('access_token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('redirect_uri')->nullable();
            $table->timestamps();
        });

        DB::table('mercadolivre')->insert([
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mercadolivre');
    }
}
