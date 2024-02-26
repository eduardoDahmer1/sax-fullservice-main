<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAttributeOptionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attribute_option_translations', function (Blueprint $table) {
            $table->foreign('attribute_option_id')->references('id')->on('attribute_options')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attribute_option_translations', function (Blueprint $table) {
            $table->dropForeign('attribute_option_translations_attribute_option_id_foreign');
        });
    }
}
