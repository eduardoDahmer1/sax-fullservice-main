<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNotificationAddReceipt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('notifications', 'receipt')) {
            Schema::table('notifications', function(Blueprint $table){
                $table->string('receipt')->nullable();
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
        if(Schema::hasColumn('notifications', 'receipt')){
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropColumn('receipt');
            });
        }
    }
}
