<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePaymentFieldsFromWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdraws', function (Blueprint $table) {
            $table->dropColumn('method');
            $table->dropColumn('acc_email');
            $table->dropColumn('iban');
            $table->dropColumn('country');
            $table->dropColumn('acc_name');
            $table->dropColumn('address');
            $table->dropColumn('swift');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdraws', function (Blueprint $table) {
            $table->string('method')->nullable();
            $table->string('acc_email')->nullable();
            $table->string('iban')->nullable();
            $table->string('country')->nullable();
            $table->string('acc_name')->nullable();
            $table->text('address')->nullable();
            $table->string('swift')->nullable();
        });
    }
}
