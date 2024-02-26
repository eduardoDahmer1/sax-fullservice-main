<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('roles')->delete();

        \DB::table('roles')->insert(array (
            0 =>
            array (
                'id' => 16,
                'section' => 'catalog , sell , content , config , marketing , system , support',
            ),
            1 =>
            array (
                'id' => 17,
                'section' => 'content , config , marketing',
            ),
            2 =>
            array (
                'id' => 18,
                'section' => 'catalog , sell',
            ),
        ));


    }
}
