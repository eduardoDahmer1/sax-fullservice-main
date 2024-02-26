<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGsAddIsBlog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('generalsettings', 'is_blog')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->boolean('is_blog')->default(true);
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
        if(Schema::hasColumn('generalsettings', 'is_blog')){
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->dropColumn('is_blog');
            });
        }
    }
}
