<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCurrenciesAddDecimalFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->char('decimal_separator',1)->default(".");
            $table->char('thousands_separator',1)->nullable()->default('');
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
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn('decimal_separator');
            $table->dropColumn('thousands_separator');
            $table->dropColumn('decimal_digits');
        });
    }
}
