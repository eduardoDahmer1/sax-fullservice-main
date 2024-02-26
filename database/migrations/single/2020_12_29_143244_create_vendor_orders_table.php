<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('order_id');
            $table->integer('qty');
            $table->double('price');
            $table->string('order_number', 191);
            $table->enum('status', ['pending', 'processing', 'completed', 'declined', 'on delivery'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_orders');
    }
}
