<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGsAddIsPay42 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function(Blueprint $table){
            $table->boolean('is_pay42_pix')->nullable()->default(false);
            $table->boolean('is_pay42_sandbox')->nullable()->default(false);
            $table->boolean('is_pay42_billet')->nullable()->default(false);
            $table->boolean('is_pay42_card')->nullable()->default(false);
            $table->string('pay42_token')->nullable()->default(null);
            $table->integer('pay42_due_date')->nullable()->default(null);
            $table->string('pay42_currency')->nullable()->default("BRL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generalsettings', function(Blueprint $table){
            $table->dropColumn('is_pay42_pix');
            $table->dropColumn('is_pay42_sandbox');
            $table->dropColumn('pay42_token');
            $table->dropColumn('pay42_currency');
            $table->dropColumn('is_pay42_billet');
            $table->dropColumn('pay42_due_date');
            $table->dropColumn('is_pay42_card');
        });
    }
}
