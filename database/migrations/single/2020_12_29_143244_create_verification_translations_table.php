<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('verification_id');
            $table->string('locale')->index();
            $table->text('text')->nullable();
            $table->text('warning_reason')->nullable();
            $table->unique(['verification_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification_translations');
    }
}
