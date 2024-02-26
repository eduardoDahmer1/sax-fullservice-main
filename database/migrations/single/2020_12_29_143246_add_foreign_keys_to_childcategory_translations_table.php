<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToChildcategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('childcategory_translations', function (Blueprint $table) {
            $table->foreign('childcategory_id')->references('id')->on('childcategories')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('childcategory_translations', function (Blueprint $table) {
            $table->dropForeign('childcategory_translations_childcategory_id_foreign');
        });
    }
}
