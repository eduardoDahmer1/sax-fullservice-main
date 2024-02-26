<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShippingFieldsType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('UPDATE `shippings` SET `shipping_type`="Free";');
        DB::statement('ALTER TABLE `shippings` MODIFY `shipping_type` ENUM("Free", "Fixed Price", "Fixed Weight", "Free Location", "Fixed Price Location", "Fixed Weight Location") NOT NULL;');
        DB::statement('ALTER TABLE `shippings` MODIFY `price` double NULL;');
        DB::statement('ALTER TABLE `shippings` MODIFY `price_per_kilo` double NULL;');
        DB::statement('ALTER TABLE `shippings` MODIFY `price_free_shipping` double NULL;');
        
        Schema::table('shippings', function (Blueprint $table) {
            $table->boolean('local_shipping');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shippings', function (Blueprint $table) {
            $table->dropColumn('local_shipping');
        });
    }
}
