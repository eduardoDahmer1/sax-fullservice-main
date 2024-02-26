<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsAddFtpHash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('products', 'ftp_hash')) {
            Schema::table('products', function(Blueprint $table){
                $table->string('ftp_hash')->nullable();
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
        if(Schema::hasColumn('products', 'ftp_hash')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('ftp_hash');
            });
        }
    }
}
