<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PackagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('packages')->delete();

        \DB::table('packages')->insert(array (
            0 =>
            array (
                'id' => 1,
            ),
        ));


    }
}
