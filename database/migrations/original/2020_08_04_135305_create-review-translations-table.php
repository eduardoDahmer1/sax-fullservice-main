<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('review_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->string('subtitle');
            $table->text('details');

            $table->unique(['review_id', 'locale']);
            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review_translations');
    }
}
