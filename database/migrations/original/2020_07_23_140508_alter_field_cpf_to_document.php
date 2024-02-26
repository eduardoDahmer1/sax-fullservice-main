<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldCpfToDocument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `users` CHANGE `cpf` `document` VARCHAR(255) NULL;');

        DB::statement('ALTER TABLE `orders` CHANGE `customer_cpf` `customer_document` VARCHAR(255) NULL;');
        DB::statement('ALTER TABLE `orders` CHANGE `shipping_cpf` `shipping_document` VARCHAR(255) NULL;');
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
