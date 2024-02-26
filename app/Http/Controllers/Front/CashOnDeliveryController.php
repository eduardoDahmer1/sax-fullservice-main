<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Traits\Gateway;
use Illuminate\Support\Facades\Session;

class CashOnDeliveryController extends Controller
{
    use Gateway;

    public function __construct()
    {
        parent::__construct();

        $this->enabled = true;
        $this->name = __("Cash on Delivery");
        $this->checkValue = false;
    }

    protected function payment()
    {
       $this->paymentUrl = action('Front\PaymentController@payreturn');
        if(Session::has("order")){
            Session::forget('order');
        }
       return;
    }
}
