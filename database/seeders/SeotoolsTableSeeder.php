<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SeotoolsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('seotools')->delete();

        \DB::table('seotools')->insert(array (
            0 =>
            array (
                'id' => 1,
                'google_analytics' => '',
                'facebook_pixel' => NULL,
                'tag_manager_head' => NULL,
                'tag_manager_body' => NULL,
            ),
        ));


    }
}
