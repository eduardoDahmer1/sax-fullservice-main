<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralsettingTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE generalsettings ENGINE=InnoDB');

        Schema::create('generalsetting_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('generalsetting_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('footer')->nullable();
            $table->string('copyright')->nullable();
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
            
            $table->unique(['generalsetting_id', 'locale']);
            $table->foreign('generalsetting_id')->references('id')->on('generalsettings')->onDelete('cascade');
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

        DB::statement('ALTER TABLE generalsettings ENGINE=MyISAM');
    }
}
