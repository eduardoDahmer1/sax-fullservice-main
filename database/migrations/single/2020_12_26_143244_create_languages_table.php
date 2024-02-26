<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->tinyInteger('is_default')->default(0);
            $table->string('language', 100)->nullable();
            $table->string('file', 100)->nullable();
            $table->string('locale');
            $table->string('file_extras')->nullable();
            $table->string('extras_name')->nullable();
            $table->tinyInteger('rtl')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
