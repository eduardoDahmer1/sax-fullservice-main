<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subscription_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('details')->nullable();
            
            $table->unique(['subscription_id', 'locale']);
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_translations');
    }
}
