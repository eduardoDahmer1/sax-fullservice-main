<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCouponsChangeMinMaxValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('coupons', 'maximum_value'))
        {
            Schema::table('coupons', function (Blueprint $table)
            {
                $table->dropColumn('maximum_value');
                $table->dropColumn('minimum_value');
            });
        }
        Schema::table('coupons', function (Blueprint $table) {
            
        $table->decimal('minimum_value',10,2)->nullable()->default(null);
        $table->decimal('maximum_value',10,2)->nullable()->default(null);
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('maximum_value');
            $table->dropColumn('minimum_value');
        });
    }
}
