<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesettingTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagesetting_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pagesetting_id')->unsigned();
            $table->string('locale')->index();
            $table->text('contact_success')->nullable();
            $table->text('contact_title')->nullable();
            $table->text('contact_text')->nullable();
            $table->text('side_title')->nullable();
            $table->text('side_text')->nullable();

            $table->unique(['pagesetting_id', 'locale']);
            $table->foreign('pagesetting_id')->references('id')->on('pagesettings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagesetting_translations');
    }
}
