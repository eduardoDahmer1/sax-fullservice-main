<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsStateCpfComplementOnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('customer_state');
            $table->text('customer_cpf');
            $table->text('customer_complement');

            $table->text('shipping_state');
            $table->text('shipping_cpf');
            $table->text('shipping_complement');
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
            $table->dropColumn('customer_state');
            $table->dropColumn('customer_cpf');
            $table->dropColumn('customer_complement');

            $table->dropColumn('shipping_state');
            $table->dropColumn('shipping_cpf');
            $table->dropColumn('shipping_complement');
        });
    }
}
