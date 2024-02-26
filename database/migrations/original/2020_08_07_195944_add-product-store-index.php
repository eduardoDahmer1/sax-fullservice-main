<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductStoreIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_store', function(Blueprint $table)
        {
            $table->index('product_id');
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
        Schema::table('product_store', function (Blueprint $table)
        {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['store_id']);
        });
    }
}
