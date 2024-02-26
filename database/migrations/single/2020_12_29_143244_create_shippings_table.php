<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->default(0);
            $table->double('price')->nullable();
            $table->enum('shipping_type', ['Free', 'Fixed Price', 'Fixed Weight', 'Free Location', 'Fixed Price Location', 'Fixed Weight Location']);
            $table->double('price_free_shipping')->nullable();
            $table->double('price_per_kilo')->nullable();
            $table->integer('status');
            $table->tinyInteger('local_shipping');
            $table->unsignedInteger('city_id')->nullable()->index('shippings_city_id_foreign');
            $table->unsignedInteger('state_id')->nullable()->index('shippings_state_id_foreign');
            $table->integer('country_id')->nullable()->index('shippings_country_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shippings');
    }
}
