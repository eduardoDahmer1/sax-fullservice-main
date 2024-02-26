<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGsAddPaghiperPix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('generalsettings', 'is_paghiper_pix')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->boolean('is_paghiper_pix')->nullable()->default(false);
            });
        }
        if(!Schema::hasColumn('generalsettings', 'paghiper_pix_days_due_date')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->integer('paghiper_pix_days_due_date')->nullable();
            });
        }
        if(!Schema::hasColumn('generalsettings', 'paghiper_pix_is_discount')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->boolean('paghiper_pix_is_discount')->nullable()->default(false);
            });
        }
        if(!Schema::hasColumn('generalsettings', 'paghiper_pix_discount')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->integer('paghiper_pix_discount');
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
