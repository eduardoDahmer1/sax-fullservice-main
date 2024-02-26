<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductsTableMoveNewTranslatableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // get language 1
        $lang1 = DB::table('languages')->where('id', 1)->first();

        // We update the old attributes into the existing translation table: 
        DB::statement("update product_translations a
            inner join products b on a.product_id = b.id
            set a.ship = b.ship,
                a.policy = b.policy,
                a.meta_tag = b.meta_tag,
                a.meta_description = b.meta_description,
                a.features = b.features,
                a.tags = b.tags
            where a.locale = '{$lang1->locale}'");

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('ship');
            $table->dropColumn('policy');
            $table->dropColumn('meta_tag');
            $table->dropColumn('meta_description');
            $table->dropColumn('features');
            $table->dropColumn('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //it is not possible to revert. Old attributes would be nullable
    }
}
