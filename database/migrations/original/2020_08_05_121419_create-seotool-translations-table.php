<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeotoolTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seotool_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('seotool_id')->unsigned();
            $table->string('locale')->index();
            $table->text('meta_keys')->nullable();

            $table->unique(['seotool_id', 'locale']);
            $table->foreign('seotool_id')->references('id')->on('seotools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seotool_translations');
    }
}
