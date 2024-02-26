<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPagesettingTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagesetting_translations', function (Blueprint $table) {
            $table->foreign('pagesetting_id')->references('id')->on('pagesettings')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagesetting_translations', function (Blueprint $table) {
            $table->dropForeign('pagesetting_translations_pagesetting_id_foreign');
        });
    }
}
