<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaDescriptionFieldToSeotoolTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seotool_translations', function (Blueprint $table) {
            $table->text('meta_description')->nullable();
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
            $table->dropColumn('meta_description');
        });
    }
}
