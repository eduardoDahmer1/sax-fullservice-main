<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('document')->nullable();
            $table->string('photo', 191)->nullable();
            $table->string('zip', 191)->nullable();
            $table->string('city', 191)->nullable();
            $table->string('state')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('address', 191)->nullable();
            $table->string('address_number')->nullable();
            $table->string('complement')->nullable();
            $table->string('district')->nullable();
            $table->string('phone', 191)->nullable();
            $table->string('fax', 191)->nullable();
            $table->string('email', 191)->unique();
            $table->string('password', 191)->nullable();
            $table->string('password_reset')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->tinyInteger('is_provider')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->text('verification_link')->nullable();
            $table->enum('email_verified', ['Yes', 'No'])->default('No');
            $table->text('affilate_code')->nullable();
            $table->double('affilate_income')->default(0);
            $table->text('shop_name')->nullable();
            $table->text('owner_name')->nullable();
            $table->text('shop_number')->nullable();
            $table->text('shop_address')->nullable();
            $table->text('reg_number')->nullable();
            $table->string('shop_image', 191)->nullable();
            $table->text('f_url')->nullable();
            $table->text('g_url')->nullable();
            $table->text('t_url')->nullable();
            $table->text('l_url')->nullable();
            $table->tinyInteger('is_vendor')->default(0);
            $table->tinyInteger('f_check')->default(0);
            $table->tinyInteger('g_check')->default(0);
            $table->tinyInteger('t_check')->default(0);
            $table->tinyInteger('l_check')->default(0);
            $table->tinyInteger('mail_sent')->default(0);
            $table->double('shipping_cost')->default(0);
            $table->double('current_balance')->default(0);
            $table->date('date')->nullable();
            $table->tinyInteger('ban')->default(0);
            $table->unsignedInteger('city_id')->nullable()->index('users_city_id_foreign');
            $table->unsignedInteger('state_id')->nullable()->index('users_state_id_foreign');
            $table->integer('country_id')->nullable()->index('users_country_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
