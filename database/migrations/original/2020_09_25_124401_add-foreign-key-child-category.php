<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyChildCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('childcategories', function (Blueprint $table) {
            $table->integer('subcategory_id')->index()->change();
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
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
            $table->dropForeign('childcategories_subcategory_id_index');
            $table->dropIndex('subcategory_id');
            $table->dropColumn('subcategory_id');
        });
    }
}