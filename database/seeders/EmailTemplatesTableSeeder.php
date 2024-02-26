<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EmailTemplatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('email_templates')->delete();

        \DB::table('email_templates')->insert(array (
            0 =>
            array (
                'id' => 1,
                'email_type' => 'new_order',
                'status' => 1,
            ),
            1 =>
            array (
                'id' => 2,
                'email_type' => 'new_registration',
                'status' => 1,
            ),
            2 =>
            array (
                'id' => 3,
                'email_type' => 'vendor_accept',
                'status' => 1,
            ),
            3 =>
            array (
                'id' => 4,
                'email_type' => 'subscription_warning',
                'status' => 1,
            ),
            4 =>
            array (
                'id' => 5,
                'email_type' => 'vendor_verification',
                'status' => 1,
            ),
            5 =>
            array (
                'id' => 6,
                'email_type' => 'cart_abandonment',
                'status' => 1,
            ),
            6 =>
            array (
                'id' => 7,
                'email_type' => 'new_order2',
                'status' => 1,
            ),
        ));
    }
}
