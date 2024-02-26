<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRedeFieldsToGeneralsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->boolean('is_rede');
            $table->boolean('is_rede_sandbox');
            $table->text('rede_token')->nullable();
            $table->text('rede_pv')->nullable();
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
            $table->dropColumn('is_rede');
            $table->dropColumn('is_rede_sandbox');
            $table->dropColumn('rede_token');
            $table->dropColumn('rede_pv');
        });
    }
}
