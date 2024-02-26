<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubscriptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('subscriptions')->delete();

        \DB::table('subscriptions')->insert(array (
            0 =>
            array (
                'id' => 1,
                'price' => 150.0,
                'days' => 3,
                'allowed_products' => 0,
            ),
        ));


    }
}
