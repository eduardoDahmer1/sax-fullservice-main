<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGeneralsettingsAddMercadopagoFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->boolean('is_mercadopago');
            $table->text('mercadopago_text')->nullable();
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
            $table->dropColumn('is_mercadopago');
            $table->dropColumn('mercadopago_text');
        });
    }
}
