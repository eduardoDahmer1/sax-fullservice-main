<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterProductsTextFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            DB::statement('ALTER TABLE `products` MODIFY `ref_code` varchar(50) NULL;');
            DB::statement('ALTER TABLE `products` MODIFY `mpn` varchar(50) NULL;');
            DB::statement('ALTER TABLE `products` MODIFY `free_shipping` tinyint NULL;');
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
