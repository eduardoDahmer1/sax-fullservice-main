@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
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
                <div class="content-box section-padding add-product-1">
                    <div class="top-area">
                        <div class="content">
                            <h4 class="heading">
                                {{ __("THANK YOU FOR YOUR ORDER.") }}
                            </h4>
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

                            $link = "https://web.whatsapp.com/send?1=pt_BR&phone=" . $gs->simplified_checkout_number .
                            "&text=" . urlencode($linkSimplifiedCheckout);
                            @endphp
                            <p class="text"> {{ __("If the conversation page does not open automatically") . " " }} </p>
                            <a class="btn btn-success m-3" id="link-simplified" href="{{$link}}" target="_blank"><span
                                    class="heading" style="font-size: 20px"><i
                                        class="icofont-verification-check"></i>{{ __("Click Here") }}</span></a>
                            <br>
                            <a href="{{ route('front.index') }}" class="link">{{ __("Get Back To Our Homepage") }}</a>
                        </div>
                    </div>
                    <div class="top-area mobile">
                        <div class="content">
                            <h4 class="heading">
                                {{ __("THANK YOU FOR YOUR ORDER.") }}
                            </h4>
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
                            <p class="text"> {{ __("If the conversation page does not open automatically") . " " }} </p>
                            <a class="btn btn-success m-3" id="link-simplified" href="{{$linkMobile}}"
                                target="_blank"><span class="heading" style="font-size: 20px"><i
                                        class="icofont-verification-check"></i>{{ __("Click Here") }}</span></a>
                            <br>
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
                                                <br>
                                                <div class="billing-add-area">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h5>{{ __("Customer Information") }}</h5>
                                                            <address>
                                                                @if($order->customer_name)
                                                                {{__("Name") . ": " . $order->customer_name}}
                                                                @else
                                                                {{__("Anonymous") . ": "}}
                                                                @endif
                                                                <br>
                                                                @if($order->customer_phone)
                                                                {{__("Phone") . ": " . $order->customer_phone}}
                                                                @endif
                                                            </address>
                                                        </div>
                                                        @if($admstore->show_product_prices)
                                                            <div class="col-md-6">
                                                                <h5>{{ __("Order Information") }}</h5>
                                                                <p>
                                                                    @if($order->tax != 0)
                                                                    {{ __('Tax') . ": " }} {{$order->tax . "%"}} <br>
                                                                    @endif
                                                                    @if($order->coupon_discount != 0)
                                                                    {{ __('Discount') . ": " }}
                                                                    {{$order->currency_sign}}{{ number_format($order->coupon_discount * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator,$order_curr->thousands_separator) }}<br>
                                                                    @endif
                                                                    {{ __("Order Amount") . ":" }}
                                                                    {{$order->currency_sign}}{{ number_format($order->pay_amount * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator,$order_curr->thousands_separator) }}<br>
                                                                    {{ __('Total') . ": " }}
                                                                    {{$first_curr->sign}}{{ number_format($order->pay_amount,  $first_curr->decimal_digits, $first_curr->decimal_separator,$first_curr->thousands_separator) }}<br>
                                                                </p>
                                                                <p>{{ __("Order Method") . ": " }} {{$order->method}}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <br>

                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <h4 class="text-center">{{ __("Ordered Products:") }}</h4>
                                                        <thead>
                                                            <tr>
                                                                <th width="60%">{{ __("Name") }}</th>
                                                                <th width="20%">{{ __("Details") }}</th>
                                                            @if($admstore->show_product_prices)
                                                                <th width="10%">{{ __("Price") }}</th>
                                                                <th width="10%">{{ __("Total") }}</th>
                                                            @endif
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
                                                                    @if(!empty($product['keys']))
                                                                    @foreach( array_combine(explode(',',
                                                                    $product['keys']), explode('~', $product['values']))
                                                                    as $key => $value)
                                                                    <b>{{ App\Models\Attribute::where('input_name', $key)->first()->name }}
                                                                        : </b> {{ $value }} <br>
                                                                    @endforeach
                                                                    @endif
                                                                </td>
                                                                @if($admstore->show_product_prices)
                                                                    <td style="text-align: end;">
                                                                        {{$order->currency_sign}}{{number_format($product['item']['price'] * $order->currency_value,  $order_curr->decimal_digits, $order_curr->decimal_separator,$order_curr->thousands_separator)}}<br>
                                                                        <small>{{$first_curr->sign}}{{ number_format($product['item']['price'],  $first_curr->decimal_digits, $first_curr->decimal_separator,$first_curr->thousands_separator) }}</small>
                                                                    </td>
                                                                    <td style="text-align: end;">
                                                                        {{$order->currency_sign}}{{number_format($product['price'] * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator,$order_curr->thousands_separator)}}<br>
                                                                        <small>{{$first_curr->sign}}{{ number_format($product['price'],  $first_curr->decimal_digits, $first_curr->decimal_separator,$first_curr->thousands_separator) }}</small>
                                                                    </td>
                                                                @endif
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
                </div>
                <!-- Ending of Dashboard data-table area -->
            </div>
        </div>
    </div>
    @endif
</section>

<!-- Small modal -->

<div id="mySmallModalLabel" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content mobile">

            <div class="card text-center">
                <div class="card-header">
                    {{__('Simplified Checkout')}}
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