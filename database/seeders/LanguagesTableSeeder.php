<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('languages')->delete();

        \DB::table('languages')->insert(array (
            0 =>
            array (
                'id' => 1,
                'is_default' => 1,
                'language' => 'Português',
                'file' => 'pt-br.json',
                'locale' => 'pt-br',
                'file_extras' => NULL,
                'extras_name' => NULL,
                'rtl' => 0,
            ),
            1 =>
            array (
                'id' => 8,
                'is_default' => 0,
                'language' => 'Espanhol',
                'file' => 'es.json',
                'locale' => 'es',
                'file_extras' => NULL,
                'extras_name' => NULL,
                'rtl' => 0,
            ),
            2 =>
            array(
                'id' => 9,
                'is_default' => 0,
                'language' => 'English',
                'file' => 'en-US.json',
                'locale' => 'en-US',
                'file_extras' => NULL,
                'extras_name' => NULL,
                'rtl' => 0,
            )
    
            
        ));


    }
}
