<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAexToGeneralsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->boolean('is_aex')->default(false);
            $table->string('aex_url', 255);
            $table->string('aex_public', 255);
            $table->string('aex_private', 255);
            $table->string('aex_origin', 255);
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
            $table->dropColumn('is_aex');
            $table->dropColumn('aex_url');
            $table->dropColumn('aex_public');
            $table->dropColumn('aex_private');
            $table->dropColumn('aex_origin');
        });
    }
}
