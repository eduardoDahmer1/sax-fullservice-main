<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannerStoreIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banner_store', function(Blueprint $table)
        {
            $table->index('banner_id');
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
        Schema::table('banner_store', function (Blueprint $table)
        {
            $table->dropIndex(['banner_id']);
            $table->dropIndex(['store_id']);
        });
    }
}
