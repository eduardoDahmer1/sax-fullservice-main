<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSubcategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subcategory_translations', function (Blueprint $table) {
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subcategory_translations', function (Blueprint $table) {
            $table->dropForeign('subcategory_translations_subcategory_id_foreign');
        });
    }
}
