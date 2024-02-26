<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersAddMoreaddressFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_address_number')->nullable();
            $table->string('customer_district')->nullable();

            $table->string('shipping_address_number')->nullable();
            $table->string('shipping_district')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('customer_address_number');
            $table->dropColumn('customer_district');

            $table->dropColumn('shipping_address_number');
            $table->dropColumn('shipping_district');
        });
    }
}
