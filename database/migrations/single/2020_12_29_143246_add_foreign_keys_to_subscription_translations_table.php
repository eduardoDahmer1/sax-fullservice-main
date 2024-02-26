<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSubscriptionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_translations', function (Blueprint $table) {
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_translations', function (Blueprint $table) {
            $table->dropForeign('subscription_translations_subscription_id_foreign');
        });
    }
}
