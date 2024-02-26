<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterShippingAddZip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('shippings', 'cep_start')){
            Schema::table('shippings', function(Blueprint $table){
                $table->string('cep_start')->nullable();
            });
        }
        if(!Schema::hasColumn('shippings', 'cep_end')){
            Schema::table('shippings', function(Blueprint $table){
                $table->string('cep_end')->nullable();
            });
        }
        if(!Schema::hasColumn('shippings', 'is_region')){
            Schema::table('shippings', function(Blueprint $table){
                $table->boolean('is_region')->default(false);
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
        if(Schema::hasColumn('shippings', 'cep_start')){
            Schema::table('shippings', function(Blueprint $table){
                $table->dropColumn('cep_start');
            });
        }
        if(Schema::hasColumn('shippings', 'cep_end')){
            Schema::table('shippings', function(Blueprint $table){
                $table->dropColumn('cep_end');
            });
        }
        if(Schema::hasColumn('shippings', 'is_region')){
            Schema::table('shippings', function(Blueprint $table){
                $table->dropColumn('is_region');
            });
        }
    }
}
