<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartabandonmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_abandonments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable()->index('cart_abandonments_user_id_foreign');
            $table->mediumtext('temp_cart');
            $table->boolean('email_sent')->default(false);
            $table->timestamps();
        });
        Schema::table('cart_abandonments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_abandonments');
    }
}
