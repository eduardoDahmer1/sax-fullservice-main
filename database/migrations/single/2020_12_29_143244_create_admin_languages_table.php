<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_languages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->tinyInteger('is_default')->default(0);
            $table->string('language', 100);
            $table->string('file', 100);
            $table->string('name', 100);
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
        Schema::dropIfExists('admin_languages');
    }
}
