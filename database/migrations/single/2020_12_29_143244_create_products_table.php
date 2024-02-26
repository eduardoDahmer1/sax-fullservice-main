<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->nullable();
            $table->enum('product_type', ['normal', 'affiliate'])->default('normal');
            $table->text('affiliate_link')->nullable();
            $table->integer('user_id')->default(0);
            $table->integer('category_id')->nullable()->index();
            $table->integer('subcategory_id')->nullable()->index();
            $table->integer('childcategory_id')->nullable()->index();
            $table->text('attributes')->nullable()->index('attributes');
            $table->text('slug')->nullable();
            $table->string('photo')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('file', 191)->nullable();
            $table->string('size', 191)->nullable();
            $table->string('size_qty', 191)->nullable();
            $table->string('size_price', 191)->nullable();
            $table->text('color')->nullable();
            $table->double('price');
            $table->double('previous_price')->nullable();
            $table->integer('stock')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedInteger('views')->default(0);
            $table->text('colors')->nullable();
            $table->tinyInteger('product_condition')->default(0);
            $table->tinyInteger('is_meta')->default(0);
            $table->string('youtube', 191)->nullable();
            $table->enum('type', ['Physical', 'Digital', 'License']);
            $table->text('license')->nullable();
            $table->text('license_qty')->nullable();
            $table->text('link')->nullable();
            $table->string('platform')->nullable();
            $table->string('region')->nullable();
            $table->string('licence_type')->nullable();
            $table->string('measure', 191)->nullable();
            $table->unsignedTinyInteger('featured')->default(0);
            $table->unsignedTinyInteger('best')->default(0);
            $table->unsignedTinyInteger('top')->default(0);
            $table->unsignedTinyInteger('hot')->default(0);
            $table->unsignedTinyInteger('latest')->default(0);
            $table->unsignedTinyInteger('big')->default(0);
            $table->tinyInteger('trending')->default(0);
            $table->tinyInteger('sale')->default(0);
            $table->timestamps();
            $table->tinyInteger('is_discount')->default(0);
            $table->text('discount_date')->nullable();
            $table->text('whole_sell_qty')->nullable();
            $table->text('whole_sell_discount')->nullable();
            $table->tinyInteger('is_catalog')->default(0);
            $table->integer('catalog_id')->default(0);
            $table->unsignedInteger('brand_id')->nullable()->index('products_brand_id_foreign');
            $table->string('ref_code', 50)->nullable()->index();
            $table->unsignedInteger('ref_code_int')->nullable()->index();
            $table->string('mpn', 50)->nullable();
            $table->tinyInteger('free_shipping')->nullable();
            $table->unsignedInteger('max_quantity')->nullable();
            $table->double('weight')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('length')->nullable();
            $table->string('external_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
