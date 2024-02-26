<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddForeignkeyCategoryProducts extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    public function up()
    {
        DB::statement(
            'UPDATE products
            LEFT JOIN categories
            ON products.category_id = categories.id
            SET products.category_id = NULL
            WHERE categories.id IS NULL;'
        );

        Schema::table('products', function (Blueprint $table) {
            $table->integer('category_id')->index()->nullable()->change();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
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
            $table->dropForeign('products_category_id_index');
            $table->dropIndex('category_id');
            $table->dropColumn('category_id');
        });
    }
}