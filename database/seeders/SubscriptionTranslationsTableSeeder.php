<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubscriptionTranslationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('subscription_translations')->delete();

        \DB::table('subscription_translations')->insert(array (
            0 =>
            array (
                'id' => 1,
                'subscription_id' => 1,
                'locale' => 'pt-br',
                'title' => 'Teste',
                'details' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'subscription_id' => 1,
                'locale' => 'es',
                'title' => 'Teste',
                'details' => NULL,
            ),
        ));


    }
}
