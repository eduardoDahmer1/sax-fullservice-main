<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->string('subtitle')->nullable();

            $table->unique(['package_id', 'locale']);
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_translations');
    }
}
