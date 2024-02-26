<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamMemberCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_member_category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('team_member_category_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['team_member_category_id', 'locale'], 'team_category_index_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_member_category_translations');
    }
}
