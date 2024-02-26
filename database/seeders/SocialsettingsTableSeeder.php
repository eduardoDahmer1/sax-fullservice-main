<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SocialsettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('socialsettings')->delete();

        \DB::table('socialsettings')->insert(array (
            0 =>
            array (
                'id' => 1,
                'facebook' => 'https://facebook.com/',
                'twitter' => 'https://twitter.com/',
                'linkedin' => 'https://linkedin.com/',
                'dribble' => 'https://dribble.com/',
                'instagram' => 'https://instagram.com/',
                'youtube' => 'https://youtube.com/',
                'y_status' => 0,
                'f_status' => 1,
                't_status' => 0,
                'l_status' => 1,
                'd_status' => 0,
                'i_status' => 1,
                'f_check' => 0,
                'g_check' => 0,
                'fclient_id' => NULL,
                'fclient_secret' => NULL,
                'fredirect' => NULL,
                'gclient_id' => NULL,
                'gclient_secret' => NULL,
                'gredirect' => NULL,
            ),
        ));


    }
}
