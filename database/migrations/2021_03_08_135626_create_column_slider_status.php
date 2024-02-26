<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
   
class CreateColumnSliderStatus extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('sliders', function (Blueprint $table) {
            $table->boolean('status')
                    ->default('1')
                    ->after('position');                                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('sliders', function($table) {
            $table->dropColumn('status');
        });
    }
}
