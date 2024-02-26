<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Traits\Gateway;

class BankDepositController extends Controller
{
    use Gateway;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = true;
        $this->name = __("Bank Deposit");
        $this->checkValue = false;
    }

    protected function payment()
    {
       $this->paymentUrl = action('Front\PaymentController@payreturn');
       return;
    }
}
