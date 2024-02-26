<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SeotoolTranslationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('seotool_translations')->delete();

        \DB::table('seotool_translations')->insert(array (
            0 =>
            array (
                'id' => 1,
                'seotool_id' => 1,
                'locale' => 'pt-br',
                'meta_keys' => '',
                'meta_description' => NULL,
            ),
        ));


    }
}
