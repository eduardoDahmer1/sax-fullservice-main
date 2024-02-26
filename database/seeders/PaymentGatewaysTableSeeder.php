<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentGatewaysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('payment_gateways')->delete();

        \DB::table('payment_gateways')->insert(array (
            0 =>
            array (
                'id' => 46,
                'subtitle' => 'Pay via your Mobile Money.',
                'title' => 'Mobile Money',
                'details' => '<font size="3"><b style="">Mobile Money</b><b>&nbsp;No: 6528068515</b><br><br></font>',
                'status' => 0,
            ),
        ));


    }
}
