<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyAttrOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attribute_options', function (Blueprint $table) {
            $table->integer('attribute_id')->index()->change();
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attribute_options', function (Blueprint $table) {
            $table->dropForeign('attribute_options_attribute_id_index');
            $table->dropIndex('attribute_id');
            $table->dropColumn('attribute_id');
        });
    }
}