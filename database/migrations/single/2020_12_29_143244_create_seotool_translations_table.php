<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->unsignedInteger('seotool_id');
            $table->string('locale')->index();
            $table->text('meta_keys')->nullable();
            $table->text('meta_description')->nullable();
            $table->unique(['seotool_id', 'locale']);
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
