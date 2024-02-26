<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSeotoolsAddFacebookPixel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seotools', function (Blueprint $table) {
            $table->text('facebook_pixel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seotools', function (Blueprint $table) {
            $table->dropColumn('facebook_pixel');
        });
    }
}
