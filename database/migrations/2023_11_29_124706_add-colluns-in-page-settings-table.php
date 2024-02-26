<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagesettings', function (Blueprint $table) {
            $table->text('banner_search1')->nullable();
            $table->text('banner_search2')->nullable();
            $table->text('banner_search3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagesettings', function (Blueprint $table) {
            $table->dropColumn('banner_search1');
            $table->dropColumn('banner_search2');
            $table->dropColumn('banner_search3');
        });
    }
};
