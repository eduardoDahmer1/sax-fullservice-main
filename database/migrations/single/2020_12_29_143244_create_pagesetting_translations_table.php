<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->unsignedInteger('pagesetting_id');
            $table->string('locale')->index();
            $table->text('contact_success')->nullable();
            $table->text('contact_title')->nullable();
            $table->text('contact_text')->nullable();
            $table->text('side_title')->nullable();
            $table->text('side_text')->nullable();
            $table->unique(['pagesetting_id', 'locale']);
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
