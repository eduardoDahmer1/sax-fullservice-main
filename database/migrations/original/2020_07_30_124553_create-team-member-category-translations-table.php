<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('team_member_category_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            
            $table->unique(['team_member_category_id', 'locale'], 'team_category_index_unique');
            $table->foreign('team_member_category_id', 'team_category_id_foreign')->references('id')->on('team_member_categories')->onDelete('cascade');
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
