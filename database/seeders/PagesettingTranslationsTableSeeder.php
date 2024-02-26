<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PagesettingTranslationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('pagesetting_translations')->delete();

        \DB::table('pagesetting_translations')->insert(array (
            0 =>
            array (
                'id' => 1,
                'pagesetting_id' => 1,
                'locale' => 'pt-br',
                'contact_success' => NULL,
                'contact_title' => NULL,
                'contact_text' => NULL,
                'side_title' => '<h4 class="title" style="margin-bottom: 10px; font-weight: 600; line-height: 28px; font-size: 28px;">Let\'s Connect</h4>',
            'side_text' => '<span style="color: rgb(51, 51, 51);">Get in touch with us</span>',
            ),
            1 =>
            array (
                'id' => 2,
                'pagesetting_id' => 1,
                'locale' => 'es',
                'contact_success' => NULL,
                'contact_title' => NULL,
                'contact_text' => NULL,
                'side_title' => NULL,
                'side_text' => NULL,
            ),
        ));
    }
}
