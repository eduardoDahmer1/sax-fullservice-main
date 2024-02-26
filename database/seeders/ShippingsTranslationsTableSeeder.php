<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ShippingsTranslationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('shipping_translations')->delete();

        \DB::table('shipping_translations')->insert(array(
            0 =>
            array(
                'id' => 1,
                'shipping_id' => 1,
                'locale' => 'pt-br',
                'title' => 'Padrão',
            ),
            1 =>
            array(
                'id' => 2,
                'shipping_id' => 1,
                'locale' => 'es',
                'title' => 'Padrão',
            ),
        ));
    }
}
