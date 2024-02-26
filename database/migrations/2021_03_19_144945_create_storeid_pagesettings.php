<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreidPagesettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    if (!Schema::hasColumn('pagesettings', 'store_id'))
    {
        Schema::table('pagesettings', function (Blueprint $table) 
        {
        $table->integer('store_id')->default(1);
        $table->foreign('store_id')->references('id')->on('generalsettings')->onDelete('CASCADE');
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
      if (Schema::hasColumn('pagesettings', 'store_id'))
      {
        Schema::table('pagesettings', function (Blueprint $table)
        {
            $table->dropForeign('pagesettings_store_id_foreign');
            $table->dropColumn('store_id');
        }); 
      }
    }
}
