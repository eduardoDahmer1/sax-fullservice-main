<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSalespeople extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salespeoples', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
        Schema::rename('salespeoples', 'team_members');
        Schema::rename('salespeople_categories', 'team_member_categories');

        Schema::table('team_members', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('team_member_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
        Schema::rename('team_members', 'salespeoples');
        Schema::rename('team_member_categories', 'salespeople_categories');
        
        Schema::table('salespeoples', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('salespeople_categories');
        });
    }
}
