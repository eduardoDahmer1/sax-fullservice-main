<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGsAddIsPaghiper extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('generalsettings', 'is_paghiper')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->boolean('is_paghiper')->nullable()->default(false);
            });
        }
        if(!Schema::hasColumn('generalsettings', 'paghiper_token')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->string('paghiper_token')->nullable();
            });
        }
        if(!Schema::hasColumn('generalsettings', 'paghiper_api_key')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->string('paghiper_api_key')->nullable();
            });
        }
        if(!Schema::hasColumn('generalsettings', 'paghiper_days_due_date')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->integer('paghiper_days_due_date')->nullable();
            });
        }
        if(!Schema::hasColumn('generalsettings', 'paghiper_is_discount')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->boolean('paghiper_is_discount')->nullable()->default(false);
            });
        }
        if(!Schema::hasColumn('generalsettings', 'paghiper_discount')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->integer('paghiper_discount');
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
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn('is_paghiper');
            $table->dropColumn('paghiper_token');
            $table->dropColumn('paghiper_api_key');
            $table->dropColumn('paghiper_days_due_date');
            $table->dropColumn('paghiper_is_discount');
            $table->dropColumn('paghiper_discount');
        });
    }
}
