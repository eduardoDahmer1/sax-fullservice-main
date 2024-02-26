<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminLanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('admin_languages')->delete();

        \DB::table('admin_languages')->insert(array(
            0 =>
            array(
                'id' => 1,
                'is_default' => 1,
                'language' => 'Português',
                'file' => 'admin_pt-br.json',
                'name' => 'pt-br',
                'rtl' => 0,
            ),
            1 =>
            array(
                'id' => 5,
                'is_default' => 0,
                'language' => 'Espanhol',
                'file' => 'admin_es.json',
                'name' => 'es',
                'rtl' => 0,
            ),
            2 =>
            array(
                'id' => 6,
                'is_default' => 0,
                'language' => 'English',
                'file' => 'admin_en-US.json',
                'name' => 'en-US',
                'rtl' => 0,
            )
        ));
    }
}
