<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGeneralsettingsAddPagoparFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->boolean('is_pagopar');
            $table->text('pagopar_text')->nullable();
            $table->text('pagopar_public_key')->nullable();
            $table->text('pagopar_private_key')->nullable();
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
            $table->dropColumn('is_pagopar');
            $table->dropColumn('pagopar_text');
            $table->dropColumn('pagopar_public_key');
            $table->dropColumn('pagopar_private_key');
        });
    }
}
