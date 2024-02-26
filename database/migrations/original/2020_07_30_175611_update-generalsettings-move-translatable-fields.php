<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGeneralsettingsMoveTranslatableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("delete from generalsetting_translations");
        
        // get language 1
        $lang1 = DB::table('languages')->where('id', 1)->first();

        // We insert the old attributes into the fresh translation table: 
        DB::statement("insert into generalsetting_translations 
         (generalsetting_id, locale, title, footer, copyright, cod_text, popup_title, popup_text, maintain_text, bancard_text, mercadopago_text, cielo_text, pagseguro_text, pagopar_text, bank_text) 
         select id, '{$lang1->locale}', title, footer, copyright, cod_text, popup_title, popup_text, maintain_text, bancard_text, mercadopago_text, cielo_text, pagseguro_text, pagopar_text, bank_text 
         from generalsettings");

        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('footer');
            $table->dropColumn('copyright');
            $table->dropColumn('cod_text');
            $table->dropColumn('popup_title');
            $table->dropColumn('popup_text');
            $table->dropColumn('maintain_text');
            $table->dropColumn('bancard_text');
            $table->dropColumn('mercadopago_text');
            $table->dropColumn('cielo_text');
            $table->dropColumn('pagseguro_text');
            $table->dropColumn('pagopar_text');
            $table->dropColumn('bank_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //it is not possible to revert. Old attributes would be nullable
    }
}
