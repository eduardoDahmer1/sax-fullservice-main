<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderTableAddPay42Columns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function(Blueprint $table){
            $table->boolean('is_qrcode')->nullable()->default(false);
            $table->datetime('pay42_due_date')->nullable()->default(false);
            $table->decimal('pay42_total')->nullable()->default(null);
            $table->decimal('pay42_exchange_rate')->nullable()->default(null);
            $table->string('pay42_billet')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function(Blueprint $table){
            $table->dropColumn('is_qrcode');
            $table->dropColumn('pay42_due_date');
            $table->dropColumn('pay42_total');
            $table->dropColumn('pay42_exchange_rate');
            $table->dropColumn('pay42_billet');
        });
    }
}
