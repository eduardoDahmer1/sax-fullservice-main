<?php

use App\Traits\MigrationIndex;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCustomprodOnDelete extends Migration
{
    use MigrationIndex;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::disableForeignKeyConstraints();
        Schema::table('custom_prod', function (Blueprint $table) {
            $table->dropForeign('custom_prod_product_id_foreign');
        });
        $this->_dropIndexIfExist('custom_prod', 'custom_prod_product_id_index');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
