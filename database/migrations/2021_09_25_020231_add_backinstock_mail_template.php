<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBackinstockMailTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('email_templates')->insert([
            'id' => 7,
            'email_type' => 'back_in_stock',
            'status' => 1
        ]);

        DB::table('email_template_translations')->insert([
            'id' => 13,
            'email_template_id' => 7,
            'locale' => 'pt-br',
            'email_subject' => 'Um produto que você procura está disponível novamente!',
            'email_body' => '
            <p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0">
            </p>
            <pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr">
                <span lang="pt">Olá!
                    Confira já!
                </span>
            </pre><p></p>'
        ]);
        DB::table('email_template_translations')->insert([
            'id' => 14,
            'email_template_id' => 7,
            'locale' => 'es',
            'email_subject' => 'Un producto que estás buscando está disponible nuevamente!',
            'email_body' => '
            <p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0">
            </p>
            <pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr">
                <span lang="pt">Hola!
                ¡Verifica!
                </span>
            </pre><p></p>'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('email_templates')->where('id', 7)->delete();
        DB::table('email_template_translations')->where('email_template_id', 7)->delete();
    }
}
