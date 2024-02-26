<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaypalFieldsGs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->boolean('is_paypal');
            $table->boolean('is_paypal_sandbox');
            $table->text('paypal_secret')->nullable();
            $table->text('paypal_client_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumn('is_paypal');
        Schema::dropColumn('is_paypal_sandbox');
        Schema::dropColumn('paypal_secret');
        Schema::dropColumn('paypal_client_id');
    }
}
