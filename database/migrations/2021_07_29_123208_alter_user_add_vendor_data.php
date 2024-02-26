<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserAddVendorData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('users', 'vendor_corporate_name')) {
            Schema::table('users', function(Blueprint $table){
                $table->string('vendor_corporate_name')->nullable();
            });
        }
        if(!Schema::hasColumn('users', 'vendor_document')) {
            Schema::table('users', function(Blueprint $table){
                $table->string('vendor_document')->nullable();
            });
        }
        if(!Schema::hasColumn('users', 'vendor_phone')) {
            Schema::table('users', function(Blueprint $table){
                $table->string('vendor_phone')->nullable();
            });
        }
        if(!Schema::hasColumn('users', 'vendor_opening_hours')) {
            Schema::table('users', function(Blueprint $table){
                $table->string('vendor_opening_hours')->nullable();
            });
        }
        if(!Schema::hasColumn('users', 'vendor_payment_methods')) {
            Schema::table('users', function(Blueprint $table){
                $table->string('vendor_payment_methods')->nullable();
            });
        }
        if(!Schema::hasColumn('users', 'vendor_delivery_info')) {
            Schema::table('users', function(Blueprint $table){
                $table->string('vendor_delivery_info')->nullable();
            });
        }
        if(!Schema::hasColumn('users', 'vendor_map_embed')) {
            Schema::table('users', function(Blueprint $table){
                $table->text('vendor_map_embed')->nullable();
            });
        }
        if(!Schema::hasColumn('users', 'shop_details')) {
            Schema::table('users', function(Blueprint $table){
                $table->text('shop_details')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('users', 'vendor_corporate_name')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('vendor_corporate_name');
            });
        }
        if(Schema::hasColumn('users', 'vendor_document')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('vendor_document');
            });
        }
        if(Schema::hasColumn('users', 'vendor_phone')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('vendor_phone');
            });
        }
        if(Schema::hasColumn('users', 'vendor_opening_hours')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('vendor_opening_hours');
            });
        }
        if(Schema::hasColumn('users', 'vendor_payment_methods')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('vendor_payment_methods');
            });
        }
        if(Schema::hasColumn('users', 'vendor_map_embed')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('vendor_map_embed');
            });
        }
        if(Schema::hasColumn('users', 'shop_details')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('shop_details');
            });
        }
    }
}
