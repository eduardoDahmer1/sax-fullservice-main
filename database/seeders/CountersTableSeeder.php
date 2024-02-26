<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CountersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('counters')->delete();

        \DB::table('counters')->insert(array (
            0 =>
            array (
                'id' => 1,
                'type' => 'browser',
                'referral' => 'Linux',
                'total_count' => 8,
                'todays_count' => 0,
                'today' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'type' => 'browser',
                'referral' => 'Mac OS X',
                'total_count' => 21,
                'todays_count' => 0,
                'today' => NULL,
            ),
        ));


    }
}
