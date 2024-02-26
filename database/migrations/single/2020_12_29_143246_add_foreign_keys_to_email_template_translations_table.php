<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEmailTemplateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_template_translations', function (Blueprint $table) {
            $table->foreign('email_template_id')->references('id')->on('email_templates')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_template_translations', function (Blueprint $table) {
            $table->dropForeign('email_template_translations_email_template_id_foreign');
        });
    }
}
