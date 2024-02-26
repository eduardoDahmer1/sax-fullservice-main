<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProductsAddMercadolivreWarrantyAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('mercadolivre_warranty_type_id')->nullable();
            $table->string('mercadolivre_warranty_type_name')->nullable();
            $table->string('mercadolivre_warranty_time')->nullable();
            $table->string('mercadolivre_warranty_time_unit')->nullable();
            $table->boolean('mercadolivre_without_warranty')->default(false);
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
            $table->dropColumn('mercadolivre_warranty_type_id');
            $table->dropColumn('mercadolivre_warranty_type_name');
            $table->dropColumn('mercadolivre_warranty_time');
            $table->dropColumn('mercadolivre_warranty_time_unit');
            $table->dropColumn('mercadolivre_without_warranty');
        });
    }
}
