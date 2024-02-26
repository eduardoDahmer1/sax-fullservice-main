<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShippingTypeFromShippings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE shippings MODIFY `shipping_type` ENUM("Free", "Fixed Price", "Fixed Weight", "Free Location", "Fixed Price Location", "Fixed Weight Location", "Percentage Price") NOT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `shippings` MODIFY `shipping_type` ENUM("Free", "Fixed Price", "Fixed Weight", "Free Location", "Fixed Price Location", "Fixed Weight Location") NOT NULL;');
    }
}
