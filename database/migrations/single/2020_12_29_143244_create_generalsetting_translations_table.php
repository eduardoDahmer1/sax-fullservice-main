<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralsettingTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generalsetting_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('generalsetting_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('footer')->nullable();
            $table->text('copyright')->nullable();
            $table->string('cod_text')->nullable();
            $table->string('popup_title')->nullable();
            $table->text('popup_text')->nullable();
            $table->text('maintain_text')->nullable();
            $table->text('bancard_text')->nullable();
            $table->text('mercadopago_text')->nullable();
            $table->text('cielo_text')->nullable();
            $table->text('pagseguro_text')->nullable();
            $table->text('pagopar_text')->nullable();
            $table->text('bank_text')->nullable();
            $table->text('pagarme_text')->nullable();
            $table->text('rede_text')->nullable();
            $table->unique(['generalsetting_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generalsetting_translations');
    }
}
