<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PagesettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('pagesettings')->delete();

        \DB::table('pagesettings')->insert(array (
            0 =>
            array (
                'id' => 1,
                'contact_email' => '',
                'street' => NULL,
                'phone' => NULL,
                'fax' => NULL,
                'email' => NULL,
                'site' => NULL,
                'slider' => 1,
                'service' => 1,
                'featured' => 1,
                'small_banner' => 1,
                'best' => 1,
                'top_rated' => 1,
                'large_banner' => 1,
                'big' => 1,
                'hot_sale' => 1,
                'partners' => 1,
                'blog_posts' => 1,
                'reviews_store' => 1,
                'best_seller_banner' => NULL,
                'best_seller_banner_link' => '#',
                'big_save_banner' => NULL,
                'big_save_banner_link' => '#',
                'bottom_small' => 1,
                'flash_deal' => 1,
                'best_seller_banner1' => NULL,
                'best_seller_banner_link1' => '#',
                'big_save_banner1' => NULL,
                'big_save_banner_link1' => '#',
                'featured_category' => 1,
                'banner_search1' => NULL,
                'banner_search2' => NULL,
                'banner_search3' => NULL,
            ),
        ));


    }
}
