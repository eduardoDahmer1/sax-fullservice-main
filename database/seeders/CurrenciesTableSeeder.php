<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('currencies')->delete();

        \DB::table('currencies')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'BRL',
                'sign' => 'R$',
                'value' => 1.0,
                'is_default' => 1,
                'decimal_separator' => ',',
                'thousands_separator' => '.',
                'decimal_digits' => 2,
            ),
            1 =>
            array (
                'id' => 10,
                'name' => 'USD',
                'sign' => 'US$',
                'value' => 6.0,
                'is_default' => 0,
                'decimal_separator' => '.',
                'thousands_separator' => ',',
                'decimal_digits' => 2,
            ),
            2 =>
            array (
                'id' => 11,
                'name' => 'PYG',
                'sign' => 'G$',
                'value' => 1300.0,
                'is_default' => 0,
                'decimal_separator' => '.',
                'thousands_separator' => '.',
                'decimal_digits' => 0,
            ),
        ));


    }
}
