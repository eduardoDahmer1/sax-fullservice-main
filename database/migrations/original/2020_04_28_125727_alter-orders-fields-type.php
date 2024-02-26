<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersFieldsType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `orders` MODIFY `customer_state` varchar(255) NULL;');
        DB::statement('ALTER TABLE `orders` MODIFY `customer_cpf` varchar(255) NULL;');
        DB::statement('ALTER TABLE `orders` MODIFY `customer_complement` varchar(255) NULL;');
        DB::statement('ALTER TABLE `orders` MODIFY `shipping_state` varchar(255) NULL;');
        DB::statement('ALTER TABLE `orders` MODIFY `shipping_cpf` varchar(255) NULL;');
        DB::statement('ALTER TABLE `orders` MODIFY `shipping_complement` varchar(255) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
