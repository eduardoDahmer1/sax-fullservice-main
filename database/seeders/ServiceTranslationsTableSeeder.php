<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServiceTranslationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('service_translations')->delete();

        \DB::table('service_translations')->insert(array (
            0 =>
            array (
                'id' => 4,
                'service_id' => 6,
                'locale' => 'pt-br',
                'title' => 'FREE SHIPPING',
                'details' => 'Free Shipping All Order',
            ),
            1 =>
            array (
                'id' => 5,
                'service_id' => 7,
                'locale' => 'pt-br',
                'title' => 'PAYMENT METHOD',
                'details' => 'Secure Payment',
            ),
            2 =>
            array (
                'id' => 6,
                'service_id' => 8,
                'locale' => 'pt-br',
                'title' => '30 DAY RETURNS',
                'details' => '30-Day Return Policy',
            ),
            3 =>
            array (
                'id' => 7,
                'service_id' => 9,
                'locale' => 'pt-br',
                'title' => 'HELP CENTER',
                'details' => '24/7 Support System',
            ),
        ));


    }
}
