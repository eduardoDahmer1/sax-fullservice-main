<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductTranslationsTableAddMoreFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_translations', function (Blueprint $table) {
            $table->string('ship')->nullable();
            $table->text('policy')->nullable();
            $table->text('meta_tag')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('features')->nullable();
            $table->string('tags')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_translations', function (Blueprint $table) {
            $table->dropColumn('ship');
            $table->dropColumn('policy');
            $table->dropColumn('meta_tag');
            $table->dropColumn('meta_description');
            $table->dropColumn('features');
            $table->dropColumn('tags');
        });
    }
}
