<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGeneralSettingsAddCieloFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->boolean('is_cielo');
            $table->text('cielo_text')->nullable();
            $table->text('cielo_merchantid')->nullable();
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
            $table->dropColumn('is_cielo');
            $table->dropColumn('cielo_text');
            $table->dropColumn('cielo_merchantid');
        });
    }
}
