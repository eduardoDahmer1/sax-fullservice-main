@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('styles')
<style>
.area-boleto{
    text-align: center;
}
.area-boleto .content .heading{
    font-size: 30px;
    font-weight: 700;
}
.area-boleto .content  .text{
    margin-bottom: 0px;
    font-size: 16px;
    line-height: 26px;
}
.area-boleto .content .link{
    font-weight: 700;
    font-size: 14px;
    text-decoration: underline;;
}
.area-boleto .content{
    margin-bottom: 30px;
}
.product-header-title h2 {
    font-size: 18px;
    font-weight: 600;
}
</style>
@section('content')
<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="pages">
                    <li>
                        <a href="{{ route('front.index') }}">
                            {{ __("Home") }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('payment.return') }}">
                            {{ __("Success") }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->
<section class="tempcart">
    @if(!empty($tempcart))
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Starting of Dashboard data-table area -->
                <div class="content-box section-padding add-product-1 ">
                    <div class="area-boleto">
                        <div class="content">
                            <h4 class="heading">
                                {{ __("THANK YOU FOR YOUR PURCHASE.") }}
                            </h4>
                            @if($order->method == "Bank Deposit")
                                @if(Auth::user())
                                    <a class="btn btn-success m-3" id="link-simplified" href="{{ route("user-upload-receipt", $order->id) }}" target="_blank"><span class="heading" style="font-size: 20px"><i class="fa fa-external-link"></i>{{ __("Upload your payment receipt") }}</span></a>
                                    <p class="text">{{ __("You can access it later through your Dashboard.") }}</p>
                                @else
                                    <a class="btn btn-success m-3" id="link-simplified" href="{{ route("front.receipt-number", $order->order_number) }}" target="_blank"><span class="heading" style="font-size: 20px"><i class="fa fa-external-link"></i>{{ __("Upload your payment receipt") }}</span></a>
                                    <p class="text">{{ __("You can access it later through the link at the bottom of the page.") }}</p>
                                @endif
                            @endif
                            @if($order->pay42_billet)
                            {{-- <div class="col-md-12" style="display:flex; justify-content:center"> --}}
                                <a class="btn btn-success m-3" href="{{$order->pay42_billet}}" target="_blank"><span class="heading" style="font-size: 20px"><i class="fa fa-external-link"></i>{{__('Access billet')}} </a></span>
                            {{-- </div> --}}
                            @endif
                            <p class="text">
                                {{ __("We'll email you an order confirmation with details and tracking info.") }}
                            </p>
                            @if(Session::has('gateway_message'))
                            <br>
                            <p class="text">
                                {{Session::get('gateway_message')}}
                            </p>
                            @endif
                            @if(Session::has('gateway_url'))
                            <a class="btn btn-success m-3" id="link-simplified" href='{{Session::get('gateway_url')}}'
                                target="_blank"><span class="heading" style="font-size: 20px"><i
                                        class="fa fa-external-link"></i>{{ (Session::has('gateway_url_title') ? Session::get('gateway_url_title') : __("Click Here")) }}</span></a>
                            <br>
                            @endif
                            @if(Session::has('pix_qrcode'))
                            <img src="{{ session()->get('pix_qrcode') }}" width="50%" alt="">
                            <p>PIX Copia e Cola (clique para copiar):</p>
                            <br>
                            <strong style="cursor: pointer" onclick="copy(this)">{{ session()->get('pix_copy_paste') }}</strong>
                            <br><br><br>
                            @endif
                            @if(isset($pay42_qrcode))
                            <img src="{{asset($pay42_qrcode)}}" width="50%" alt="">
                            <p>PIX QR CODE 
                                {{__('Expire in: ')}} {{$order->pay42_due_date.' UTC'}}  </p>
                            <br>
                            <br><br><br>
                            @endif
                            <a href="{{ route('front.index') }}" class="link">{{ __("Get Back To Our Homepage") }}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="product__header">
                                <div class="row reorder-xs">
                                    <div class="col-lg-12">
                                        <div class="product-header-title">
                                            <h2>{{ __("Order#") }} {{$order->order_number}}</h2>
                                        </div>
                                    </div>
                                    @include('includes.form-success')
                                    <div class="col-md-12" id="tempview">
                                        <div class="dashboard-content">
                                            <div class="view-order-page" id="print">
                                                <p class="order-date">{{ __("Order Date") }}
                                                    {{date('d-M-Y',strtotime($order->created_at))}}</p>
                                                @if($order->dp == 1)
                                                <div class="billing-add-area">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h5>{{ __("Billing Address") }}</h5>
                                                            <address>
                                                                {{ __("Name:") }} {{$order->customer_name}}<br>
                                                                {{ __("Email:") }} {{$order->customer_email}}<br>
                                                                {{ __("Phone:") }} {{$order->customer_phone}}<br>
                                                                {{ __("Address:") }} {{$order->customer_address}}<br>
                                                                {{ $customer_doc_str }}:
                                                                {{$order->customer_document}}<br>
                                                                {{ __('State:') }} {{$order->customer_state}}<br>
                                                                {{ __('City:') }} {{$order->customer_city}}<br>
                                                                {{ __('Country:') }} {{$order->customer_country}}<br>
                                                                {{ __('Zip:') }} {{$order->customer_zip}}<br>
                                                                {{ __('Complement:') }}
                                                                {{$order->customer_complement}}<br>
                                                            </address>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h5>{{ __("Payment Information") }}</h5>
                                                            <p>{{ __("Paid Amount:") }}
                                                                {{$order->currency_sign}}{{ number_format($order->pay_amount * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator,$order_curr->thousands_separator) }}
                                                            </p>
                                                            <p>{{ __("Payment Method:") }} {{$order->method}}</p>
                                                            @if($order->method != "Cash On Delivery")
                                                            @if($order->method=="Stripe")
                                                            {{$order->method}} {{ __("Charge ID:") }}
                                                            <p>{{$order->charge_id}}</p>
                                                            @endif
                                                            {{$order->method}} {{ __("Transaction ID:") }}
                                                            <p id="ttn">{{$order->txnid}}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="shipping-add-area">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            @if($order->shipping == "shipto")
                                                            <h5>{{ __("Shipping Address") }}</h5>
                                                            <address>
                                                                {{ __("Name:") }}
                                                                {{$order->shipping_name == null ? $order->customer_name : $order->shipping_name}}<br>
                                                                {{ __("Email:") }}
                                                                {{$order->shipping_email == null ? $order->customer_email : $order->shipping_email}}<br>
                                                                {{ __("Phone:") }}
                                                                {{$order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone}}<br>
                                                                {{ __("Address:") }}
                                                                {{$order->shipping_address == null ? $order->customer_address : $order->shipping_address}}<br>
                                                                {{ $customer_doc_str }}:
                                                                {{$order->shipping_document == null ? $order->customer_document : $order->shipping_document}}<br>
                                                                {{ __('State:') }}
                                                                {{$order->shipping_state == null ? $order->customer_state : $order->shipping_state}}<br>
                                                                {{ __('City:') }}
                                                                {{$order->shipping_city == null ? $order->customer_city : $order->shipping_city}}<br>
                                                                {{ __('Country:') }}
                                                                {{$order->shipping_country == null ? $order->customer_country : $order->shipping_country}}<br>
                                                                {{ __('Zip:') }}
                                                                {{$order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip}}<br>
                                                                {{ __('Complement:') }}
                                                                {{$order->shipping_complement == null ? $order->customer_complement : $order->shipping_complement}}<br>
                                                            </address>
                                                            @else
                                                            <h5>{{ __("PickUp Location") }}</h5>
                                                            <address>
                                                                {{ __("Address:") }} {{$order->pickup_location}}<br>
                                                            </address>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h5>{{ __("Shipping Method") }}</h5>
                                                            @if($order->shipping == "shipto")
                                                            <p>{{ __("Ship To Address") }}</p>
                                                            @else
                                                            <p>{{ __("Pick Up") }}</p>
                                                            @endif
                                                            <p>
                                                                {{ $order->shipping_type }}:
                                                                {{$order->currency_sign}}{{ number_format($order->shipping_cost * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator,$order_curr->thousands_separator) }}<br>

                                                                {{ $order->packing_type }}:
                                                                {{$order->currency_sign}}{{ number_format($order->packing_cost * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator,$order_curr->thousands_separator) }}<br>
                                                            </p>
                                                            @if ($order->puntoentrega != null)
                                                            <h5>{{ __("Delivery point") }}</h5>
                                                            <p>
                                                                {{$order->puntoentrega}}
                                                            </p>
                                                            @endif
                                                            <h5>{{ __("Order Note") }}</h5>
                                                                {{ $order->order_note }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="billing-add-area">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h5>{{ __("Billing Address") }}</h5>
                                                            <address>
                                                                {{ __("Name:") }} {{$order->customer_name}}<br>
                                                                {{ __("Email:") }} {{$order->customer_email}}<br>
                                                                {{ __("Phone:") }} {{$order->customer_phone}}<br>
                                                                {{ __("Address:") }} {{$order->customer_address}}<br>
                                                                {{ $customer_doc_str }}:
                                                                {{$order->customer_document}}<br>
                                                                {{ __('State:') }} {{$order->customer_state}}<br>
                                                                {{ __('City:') }} {{$order->customer_city}}<br>
                                                                {{ __('Country:') }} {{$order->customer_country}}<br>
                                                                {{ __('Zip:') }} {{$order->customer_zip}}<br>
                                                                {{ __('Complement:') }}
                                                                {{$order->customer_complement}}<br>
                                                            </address>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h5>{{ __("Payment Information") }}</h5>
                                                            <p>
                                                                @if($order->coupon_discount != 0)
                                                                {{ __('Discount:') }}
                                                                {{$order->currency_sign}}{{ number_format($order->coupon_discount * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator,$order_curr->thousands_separator) }}<br>
                                                                @endif
                                                                {{ __("Paid Amount:") }}
                                                                {{$order->currency_sign}}{{ number_format($order->pay_amount * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator,$order_curr->thousands_separator) }}<br>
                                                                @if($order->pay42_exchange_rate)
                                                                    <p style="font-size: 20px">
                                                                        {{ $first_curr->sign.' '.__('Total to be paid:') }}
                                                                        {{$first_curr->sign}}{{ number_format($order->pay42_total,  $first_curr->decimal_digits, $first_curr->decimal_separator,$first_curr->thousands_separator) }}<br>
                                                                        {{__('Pay42 exchange rate: ').$order->pay42_exchange_rate}}
                                                                    </p>
                                                                @else
                                                                    {{ $first_curr->sign.' '.__('Total:') }}
                                                                    {{$first_curr->sign}}{{ number_format($order->pay_amount,  $first_curr->decimal_digits, $first_curr->decimal_separator,$first_curr->thousands_separator) }}<br>
                                                                @endif
                                                            </p>
                                                            <p>{{ __("Payment Method:") }} {{$order->method}}</p>
                                                            @if($order->method != "Cash On Delivery")
                                                            @if($order->method=="Stripe")
                                                            {{$order->method}} {{ __("Charge ID:") }}
                                                            <p>{{$order->charge_id}}</p>
                                                            @endif
                                                            @if($order->method=="Paypal")
                                                            {{$order->method}} {{ __("Transaction ID:") }}
                                                            <p id="ttn">{{ isset($_GET['tx']) ? $_GET['tx'] : '' }}</p>
                                                            @elseif($order->method!="Paghiper")
                                                            {{$order->method}} {{ __("Transaction ID:") }}
                                                            <p id="ttn">{{$order->txnid}}</p>
                                                            @endif
                                                            @endif
                                                            @if($order->method == "Paghiper" && $gs->paghiper_is_discount)
                                                            {{$order->method}} {{ __("Discount:") }} {{ $gs->paghiper_discount }}%
                                                            @endif

                                                            @if($order->method=="Bank Deposit")
                                                            <div style="margin-top: 10px; font-size: small;">
                                                                @foreach($bank_accounts as $bank_account)
                                                                <ul class="list-group" style="margin-top: 10px">
                                                                    <li class="list-group-item" style="padding: 5px;">
                                                                        {{strtoupper($bank_account->name)}}</li>
                                                                    <li class="list-group-item">{!!nl2br(str_replace("
                                                                        ", " &nbsp;", $bank_account->info))!!}</li>
                                                                </ul>
                                                                @endforeach
                                                            </div>
                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <h4 class="text-center">{{ __("Ordered Products:") }}</h4>
                                                        <thead>
                                                            <tr>
                                                                <th width="60%">{{ __("Name") }}</th>
                                                                <th width="20%">{{ __("Details") }}</th>
                                                                <th width="10%">{{ __("Price") }}</th>
                                                                <th width="10%">{{ __("Total") }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($tempcart->items as $product)
                                                            <tr>
                                                                <td>
                                                                    {{ $product['item']['name'] }}
                                                                    <hr>
                                                                    @php $prod =
                                                                    App\Models\Product::find($product['item']['id']);
                                                                    @endphp
                                                                    @if(isset($prod))
                                                                    <p style="margin-bottom: 0; font-size: 10px">
                                                                        {{ __('Product SKU') }} - {{$prod->sku}}</p>
                                                                    <p style="font-size: 10px">
                                                                        {{ __('Reference Code') }} - {{$prod->ref_code}}
                                                                    </p>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <b>{{ __("Quantity") }}</b>: {{$product['qty']}}
                                                                    <br>
                                                                    @if(!empty($product['size']))
                                                                    <b>{{ __("Size") }}</b>:
                                                                    {{ $product['item']['measure'] }}{{str_replace('-',' ',$product['size'])}}
                                                                    <br>
                                                                    @endif
                                                                    @if(!empty($product['color']))
                                                                    <div class="d-flex mt-2">
                                                                        <b>{{ __("Color") }}</b>: <span id="color-bar"
                                                                            style="border: 10px solid #{{$product['color'] == "" ? "white" : $product['color']}};"></span>
                                                                    </div>
                                                                    @endif
                                                                    @if(!empty($product['material']))
                                                                    <div class="d-flex mt-2">
                                                                        <b>{{ __("Material") }}</b>:
                                                                        {{ $product['material'] }}
                                                                        <br>
                                                                    </div>
                                                                    @endif
                                                                        @if(env("ENABLE_CUSTOM_PRODUCT"))
                                                                        @if(!empty($product['customizable_name']) || !empty($product['customizable_gallery']) || !empty($product['customizable_logo']) )
                                                                        <small>{{__("Contains customizable items")}}</small>
                                                                        @endif
                                                                    @endif
                                                                    @if(env("ENABLE_CUSTOM_PRODUCT_NUMBER"))
                                                                        @if(!empty($product['customizable_name']) || !empty($product['customizable_number']))
                                                                        <small>{{__("Contains customizable items")}}</small>
                                                                        @endif
                                                                    @endif
                                                                    @if(!empty($product['keys']))
                                                                    @foreach( array_combine(explode(',',
                                                                    $product['keys']), explode('~', $product['values']))
                                                                    as $key => $value)
                                                                    <b>{{ App\Models\Attribute::where('input_name', $key)->first()->name }}
                                                                        : </b> {{ $value }} <br>
                                                                    @endforeach
                                                                    @endif
                                                                </td>
                                                                @if($product['item']['promotion_price'] > 0)
                                                                    <td style="text-align: end;">
                                                                        {{$order->currency_sign}}{{number_format($product['item']['promotion_price'] * $order->currency_value,  $order_curr->decimal_digits, $order_curr->decimal_separator,$order_curr->thousands_separator)}}<br><small>{{$first_curr->sign}}{{ number_format($product['item']['price'],  $first_curr->decimal_digits, $first_curr->decimal_separator,$first_curr->thousands_separator) }}</small>
                                                                    </td>
                                                                @else
                                                                    <td style="text-align: end;">
                                                                        {{$order->currency_sign}}{{number_format($product['item']['price'] * $order->currency_value,  $order_curr->decimal_digits, $order_curr->decimal_separator,$order_curr->thousands_separator)}}<br><small>{{$first_curr->sign}}{{ number_format($product['item']['price'],  $first_curr->decimal_digits, $first_curr->decimal_separator,$first_curr->thousands_separator) }}</small>
                                                                    </td>
                                                                @endif
                                                                <td style="text-align: end;">
                                                                    {{$order->currency_sign}}{{number_format($product['price'] * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator,$order_curr->thousands_separator)}}<br><small>{{$first_curr->sign}}{{ number_format($product['price'],  $first_curr->decimal_digits, $first_curr->decimal_separator,$first_curr->thousands_separator) }}</small>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="area-boleto">
                        <div class="content">
                        @php
                            $linkWpp = "*| " . __('New Order - Simplified Checkout') . " |*" .
                            PHP_EOL.PHP_EOL;

                            if($order->customer_name) {
                            $linkWpp .= "_*" . __("Name") . ": " . $order->customer_name . "*_" .
                            PHP_EOL;
                            }
                            if($order->customer_phone) {
                            $linkWpp .= "_*" . __("Phone") . ": " . $order->customer_phone . "*_" .
                            PHP_EOL;
                            }

                            $linkWpp .= PHP_EOL . "*---------------------------------*".PHP_EOL.PHP_EOL;

                            foreach($tempcart->items as $product) {
                            $linkWpp .= "*" . __("Product") . "*: " . $product['item']['name'].PHP_EOL;
                            $linkWpp .= "*" . __("Quantity") . "*: " . $product['qty'].PHP_EOL;
                            if($admstore->show_product_prices){
                                $linkWpp .= "*" . __("Price") . "*: " . $order->currency_sign .
                                number_format($product['item']['price'] * $order->currency_value,
                                $order_curr->decimal_digits,
                                $order_curr->decimal_separator,$order_curr->thousands_separator).PHP_EOL;
                            }
                            $linkWpp .= (route('front.product', ['slug' =>
                            $product['item']['slug']])).PHP_EOL.PHP_EOL;
                            $linkWpp .= "*---------------------------------*".PHP_EOL.PHP_EOL;
                            }
                            if ($order->tax) {
                            $linkWpp .= "*" . __("Tax") . "*: " . $order->tax . "%" .PHP_EOL;
                            }
                            if ($order->coupon_code) {
                            $linkWpp .= "*" . __("Discount") . "*: " . $order->currency_sign .
                            number_format($order->coupon_discount * $order->currency_value, $order_curr->decimal_digits,
                            $order_curr->decimal_separator,$order_curr->thousands_separator).PHP_EOL;
                            }
                            if($admstore->show_product_prices){
                                $linkWpp .= "*" . __("Order Amount") . "*: " . $order->currency_sign .
                                number_format($order->pay_amount * $order->currency_value, $order_curr->decimal_digits,
                                $order_curr->decimal_separator,$order_curr->thousands_separator).PHP_EOL.PHP_EOL;
                            }
                            $linkWpp .= "_" . __("Order Number") . " - " . $order->order_number .
                            "_".PHP_EOL;
                            $linkWpp .= "_" . __("Date") . " - " .
                            Carbon\Carbon::now()->toDateTimeString() . "_";

                            $link = "https://web.whatsapp.com/send?1=pt_BR&phone=" . $gs->simplified_checkout_number .
                            "&text=" . urlencode($linkWpp);
                            @endphp

                            <a class="text" href="{{$link}}">
                                {{ __("Click here to complete your purchase on our Whatsapp") }}
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Ending of Dashboard data-table area -->
            </div>
            @endif
</section>

                            @php
                            $linkSimplifiedCheckout = "*| " . __('New Order - Simplified Checkout') . " |*" .
                            PHP_EOL.PHP_EOL;

                            if($order->customer_name) {
                            $linkSimplifiedCheckout .= "_*" . __("Name") . ": " . $order->customer_name . "*_" .
                            PHP_EOL;
                            }
                            if($order->customer_phone) {
                            $linkSimplifiedCheckout .= "_*" . __("Phone") . ": " . $order->customer_phone . "*_" .
                            PHP_EOL;
                            }

                            $linkSimplifiedCheckout .= PHP_EOL . "*---------------------------------*".PHP_EOL.PHP_EOL;

                            foreach($tempcart->items as $product) {
                            $linkSimplifiedCheckout .= "*" . __("Product") . "*: " . $product['item']['name'].PHP_EOL;
                            $linkSimplifiedCheckout .= "*" . __("Quantity") . "*: " . $product['qty'].PHP_EOL;
                            if($admstore->show_product_prices){
                                $linkSimplifiedCheckout .= "*" . __("Price") . "*: " . $order->currency_sign .
                                number_format($product['item']['price'] * $order->currency_value,
                                $order_curr->decimal_digits,
                                $order_curr->decimal_separator,$order_curr->thousands_separator).PHP_EOL;
                            }
                            $linkSimplifiedCheckout .= (route('front.product', ['slug' =>
                            $product['item']['slug']])).PHP_EOL.PHP_EOL;
                            $linkSimplifiedCheckout .= "*---------------------------------*".PHP_EOL.PHP_EOL;
                            }
                            if ($order->tax) {
                            $linkSimplifiedCheckout .= "*" . __("Tax") . "*: " . $order->tax . "%" .PHP_EOL;
                            }
                            if ($order->coupon_code) {
                            $linkSimplifiedCheckout .= "*" . __("Discount") . "*: " . $order->currency_sign .
                            number_format($order->coupon_discount * $order->currency_value, $order_curr->decimal_digits,
                            $order_curr->decimal_separator,$order_curr->thousands_separator).PHP_EOL;
                            }
                            if($admstore->show_product_prices){
                                $linkSimplifiedCheckout .= "*" . __("Order Amount") . "*: " . $order->currency_sign .
                                number_format($order->pay_amount * $order->currency_value, $order_curr->decimal_digits,
                                $order_curr->decimal_separator,$order_curr->thousands_separator).PHP_EOL.PHP_EOL;
                            }
                            $linkSimplifiedCheckout .= "_" . __("Order Number") . " - " . $order->order_number .
                            "_".PHP_EOL;
                            $linkSimplifiedCheckout .= "_" . __("Date") . " - " .
                            Carbon\Carbon::now()->toDateTimeString() . "_";

                            $linkMobile = "https:///api.whatsapp.com/send?1=pt_BR&phone=" .
                            $gs->simplified_checkout_number . "&text=" . urlencode($linkSimplifiedCheckout);
                            @endphp



                            @php
                            $linkWpp = "*| " . __('New Order - Simplified Checkout') . " |*" .
                            PHP_EOL.PHP_EOL;

                            if($order->customer_name) {
                            $linkWpp .= "_*" . __("Name") . ": " . $order->customer_name . "*_" .
                            PHP_EOL;
                            }
                            if($order->customer_phone) {
                            $linkWpp .= "_*" . __("Phone") . ": " . $order->customer_phone . "*_" .
                            PHP_EOL;
                            }

                            $linkWpp .= PHP_EOL . "*---------------------------------*".PHP_EOL.PHP_EOL;

                            foreach($tempcart->items as $product) {
                            $linkWpp .= "*" . __("Product") . "*: " . $product['item']['name'].PHP_EOL;
                            $linkWpp .= "*" . __("Quantity") . "*: " . $product['qty'].PHP_EOL;
                            if($admstore->show_product_prices){
                                $linkWpp .= "*" . __("Price") . "*: " . $order->currency_sign .
                                number_format($product['item']['price'] * $order->currency_value,
                                $order_curr->decimal_digits,
                                $order_curr->decimal_separator,$order_curr->thousands_separator).PHP_EOL;
                            }
                            $linkWpp .= (route('front.product', ['slug' =>
                            $product['item']['slug']])).PHP_EOL.PHP_EOL;
                            $linkWpp .= "*---------------------------------*".PHP_EOL.PHP_EOL;
                            }
                            if ($order->tax) {
                            $linkWpp .= "*" . __("Tax") . "*: " . $order->tax . "%" .PHP_EOL;
                            }
                            if ($order->coupon_code) {
                            $linkWpp .= "*" . __("Discount") . "*: " . $order->currency_sign .
                            number_format($order->coupon_discount * $order->currency_value, $order_curr->decimal_digits,
                            $order_curr->decimal_separator,$order_curr->thousands_separator).PHP_EOL;
                            }
                            if($admstore->show_product_prices){
                                $linkWpp .= "*" . __("Order Amount") . "*: " . $order->currency_sign .
                                number_format($order->pay_amount * $order->currency_value, $order_curr->decimal_digits,
                                $order_curr->decimal_separator,$order_curr->thousands_separator).PHP_EOL.PHP_EOL;
                            }
                            $linkWpp .= "_" . __("Order Number") . " - " . $order->order_number .
                            "_".PHP_EOL;
                            $linkWpp .= "_" . __("Date") . " - " .
                            Carbon\Carbon::now()->toDateTimeString() . "_";

                            $link = "https://web.whatsapp.com/send?1=pt_BR&phone=" . $gs->simplified_checkout_number .
                            "&text=" . urlencode($linkWpp);
                            @endphp

<!-- Small modal -->

<div id="mySmallModalLabel" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content mobile">

            <div class="card text-center">
                <div class="card-header">
                    {{__('Checkout')}}
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{__('Start Conversation')}}</h5>
                    <p class="card-text">{{ __("You will be redirected to a new page to continue your order.") }}</p>
                    <div class="mobile-button" style="display:flex; justify-content:center;">
                        <a class="btn btn-success d-block d-lg-none" href="{{$linkMobile}}" style="color: #fff;"
                            target="_blank">{{ __("Accept")}}</a>
                        <button type="button" class="btn btn-danger d-block d-lg-none" style="margin-left: 10px;"
                            data-dismiss="modal">{{ __("Close") }}</button>
                    </div>
                    <div style="display:flex; justify-content:center;">
                        <a class="btn btn-success d-none d-lg-block" href="{{$link}}" style="color: #fff;"
                            target="_blank">{{ __("Accept")}}</a>
                        <button type="button" class="btn btn-danger d-none d-lg-block" style="margin-left: 10px;"
                            data-dismiss="modal">{{ __("Close") }}</button>
                    </div>

                </div>
                <div class="card-footer text-muted">
                    {{ __("Order#") }} {{$order->order_number}}
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    window.onload = function() {
        $('#mySmallModalLabel').modal('show');
        $("#link-simplified-modal").on('click', function(){
            var url = $('#link-simplified').attr('href');
            window.open(url); 
            $('#mySmallModalLabel').modal('hide')
        });
    };

</script>


@endsection
@section('scripts')
<script>
    function copy(that) {
        var inp =document.createElement('input');
        document.body.appendChild(inp);
        inp.value =that.textContent;
        inp.select();
        document.execCommand('copy',false);
        inp.remove();
    }
    $(document).ready(function(){
        if(typeof fbq != 'undefined'){
            var currency_name = "{{ $first_curr->name }}";
            var total = "{{ $order->pay_amount }}";
            console.log(currency_name);
            console.log(total);
            fbq('track', 'Purchase', {
                value: total,
                currency: currency_name
            });
        }
    });
</script>
@endsection