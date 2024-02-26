<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->tinyInteger('reference_code')->after('show_product_prices');
            $table->string('ref_color')->after('reference_code')->nullable();
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
            $table->dropColumn('reference_code');
            $table->dropColumn('ref_color');
        });
    }
}
