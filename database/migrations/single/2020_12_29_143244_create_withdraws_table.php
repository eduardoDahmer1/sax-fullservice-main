<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->string('method')->nullable();
            $table->string('acc_email')->nullable();
            $table->string('iban')->nullable();
            $table->string('country')->nullable();
            $table->string('acc_name')->nullable();
            $table->text('address')->nullable();
            $table->string('swift')->nullable();
            $table->text('reference')->nullable();
            $table->float('amount', 10, 0)->nullable();
            $table->float('fee', 10, 0)->nullable()->default(0);
            $table->timestamps();
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->enum('type', ['user', 'vendor']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraws');
    }
}
