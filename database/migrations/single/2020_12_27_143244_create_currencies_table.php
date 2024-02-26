<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 30);
            $table->string('sign', 10);
            $table->double('value');
            $table->tinyInteger('is_default')->default(0);
            $table->char('decimal_separator', 1)->default('.');
            $table->char('thousands_separator', 1)->nullable()->default('');
            $table->integer('decimal_digits')->default(2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
