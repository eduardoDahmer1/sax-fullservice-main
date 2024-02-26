<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SocialProvidersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('social_providers')->delete();

        \DB::table('social_providers')->insert(array (
            0 =>
            array (
                'id' => 1,
                'user_id' => 37,
                'provider_id' => '107486278340978172129',
                'provider' => 'google',
                'created_at' => '2020-07-23 14:38:55',
                'updated_at' => '2020-07-23 14:38:55',
            ),
        ));


    }
}
