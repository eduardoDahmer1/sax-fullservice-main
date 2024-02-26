<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @foreach ($seos as $seo)
        <meta name="keywords" content="{{ $seo->meta_keys }}">
    @endforeach
    <meta name="author" content="CrowTech">

    <title>{{ $gs->title }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('assets/print/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/print/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assets/print/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/print/css/style.css') }}">
    <link href="{{ asset('assets/print/css/print.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="icon" type="image/png" href="{{ asset('storage/images/' . $gs->favicon) }}">
    <style type="text/css">
        #color-bar {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-left: 5px;
            margin-top: 5px;
        }

        @page {
            size: auto;
            margin: 0mm;
        }

        @page {
            size: A4;
            margin: 0;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 287mm;
            }

            html {
                overflow: scroll;
                overflow-x: hidden;
            }

            ::-webkit-scrollbar {
                width: 0px;
                /* remove scrollbar space */
                background: transparent;
                /* optional: just make scrollbar invisible */
            }
        }
    </style>
</head>

<body onload="window.print();">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Starting of Dashboard data-table area -->
                <div class="section-padding add-product-1">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="product__header">
                                <div class="row reorder-xs">
                                    <div class="col-lg-8 col-md-5 col-sm-5 col-xs-12">
                                        <div class="product-header-title">
                                            <h2>{{ __('Order#') }} {{ $order->order_number }}
                                                [{{ __($order->status) }}]
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="dashboard-content">
                                                <div class="view-order-page" id="print">
                                                    <p class="order-date" style="margin-left: 2%">
                                                        {{ __('Order Date') }}
                                                        {{ date('d-M-Y', strtotime($order->created_at)) }}</p>

                                                    @if ($order->dp == 1)

                                                        <div class="billing-add-area">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h5>{{ __('Billing Address') }}</h5>
                                                                    <address>
                                                                        {{ __('Name:') }}
                                                                        {{ $order->customer_name }}<br>
                                                                        {{ __('Email:') }}
                                                                        {{ $order->customer_email }}<br>
                                                                        {{ $customer_doc_str }}:
                                                                        {{ $order->customer_document }}<br>
                                                                        {{ __('Phone:') }}
                                                                        {{ $order->customer_phone }}<br>
                                                                        {{ __('Address:') }}
                                                                        {{ $order->customer_address }}<br>
                                                                        {{ __('Number:') }}
                                                                        {{ $order->customer_address_number }}
                                                                        {{ $order->customer_complement }}<br>
                                                                        {{ __('District:') }}
                                                                        {{ $order->customer_district }}<br>
                                                                        {{ $order->customer_city }} -
                                                                        {{ $order->customer_state }}<br>
                                                                        {{ $order->customer_country }}<br>
                                                                        {{ $order->customer_zip }}
                                                                        @if ($order->order_note != null)
                                                                            {{ __('Order Note') }}:
                                                                            {{ $order->order_note }}<br>
                                                                        @endif
                                                                    </address>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h5>{{ __('Payment Information') }}</h5>
                                                                    <p>{{ __('Paid Amount:') }}
                                                                        {{ $order->currency_sign }}{{ number_format(
                                                                            $order->pay_amount * $order->currency_value,
                                                                            $order_curr->decimal_digits,
                                                                            $order_curr->decimal_separator,
                                                                            $order_curr->thousands_separator,
                                                                        ) }}
                                                                    </p>
                                                                    <p>{{ $first_curr->sign . ' ' . __('Total:') }}
                                                                        {{ $first_curr->sign }}{{ number_format(
                                                                            $order->pay_amount,
                                                                            $first_curr->decimal_digits,
                                                                            $first_curr->decimal_separator,
                                                                            $first_curr->thousands_separator,
                                                                        ) }}
                                                                    </p>
                                                                    <p>{{ __('Payment Method:') }}
                                                                        {{ $order->method }}</p>

                                                                    @if ($order->method != 'Cash On Delivery')
                                                                        @if ($order->method == 'Stripe')
                                                                            {{ $order->method }}
                                                                            {{ __('Charge ID:') }} <p>
                                                                                {{ $order->charge_id }}</p>
                                                                        @endif
                                                                        {{ $order->method }}
                                                                        {{ __('Transaction ID:') }} <p id="ttn">
                                                                            {{ $order->txnid }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="invoice__metaInfo">

                                                            <div class="col-md-6">
                                                                <h5>{{ __('Billing Address') }}</h5>
                                                                <address>
                                                                    {{ __('Name:') }}
                                                                    {{ $order->customer_name }}<br>
                                                                    {{ __('Email:') }}
                                                                    {{ $order->customer_email }}<br>
                                                                    {{ $customer_doc_str }}:
                                                                    {{ $order->customer_document }}<br>
                                                                    {{ __('Phone:') }}
                                                                    {{ $order->customer_phone }}<br>
                                                                    {{ __('Address:') }}
                                                                    {{ $order->customer_address }}<br>
                                                                    {{ __('Number:') }}
                                                                    {{ $order->customer_address_number }}
                                                                    {{ $order->customer_complement }}<br>
                                                                    {{ __('District:') }}
                                                                    {{ $order->customer_district }}<br>
                                                                    {{ $order->customer_city }} -
                                                                    {{ $order->customer_state }}<br>
                                                                    {{ $order->customer_country }}<br>
                                                                    {{ $order->customer_zip }}
                                                                    @if ($order->order_note != null)
                                                                        {{ __('Order Note') }}:
                                                                        {{ $order->order_note }}<br>
                                                                    @endif
                                                                </address>

                                                                <h5>{{ __('Payment Information') }}</h5>
                                                                @if ($order->tax != 0)
                                                                    <p>{{ __('Tax:') }} {{ $order->tax . '%' }}</p>
                                                                @endif
                                                                @if ($order->coupon_discount != 0)
                                                                    <p>{{ __('Discount:') }}
                                                                        {{ $order->currency_sign }}{{ number_format(
                                                                            $order->coupon_discount * $order->currency_value,
                                                                            $order_curr->decimal_digits,
                                                                            $order_curr->decimal_separator,
                                                                            $order_curr->thousands_separator,
                                                                        ) }}
                                                                    </p>
                                                                @endif
                                                                <p>{{ __('Paid Amount:') }}
                                                                    {{ $order->currency_sign }}{{ number_format(
                                                                        $order->pay_amount * $order->currency_value,
                                                                        $order_curr->decimal_digits,
                                                                        $order_curr->decimal_separator,
                                                                        $order_curr->thousands_separator,
                                                                    ) }}
                                                                </p>
                                                                <p>{{ $first_curr->sign . ' ' . __('Total:') }}
                                                                    {{ $first_curr->sign }}{{ number_format(
                                                                        $order->pay_amount,
                                                                        $first_curr->decimal_digits,
                                                                        $first_curr->decimal_separator,
                                                                        $first_curr->thousands_separator,
                                                                    ) }}
                                                                </p>
                                                                <p>{{ __('Payment Method:') }} {{ $order->method }}
                                                                </p>

                                                                @if ($order->method != 'Cash On Delivery')
                                                                    @if ($order->method == 'Stripe')
                                                                        {{ $order->method }} {{ __('Charge ID:') }}
                                                                        <p>
                                                                            {{ $order->charge_id }}</p>
                                                                    @endif
                                                                    {{ $order->method }} {{ __('Transaction ID:') }}
                                                                    <p id="ttn">
                                                                        {{ $order->txnid }}</p>

                                                                @endif

                                                                @if ($order->method == 'Bank Deposit')
                                                                    <div style="margin-top: 10px; font-size: small;">
                                                                        @foreach ($bank_accounts as $bank_account)
                                                                            <ul class="list-group"
                                                                                style="margin-top: 10px">
                                                                                <li class="list-group-item"
                                                                                    style="padding: 5px;">
                                                                                    {{ strtoupper($bank_account->name) }}
                                                                                </li>
                                                                                <li class="list-group-item">
                                                                                    {!! nl2br(
                                                                                        str_replace(
                                                                                            "
                                                                                                                                                            ",
                                                                                            ' &nbsp;',
                                                                                            $bank_account->info,
                                                                                        ),
                                                                                    ) !!}</li>
                                                                            </ul>
                                                                        @endforeach
                                                                    </div>
                                                                @endif

                                                            </div>

                                                            <div class="col-md-6" style="width: 50%;">
                                                                @if ($order->shipping == 'shipto')
                                                                    <h5>{{ __('Shipping Address') }}</h5>
                                                                    <address>
                                                                        {{ __('Name:') }}
                                                                        {{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name }}<br>
                                                                        {{ __('Phone:') }}
                                                                        {{ $order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone }}<br>
                                                                        {{ __('Address:') }}
                                                                        {{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}<br>
                                                                        {{ $order->shipping_address_number == null ? $order->customer_address_number : $order->shipping_address_number }}<br>
                                                                        {{ $order->shipping_complement == null ? $order->customer_complement : $order->shipping_complement }}<br>
                                                                        {{ $order->shipping_district == null ? $order->customer_district : $order->shipping_district }}<br>
                                                                        {{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}
                                                                        -
                                                                        {{ $order->shipping_state == null ? $order->customer_state : $order->shipping_state }}<br>
                                                                        {{ $order->shipping_country == null ? $order->customer_country : $order->shipping_country }}<br>
                                                                        {{ $order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip }}
                                                                    </address>
                                                                @else
                                                                    <h5>{{ __('PickUp Location') }}</h5>
                                                                    <address>
                                                                        {{ __('Address:') }}
                                                                        {{ $order->pickup_location }}<br>
                                                                    </address>
                                                                @endif

                                                                <h5>{{ __('Shipping Method') }}</h5>
                                                                @if ($order->shipping == 'shipto')
                                                                    <p>{{ __('Ship To Address') }}</p>
                                                                @else
                                                                    <p>{{ __('Pick Up') }}</p>
                                                                @endif

                                                                <p>
                                                                    {{ $order->shipping_type }}:
                                                                    {{ $order->currency_sign }}{{ number_format(
                                                                        $order->shipping_cost * $order->currency_value,
                                                                        $order_curr->decimal_digits,
                                                                        $order_curr->decimal_separator,
                                                                        $order_curr->thousands_separator,
                                                                    ) }}<br>

                                                                    {{ $order->packing_type }}:
                                                                    {{ $order->currency_sign }}{{ number_format(
                                                                        $order->packing_cost * $order->currency_value,
                                                                        $order_curr->decimal_digits,
                                                                        $order_curr->decimal_separator,
                                                                        $order_curr->thousands_separator,
                                                                    ) }}<br>

                                                                    @if ($order->puntoentrega != null)
                                                                        {{ __('Delivery Point') }}:
                                                                        {{ $order->puntoentrega }}
                                                                    @endif
                                                                </p>
                                                            </div>

                                                        </div>

                                                    @endif
                                                    @if ($order->is_qrcode)
                                                        @if (now() < $order->pay42_due_date)
                                                            <div style="display:flex; justify-content:center;">
                                                                <img
                                                                    src="{{ asset(' assets/temp/' . $order->id . '/qrcode.png') }}" />
                                                            </div>
                                                            <div style="display:flex; justify-content:center;">
                                                                <p class="pix"> PIX QR CODE {{ __('Expire in: ') }}
                                                                    {{ $order->pay42_due_date . ' UTC' }}</p>
                                                            </div>
                                                        @else
                                                            <div style="display:flex; justify-content:center;">
                                                                <p class="pix"> PIX QR CODE {{ __('Expired') }}</p>
                                                            </div>
                                                        @endif

                                                    @endif
                                                    <br>
                                                    <br>
                                                    <div class="table-responsive">
                                                        <table id="example" class="table">
                                                            <h4 class="text-center">{{ __('Ordered Products:') }}
                                                            </h4>
                                                            <hr>
                                                            <thead>
                                                                <tr>
                                                                    <th width="10%">{{ __('ID#') }}</th>
                                                                    <th>{{ __('Name') }}</th>
                                                                    <th width="20%">{{ __('Details') }}</th>
                                                                    <th width="20%">{{ __('Price') }}</th>
                                                                    <th width="10%">{{ __('Total') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                @foreach ($cart['items'] as $product)
                                                                    <tr>
                                                                        <td>{{ $product['item']['id'] }}</td>
                                                                        <td>{{ mb_strlen($product['item']['name'], 'utf-8') > 25
                                                                            ? mb_substr($product['item']['name'], 0, 25, 'utf-8') . '...'
                                                                            : $product['item']['name'] }}
                                                                        </td>
                                                                        <td>
                                                                            <b>{{ __('Quantity') }}</b>:
                                                                            {{ $product['qty'] }} <br>
                                                                            @if (!empty($product['size']))
                                                                                <b>{{ __('Size') }}</b>:
                                                                                {{ $product['item']['measure'] }}{{ str_replace('-', ' ', $product['size']) }}
                                                                                <br>
                                                                            @endif
                                                                            @if (!empty($product['color']))
                                                                                <b>{{ __('Color') }}</b>: <span
                                                                                    id="color-bar"
                                                                                    style="border: 10px solid {{ $product['color'] == ''
                                                                                        ? "
                                                                                                                                                                    white"
                                                                                        : '#' . $product['color'] }};"></span>
                                                                            @endif

                                                                            @if (!empty($product['keys']))
                                                                                @foreach (array_combine(explode(',', $product['keys']), explode('~', $product['values'])) as $key => $value)
                                                                                    <b>{{ App\Models\Attribute::where('input_name', $key)->first()->name }}
                                                                                        : </b> {{ $value }}
                                                                                    <br>
                                                                                @endforeach
                                                                            @endif

                                                                        </td>
                                                                        <td style="text-align: end;">
                                                                            {{ $order->currency_sign }}{{ number_format(
                                                                                $product['item']['price'] * $order->currency_value,
                                                                                $order_curr->decimal_digits,
                                                                                $order_curr->decimal_separator,
                                                                                $order_curr->thousands_separator,
                                                                            ) }}
                                                                            <br><small>{{ $first_curr->sign }}{{ number_format(
                                                                                $product['item']['price'],
                                                                                $first_curr->decimal_digits,
                                                                                $first_curr->decimal_separator,
                                                                                $first_curr->thousands_separator,
                                                                            ) }}</small>
                                                                        </td>
                                                                        <td style="text-align: end;">
                                                                            {{ $order->currency_sign }}{{ number_format(
                                                                                $product['price'] * $order->currency_value,
                                                                                $order_curr->decimal_digits,
                                                                                $order_curr->decimal_separator,
                                                                                $order_curr->thousands_separator,
                                                                            ) }}
                                                                            <br><small>{{ $first_curr->sign }}{{ number_format(
                                                                                $product['price'],
                                                                                $first_curr->decimal_digits,
                                                                                $first_curr->decimal_separator,
                                                                                $first_curr->thousands_separator,
                                                                            ) }}</small>
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
                    </div>
                </div>
                <!-- Ending of Dashboard data-table area -->
            </div>
        </div>
    </div>
    <!-- ./wrapper -->
    <!-- ./wrapper -->

    <script type="text/javascript">
        setTimeout(function() {
            window.close();
        }, 500);
    </script>
</body>

</html>
