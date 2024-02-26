<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAttributeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attribute_translations', function (Blueprint $table) {
            $table->foreign('attribute_id')->references('id')->on('attributes')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attribute_translations', function (Blueprint $table) {
            $table->dropForeign('attribute_translations_attribute_id_foreign');
        });
    }
}
