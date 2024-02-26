<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAexPickupFieldsToGeneralsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->string('aex_calle_principal')->after('aex_origin');
            $table->string('aex_calle_transversal')->after('aex_calle_principal');
            $table->string('aex_numero_casa')->after('aex_calle_transversal');
            $table->string('aex_telefono')->after('aex_numero_casa');
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
            $table->dropColumn('aex_calle_principal');
            $table->dropColumn('aex_calle_transversal');
            $table->dropColumn('aex_numero_casa');
            $table->dropColumn('aex_telefono');
        });
    }
}
