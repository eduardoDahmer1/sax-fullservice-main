<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGeneralsettingsAddMoreBancardFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->text('bancard_public_key')->nullable();
            $table->text('bancard_private_key')->nullable();
            $table->enum('bancard_mode', ['sandbox', 'live'])->nullable();
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
            $table->dropColumn('bancard_public_key');
            $table->dropColumn('bancard_private_key');
            $table->dropColumn('bancard_mode');
        });
    }
}
