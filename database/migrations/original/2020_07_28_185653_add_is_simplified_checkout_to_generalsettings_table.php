<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSimplifiedCheckoutToGeneralsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->boolean('is_simplified_checkout')->after('is_cart')->default(true);
            $table->string('simplified_checkout_number')->after('is_simplified_checkout')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn('is_simplified_checkout');
            $table->dropColumn('simplified_checkout_number');
        });
    }
}
