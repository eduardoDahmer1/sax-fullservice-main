<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSliderStoreIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('slider_store', function(Blueprint $table)
        {
            $table->index('slider_id');
            $table->index('store_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('slider_store', function (Blueprint $table)
        {
            $table->dropIndex(['slider_id']);
            $table->dropIndex(['store_id']);
        });
    }
}
