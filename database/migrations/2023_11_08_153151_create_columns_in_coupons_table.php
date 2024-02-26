<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('category_id')->nullable();
            $table->unsignedInteger('brand_id')->nullable();
    
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('category_id');
            $table->dropColumn('brand_id');
        });
    }
};
