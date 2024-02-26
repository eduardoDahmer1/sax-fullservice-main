<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeOptionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_option_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_option_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['attribute_option_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_option_translations');
    }
}
