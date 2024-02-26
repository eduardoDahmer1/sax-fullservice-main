<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGeneralsettingsAddPagarmeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->boolean('is_pagarme');
            $table->text('pagarme_encryption_key')->nullable();
            $table->text('pagarme_api_key')->nullable();
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
            $table->dropColumn('is_pagarme');
            $table->dropColumn('pagarme_encryption_key');
            $table->dropColumn('pagarme_api_key');
        });
    }
}
