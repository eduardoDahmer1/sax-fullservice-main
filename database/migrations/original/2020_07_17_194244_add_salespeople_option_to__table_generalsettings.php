<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalespeopleOptionToTableGeneralsettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->tinyInteger('salespeople_show_whatsapp')->after('jivochat');
            $table->tinyInteger('salespeople_show_header')->after('salespeople_show_whatsapp');
            $table->tinyInteger('salespeople_show_footer')->after('salespeople_show_header');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn('salespeople_show_whatsapp');
            $table->dropColumn('salespeople_show_header');
            $table->dropColumn('salespeople_show_footer');
        });
    }
}
