<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGsAddShowPhotoAtInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('generalsettings', 'is_invoice_photo')){
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->boolean('is_invoice_photo')->default(false);
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
        if(Schema::hasColumn('generalsettings', 'is_invoice_photo')){
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->dropColumn('is_invoice_photo');
            });
        }
    }
}
