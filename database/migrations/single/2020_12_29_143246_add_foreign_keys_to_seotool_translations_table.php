<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSeotoolTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seotool_translations', function (Blueprint $table) {
            $table->foreign('seotool_id')->references('id')->on('seotools')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seotool_translations', function (Blueprint $table) {
            $table->dropForeign('seotool_translations_seotool_id_foreign');
        });
    }
}
