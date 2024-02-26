<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSeotoolsDropFbPixelColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('seotools', 'fb_addPayment')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_addPayment');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_addCart')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_addCart');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_addWish')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_addWish');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_CompleteReg')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_CompleteReg');
            });
        }

        if(Schema::hasColumn('seotools', 'fb_contact')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_contact');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_customizeProd')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_customizeProd');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_Donate')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_Donate');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_findLocal')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_findLocal');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_CheckoutIni')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_CheckoutIni');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_lead')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_lead');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_purchase')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_purchase');
            });
        }

        if(Schema::hasColumn('seotools', 'fb_schedule')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_schedule');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_search')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_search');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_starTrial')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_starTrial');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_submitApp')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_submitApp');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_subscribe')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_subscribe');
            });
        }
        if(Schema::hasColumn('seotools', 'fb_viewContent')){
            Schema::table('seotools', function (Blueprint $table) {
                $table->dropColumn('fb_viewContent');
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
        //
    }
}
