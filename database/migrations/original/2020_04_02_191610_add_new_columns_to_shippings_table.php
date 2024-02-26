<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shippings', function (Blueprint $table) {
            $table->string('shipping_type');
            $table->double('price_free_shipping', 8, 2);
            $table->double('price_per_kilo', 8, 2);
            $table->integer('status');	
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shippings', function (Blueprint $table) {
            $table->dropColumn('shipping_type');
            $table->dropColumn('price_free_shipping');
            $table->dropColumn('price_per_kilo');
            $table->dropColumn('status');
        });
    }
}
