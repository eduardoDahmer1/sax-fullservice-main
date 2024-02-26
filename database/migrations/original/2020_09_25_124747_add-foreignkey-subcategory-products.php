<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddForeignkeySubCategoryProducts extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    public function up()
    {
        DB::statement(
            'UPDATE products
            LEFT JOIN subcategories
            ON products.subcategory_id = subcategories.id
            SET products.subcategory_id = NULL
            WHERE subcategories.id IS NULL;'
        );
        Schema::table('products', function (Blueprint $table) {
            $table->integer('subcategory_id')->index()->change();
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('set null');
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
            $table->dropForeign('products_subcategory_id_index');
            $table->dropIndex('subcategory_id');
            $table->dropColumn('subcategory_id');
        });
    }
}