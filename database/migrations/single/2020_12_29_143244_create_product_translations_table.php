<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->mediumText('details')->nullable();
            $table->string('ship')->nullable();
            $table->text('policy')->nullable();
            $table->text('meta_tag')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('features')->nullable();
            $table->string('tags')->nullable();
            $table->unique(['product_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_translations');
    }
}
