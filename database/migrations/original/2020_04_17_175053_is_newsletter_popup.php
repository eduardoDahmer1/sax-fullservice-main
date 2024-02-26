<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IsNewsletterPopup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->tinyInteger('is_newsletter_popup') // Nome da coluna
                    ->default(0)// newsletter desativada no popup
                    ->after('is_popup'); // Ordenado após a coluna is_popup
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn('is_newsletter_popup');
        });
    }
}
