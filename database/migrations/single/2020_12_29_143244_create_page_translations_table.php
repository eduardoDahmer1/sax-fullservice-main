<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('details');
            $table->text('meta_tag')->nullable();
            $table->text('meta_description')->nullable();
            $table->unique(['page_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_translations');
    }
}
