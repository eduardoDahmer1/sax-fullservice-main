<!DOCTYPE html>
<html>

<head>
    @include('print.__inline-bootstrap')
    @include('print.__custom-style')
</head>

<body>
    <div class="invoice-wrap">
        <br>
        <div>
            <div class="invoice__metaInfo">
                <div class="col-lg-6">
                    <div class="invoice__orderDetails">

                        <p><strong>{{ __('Order Details') }} </strong></p>
                        <span><strong>{{ __('Invoice Number') }} :</strong> {{ sprintf("%'.08d", $order->id)
                            }}</span><br>
                        <span><strong>{{ __('Order Date') }} :</strong> {{ date('d-M-Y',strtotime($order->created_at))
                            }}</span><br>
                        <span><strong>{{ __('Order ID')}} :</strong> {{ $order->order_number }}</span><br>
                        <span><strong> {{__('CEC Number')}} :</strong> {{$order->number_cec}}</span><br>
                        @if($order->dp == 0)
                        <span> <strong>{{ __('Shipping Method') }} :</strong>
                            @if($order->shipping == "pickup")
                            {{ __('Pick Up') }}
                            @else
                            {{ __('Ship To Address') }}
                            @endif
                        </span><br>
                        @endif
                        @if ($order->shipping == 3)
                            <span><strong>{{ __('Retirar em') }} :</strong>
                                {{$order->pickup_location}}
                            </span><br>
                        @endif
                        <span> <strong>{{ __('Payment Method') }} :</strong> {{$order->method}}</span><br>
                        @if($order->method=="Bank Deposit")
                        <div style="margin-top: 10px; font-size: small;">
                            @foreach($bank_accounts as $bank_account)
                            <ul class="list-group" style="margin-top: 10px">
                                <li class="list-group-item" style="padding: 5px;">{{strtoupper($bank_account->name)}}
                                </li>
                                <li class="list-group-item">{!!nl2br(str_replace(" ", " &nbsp;",
                                    $bank_account->info))!!}</li>
                            </ul>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="invoice__metaInfo">
                @if($order->dp == 0)
                <div class="col-lg-6">
                    <div class="invoice__orderDetails">
                        <p><strong>{{ __('Shipping Details') }}</strong></p>
                        <span><strong>{{ __('Customer Name') }}</strong>: {{ $order->shipping_name == null ?
                            $order->customer_name : $order->shipping_name}}</span><br>
                        <span><strong>{{ __('Address') }}</strong>: {{ $order->shipping_address == null ?
                            $order->customer_address : $order->shipping_address }}</span><br>
                        <span><strong>{{ __('City') }}</strong>: {{ $order->shipping_city == null ?
                            $order->customer_city : $order->shipping_city }}</span><br>
                        <span><strong>{{ __('Country') }}</strong>: {{ $order->shipping_country == null ?
                            $order->customer_country : $order->shipping_country }}</span>
                    </div>
                </div>
                @endif

            </div>

            <div class="invoice__metaInfo">
                <div class="col-lg-6">
                    <div class="invoice__orderDetails">
                        <p><strong>{{ __('Billing Details') }}</strong></p>
                        <span><strong>{{ __('Customer Name') }}</strong>: {{ $order->customer_name}}</span><br>
                        <span><strong>{{ __('Address') }}</strong>: {{ $order->customer_address }}</span><br>
                        <span><strong>{{ __('City') }}</strong>: {{ $order->customer_city }}</span><br>
                        <span><strong>{{ __('Country') }}</strong>: {{ $order->customer_country }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="invoice_table">
                <div class="mr-table">
                    <div class="table-responsive">
                        <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead style="border-top:1px solid rgba(0, 0, 0, 0.1) !important;">
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Details') }}</th>
                                    <th>{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $subtotal = 0;
                                $tax = 0;
                                @endphp
                                @foreach($cart['items'] as $product)
                                <tr>
                                    <td width="50%">
                                        @if($product['item']['user_id'] != 0)
                                        @php
                                        $user = App\Models\User::find($product['item']['user_id']);
                                        @endphp
                                        @if(isset($user))
                                        {{ $product['item']['name']}}
                                        @else
                                        {{$product['item']['name']}}
                                        @endif

                                        @else
                                        {{ $product['item']['name']}}
                                        @endif
                                    </td>

                                    <td>
                                        @if($product['size'])
                                        <p>
                                            Size : {{str_replace('-',' ',$product['size'])}}
                                        </p>
                                        @endif
                                        @if($product['color'])
                                        <p>
                                            {{ __('Color') }} : <span
                                                style="margin-left: 40px; width: 20px; height: 0.2px; display: block; border: 10px solid {{$product['color'] == "" ? "
                                                white" : $product['color']}};"></span>
                                        </p>
                                        @endif
                                        <p>
                                            {{ __('Price') }} : {{$order->currency_sign}}{{
                                            number_format($product['item']['price'] * $order->currency_value,
                                            $order_curr->decimal_digits,
                                            $order_curr->decimal_separator,$order_curr->thousands_separator) }}
                                        </p>
                                        <p>
                                            <small>{{ $first_curr->sign." ".__('Price') }} : {{$first_curr->sign}}{{
                                                number_format($product['item']['price'], $first_curr->decimal_digits,
                                                $first_curr->decimal_separator,$first_curr->thousands_separator)
                                                }}</small>
                                        </p>

                                        <p>
                                            {{ __('Qty') }} : {{$product['qty']}} {{ $product['item']['measure'] }}
                                        </p>
                                    </td>

                                    <td style="text-align: end;">{{$order->currency_sign}}{{
                                        number_format($product['price'] * $order->currency_value,
                                        $order_curr->decimal_digits,
                                        $order_curr->decimal_separator,$order_curr->thousands_separator) }}
                                        <br><small>{{$first_curr->sign}}{{ number_format($product['price'],
                                            $first_curr->decimal_digits,
                                            $first_curr->decimal_separator,$first_curr->thousands_separator) }}</small>
                                    </td>
                                    @php
                                    $subtotal += round($product['price'] * $order->currency_value, 2);
                                    @endphp

                                </tr>

                                @endforeach

                                <tr class="semi-border">
                                    <td colspan="1"></td>
                                    <td><strong>{{ __('Subtotal') }}</strong></td>
                                    <td>{{$order->currency_sign}}{{ number_format($subtotal,
                                        $order_curr->decimal_digits,
                                        $order_curr->decimal_separator,$order_curr->thousands_separator) }}</td>

                                </tr>
                                @if($order->shipping_cost != 0)
                                <tr class="no-border">
                                    <td colspan="1"></td>
                                    <td><strong>{{ __('Shipping Cost') }}</strong></td>
                                    <td>{{$order->currency_sign}}{{ number_format($order->shipping_cost *
                                        $order->currency_value, $order_curr->decimal_digits,
                                        $order_curr->decimal_separator,$order_curr->thousands_separator) }}</td>
                                </tr>
                                @endif

                                @if($order->packing_cost != 0)
                                <tr class="no-border">
                                    <td colspan="1"></td>
                                    <td><strong>{{ __('Packaging Cost') }}</strong></td>
                                    <td>{{$order->currency_sign}}{{ number_format($order->packing_cost *
                                        $order->currency_value, $order_curr->decimal_digits,
                                        $order_curr->decimal_separator,$order_curr->thousands_separator) }}</td>
                                </tr>
                                @endif

                                @if($order->tax != 0)
                                <tr class="no-border">
                                    <td colspan="1"></td>
                                    <td><strong>{{ __('TAX') }}</strong></td>

                                    @php
                                    $tax = ($subtotal / 100) * $order->tax;
                                    @endphp

                                    <td>{{$order->currency_sign}}{{number_format($tax, $order_curr->decimal_digits,
                                        $order_curr->decimal_separator,$order_curr->thousands_separator) }}</td>
                                </tr>

                                @endif
                                @if($order->coupon_discount != null)
                                <tr class="no-border">
                                    <td colspan="1"></td>
                                    <td><strong>{{ __('Coupon Discount') }}</strong></td>
                                    <td>{{$order->currency_sign}}{{number_format($order->coupon_discount *
                                        $order->currency_value, $order_curr->decimal_digits,
                                        $order_curr->decimal_separator,$order_curr->thousands_separator) }}</td>
                                </tr>
                                @endif
                                <tr class="final-border">
                                    <td colspan="1"></td>
                                    <td><strong>{{ __('Total') }}</strong></td>
                                    <td>{{$order->currency_sign}}{{ number_format($order->pay_amount *
                                        $order->currency_value, $order_curr->decimal_digits,
                                        $order_curr->decimal_separator,$order_curr->thousands_separator) }}
                                        <br><small>{{$first_curr->sign}}{{ number_format($order->pay_amount,
                                            $first_curr->decimal_digits,
                                            $first_curr->decimal_separator,$first_curr->thousands_separator) }}</small>
                                    </td>
                                </tr>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./wrapper -->



</body>

</html>
