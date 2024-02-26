<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTeamMemberCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_member_category_translations', function (Blueprint $table) {
            $table->foreign('team_member_category_id', 'team_category_id_foreign')->references('id')->on('team_member_categories')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_member_category_translations', function (Blueprint $table) {
            $table->dropForeign('team_category_id_foreign');
        });
    }
}
