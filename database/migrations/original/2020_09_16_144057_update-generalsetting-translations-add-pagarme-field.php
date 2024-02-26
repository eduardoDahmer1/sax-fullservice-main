<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGeneralsettingTranslationsAddPagarmeField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsetting_translations', function (Blueprint $table) {
            $table->text('pagarme_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generalsetting_translations', function (Blueprint $table) {
            $table->dropcolumn('pagarme_text');
        });
    }
}
