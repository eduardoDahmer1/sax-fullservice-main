<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('services')->delete();

        \DB::table('services')->insert(array (
            0 =>
            array (
                'id' => 6,
                'user_id' => 13,
                'photo' => '1563247602brand1.png',
            ),
            1 =>
            array (
                'id' => 7,
                'user_id' => 13,
                'photo' => '1563247614brand2.png',
            ),
            2 =>
            array (
                'id' => 8,
                'user_id' => 13,
                'photo' => '1563247620brand3.png',
            ),
            3 =>
            array (
                'id' => 9,
                'user_id' => 13,
                'photo' => '1563247670brand4.png',
            ),
        ));


    }
}
