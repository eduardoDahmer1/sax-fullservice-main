<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PackageTranslationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('package_translations')->delete();

        \DB::table('package_translations')->insert(array (
            0 =>
            array (
                'id' => 1,
                'package_id' => 1,
                'locale' => 'pt-br',
                'title' => 'Padrão',
                'subtitle' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'package_id' => 1,
                'locale' => 'es',
                'title' => 'Padrão',
                'subtitle' => NULL,
            ),
        ));
    }
}
