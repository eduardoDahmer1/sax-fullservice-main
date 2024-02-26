<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSubcategoriesAddForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            'DELETE subcategories  from subcategories
            LEFT JOIN categories
            ON subcategories.category_id = categories.id
            WHERE categories.id IS NULL;'
        );

        Schema::table('subcategories', function (Blueprint $table) {
            $table->integer('category_id')->index()->change();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropForeign('subcategories_category_id_index');
            $table->dropIndex('category_id');
            $table->dropColumn('category_id');
        });
    }
}