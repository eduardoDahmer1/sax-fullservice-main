<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGsTranslationsAddVendorPolicy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('generalsetting_translations', 'vendor_policy')) {
            Schema::table('generalsetting_translations', function(Blueprint $table){
                $table->mediumText('vendor_policy')->nullable();
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
        if(Schema::hasColumn('generalsetting_translations', 'vendor_policy')){
            Schema::table('generalsetting_translations', function (Blueprint $table) {
                $table->dropColumn('vendor_policy');
            });
        }
    }
}
