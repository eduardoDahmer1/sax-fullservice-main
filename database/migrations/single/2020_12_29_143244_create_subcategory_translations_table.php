<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcategory_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subcategory_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['subcategory_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subcategory_translations');
    }
}
