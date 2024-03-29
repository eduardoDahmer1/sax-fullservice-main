<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagesettings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contact_email', 191);
            $table->text('street')->nullable();
            $table->text('phone')->nullable();
            $table->text('fax')->nullable();
            $table->text('email')->nullable();
            $table->text('site')->nullable();
            $table->tinyInteger('slider')->default(1);
            $table->tinyInteger('service')->default(1);
            $table->tinyInteger('featured')->default(1);
            $table->tinyInteger('small_banner')->default(1);
            $table->tinyInteger('best')->default(1);
            $table->tinyInteger('top_rated')->default(1);
            $table->tinyInteger('large_banner')->default(1);
            $table->tinyInteger('big')->default(1);
            $table->tinyInteger('hot_sale')->default(1);
            $table->tinyInteger('partners')->default(0);
            $table->tinyInteger('review_blog')->default(1);
            $table->text('best_seller_banner')->nullable();
            $table->text('best_seller_banner_link')->nullable();
            $table->text('big_save_banner')->nullable();
            $table->text('big_save_banner_link')->nullable();
            $table->tinyInteger('bottom_small')->default(0);
            $table->tinyInteger('flash_deal')->default(0);
            $table->text('best_seller_banner1')->nullable();
            $table->text('best_seller_banner_link1')->nullable();
            $table->text('big_save_banner1')->nullable();
            $table->text('big_save_banner_link1')->nullable();
            $table->integer('featured_category')->default(0);
            $table->integer('store_id')->default(1)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagesettings');
    }
}
