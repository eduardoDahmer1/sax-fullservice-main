<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFbpixelEventsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('seotools', function (Blueprint $table) {            
            $table->text('fb_addPayment')->nullable()->default(NULL);
            $table->text('fb_addCart')->nullable()->default(NULL);
            $table->text('fb_addWish')->nullable()->default(NULL);
            $table->text('fb_CompleteReg')->nullable()->default(NULL);
            $table->text('fb_contact')->nullable()->default(NULL);
            $table->text('fb_customizeProd')->nullable()->default(NULL);
            $table->text('fb_Donate')->nullable()->default(NULL);
            $table->text('fb_findLocal')->nullable()->default(NULL);
            $table->text('fb_CheckoutIni')->nullable()->default(NULL);
            $table->text('fb_lead')->nullable()->default(NULL);
            $table->text('fb_purchase')->nullable()->default(NULL);
            $table->text('fb_schedule')->nullable()->default(NULL);
            $table->text('fb_search')->nullable()->default(NULL);
            $table->text('fb_starTrial')->nullable()->default(NULL);
            $table->text('fb_submitApp')->nullable()->default(NULL);
            $table->text('fb_subscribe')->nullable()->default(NULL);
            $table->text('fb_viewContent')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('seotools', function($table) {
            $table->dropColumn('fb_addPayment');
            $table->dropColumn('fb_addCart');
            $table->dropColumn('fb_addWish');
            $table->dropColumn('fb_CompleteReg');
            $table->dropColumn('fb_contact');
            $table->dropColumn('fb_customizeProd');
            $table->dropColumn('fb_Donate');
            $table->dropColumn('fb_findLocal');
            $table->dropColumn('fb_CheckoutIni');
            $table->dropColumn('fb_lead');
            $table->dropColumn('fb_purchase');
            $table->dropColumn('fb_schedule');
            $table->dropColumn('fb_search');
            $table->dropColumn('fb_starTrial');
            $table->dropColumn('fb_submitApp');
            $table->dropColumn('fb_subscribe');
            $table->dropColumn('fb_viewContent');
        });
    }
}
