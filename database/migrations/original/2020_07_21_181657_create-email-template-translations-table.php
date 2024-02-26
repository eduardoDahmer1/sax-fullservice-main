<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_template_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('email_template_id');
            $table->string('locale')->index();
            $table->string('email_subject');
            $table->text('email_body');

            $table->unique(['email_template_id', 'locale']);
            $table->foreign('email_template_id')->references('id')->on('email_templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_template_translations');
    }
}
