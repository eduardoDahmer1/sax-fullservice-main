<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVerificationTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verification_translations', function (Blueprint $table) {
            $table->foreign('verification_id')->references('id')->on('verifications')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verification_translations', function (Blueprint $table) {
            $table->dropForeign('verification_translations_verification_id_foreign');
        });
    }
}
