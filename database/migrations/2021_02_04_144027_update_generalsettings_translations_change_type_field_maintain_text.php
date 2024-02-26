<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGeneralsettingsTranslationsChangeTypeFieldMaintainText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsetting_translations', function (Blueprint $table) {
            $table->mediumtext('maintain_text')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generalsetting_translations', function (Blueprint $table) {
            $table->text('maintain_text')->change();
        });
    }
}
