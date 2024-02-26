<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->text('cart');
            $table->string('method')->nullable();
            $table->string('shipping')->nullable();
            $table->string('pickup_location')->nullable();
            $table->string('totalQty', 191);
            $table->float('pay_amount', 10, 0);
            $table->string('txnid')->nullable();
            $table->string('charge_id')->nullable();
            $table->string('order_number');
            $table->string('payment_status');
            $table->string('customer_email');
            $table->string('customer_name');
            $table->string('customer_country', 191);
            $table->string('customer_phone');
            $table->string('customer_address')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('customer_zip')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_country', 191)->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_zip')->nullable();
            $table->text('order_note')->nullable();
            $table->string('coupon_code', 191)->nullable();
            $table->string('coupon_discount', 191)->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'declined', 'on delivery'])->default('pending');
            $table->timestamps();
            $table->string('affilate_user', 191)->nullable();
            $table->string('affilate_charge', 191)->nullable();
            $table->string('currency_sign', 10);
            $table->double('currency_value');
            $table->double('shipping_cost');
            $table->double('packing_cost')->default(0);
            $table->integer('tax');
            $table->tinyInteger('dp')->default(0);
            $table->text('pay_id')->nullable();
            $table->integer('vendor_shipping_id')->default(0);
            $table->integer('vendor_packing_id')->default(0);
            $table->string('shipping_type')->nullable();
            $table->string('packing_type')->nullable();
            $table->string('customer_state')->nullable();
            $table->string('customer_document')->nullable();
            $table->string('customer_complement')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_document')->nullable();
            $table->string('shipping_complement')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
