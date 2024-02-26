<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGeneralsettingTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsetting_translations', function (Blueprint $table) {
            $table->foreign('generalsetting_id')->references('id')->on('generalsettings')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
            $table->dropForeign('generalsetting_translations_generalsetting_id_foreign');
        });
    }
}
