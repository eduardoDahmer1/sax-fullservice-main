<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddForeignkeyChildcategoryProducts extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            'UPDATE products
            LEFT JOIN childcategories
            ON products.childcategory_id = childcategories.id
            SET products.childcategory_id = NULL
            WHERE childcategories.id IS NULL;'
        );
        Schema::table('products', function(Blueprint $table){
            $table->integer('childcategory_id')->index()->change();
            $table->foreign('childcategory_id')->references('id')->on('childcategories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_childcategory_id_index');
            $table->dropIndex('childcategory_id');
            $table->dropColumn('childcategory_id');
        });
    }
}