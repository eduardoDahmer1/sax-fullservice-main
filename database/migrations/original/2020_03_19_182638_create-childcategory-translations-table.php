<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildcategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('childcategory_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('childcategory_id');
            $table->string('locale')->index();
            $table->string('name');

            $table->unique(['childcategory_id', 'locale']);
            $table->foreign('childcategory_id')->references('id')->on('childcategories')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('childcategory_translations');
    }
}
