<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterServicesAddLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('services', 'link')) {
            Schema::table('services', function(Blueprint $table){
                $table->string('link')->nullable();
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
        if(Schema::hasColumn('services', 'link')){
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('link');
            });
        }
    }
}
