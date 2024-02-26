<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToChildcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('childcategories', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('RESTRICT')->onDelete('SET NULL');
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
        Schema::table('childcategories', function (Blueprint $table) {
            $table->dropForeign('childcategories_category_id_foreign');
            $table->dropForeign('childcategories_subcategory_id_foreign');
        });
    }
}
