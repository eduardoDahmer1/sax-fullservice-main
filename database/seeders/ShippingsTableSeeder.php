<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ShippingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('shippings')->delete();

        \DB::table('shippings')->insert(array(
            0 =>
            array(
                'id' => 1,
                'price' => 0,
                'shipping_type' => 'Free',
                'status' => 1,
                'local_shipping' => 0
            ),
        ));
    }
}
