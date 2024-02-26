<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('notifications')->delete();

        \DB::table('notifications')->insert(array (
            0 =>
            array (
                'id' => 1,
                'order_id' => NULL,
                'user_id' => 1,
                'vendor_id' => NULL,
                'product_id' => NULL,
                'conversation_id' => NULL,
                'is_read' => 0,
                'created_at' => '2020-12-23 12:26:11',
                'updated_at' => '2020-12-23 12:26:11',
            ),
        ));


    }
}
