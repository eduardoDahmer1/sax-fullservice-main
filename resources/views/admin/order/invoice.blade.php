@extends('layouts.admin')
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Order Invoice') }} <a class="add-btn" href="javascript:history.back();"><i
                                class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-order-index') }}">{{ __('Orders') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-order-invoice', $order->id) }}">{{ __('Invoice') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="order-table-wrap">
            <div class="invoice-wrap">
                <div class="invoice__title">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="invoice__logo text-left">
                                <img src="{{ $admstore->invoiceLogoUrl }}" alt="">
                            </div>
                        </div>
                        <div class="col-lg-6 text-right">
                            <a class="btn  add-newProduct-btn print" href="{{ route('admin-order-print', $order->id) }}"
                                target="_blank"><i class="fa fa-print"></i> {{ __('Generate Invoice') }}</a>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row invoice__metaInfo mb-4">
                    <div class="col-lg-6">
                        <div class="invoice__orderDetails">
                            <p><strong>{{ __('Order Details') }} </strong></p>
                            <span><strong>{{ __('Invoice Number') }} :</strong>
                                {{ sprintf("%'.08d", $order->id) }}</span><br>
                            <span><strong>{{ __('Order Date') }} :</strong>
                                @php
                                    setlocale(LC_ALL, \App\Helpers\Helper::strLocaleVariations($lang->locale));
                                @endphp
                                {{ $order->created_at->formatLocalized('%d-%b-%Y %T') }}
                            </span><br>
                            <span><strong>{{ __('Order ID') }} :</strong> {{ $order->order_number }}</span><br>
                            <span><strong> {{__('CEC Number')}} :</strong> {{$order->number_cec}}</span><br>
                            
                            @if ($order->dp == 0)
                                <span> <strong>{{ __('Shipping Method') }} :</strong>
                                    @if ($order->shipping == 3)
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
                            @if ($order->shipping != 3)
                                <span> <strong>{{ __('Shipping Type') }} :</strong> {{ $order->shipping_type }}</span><br>
                                <span> <strong>{{ __('Packing Type') }} :</strong> {{ $order->packing_type }}</span><br>
                                @if (!empty($order->puntoentrega))
                                    <span> <strong>{{ __('Delivery Point') }} :</strong>
                                        {{ $order->puntoentrega }}</span><br>
                                @endif
                            @endif
                            <span> <strong>{{ __('Payment Method') }} :</strong> {{ $order->method }}</span>
                            @if (!empty($order->order_note))
                                <br><span> <strong>{{ __('Order Note') }} :</strong> {{ $order->order_note }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row invoice__metaInfo">
                    @if ($order->dp == 0)
                        <div class="col-lg-6">
                            <div class="invoice__shipping">
                                <p><strong>{{ __('Shipping Address') }}</strong></p>
                                <span><strong>{{ __('Customer Name') }}</strong>:
                                    {{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name }}</span><br>
                                <span><strong>{{ __('Address') }}</strong>:
                                    {{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}</span><br>
                                <span><strong>{{ __('Number') }}</strong>:
                                    {{ $order->shipping_address_number == null
                                        ? $order->customer_address_number .
                                            "
                                                                                                    " .
                                            $order->customer_complement
                                        : $order->shipping_address_number .
                                            "
                                                                                                    " .
                                            $order->shipping_complement }}</span><br>
                                <span><strong>{{ __('District') }}</strong>:
                                    {{ $order->shipping_district == null ? $order->customer_district : $order->shipping_district }}</span><br>
                                <span><strong>{{ __('City') }}</strong>:
                                    {{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}</span><br>
                                <span><strong>{{ __('State') }}</strong>:
                                    {{ $order->shipping_state == null ? $order->customer_state : $order->shipping_state }}</span><br>
                                <span><strong>{{ __('Country') }}</strong>:
                                    {{ $order->shipping_country == null ? $order->customer_country : $order->shipping_country }}</span><br>
                                <span><strong>{{ __('Postal Code') }}</strong>:
                                    {{ $order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-6">
                        <div class="buyer">
                            <p><strong>{{ __('Billing Details') }}</strong></p>
                            <span><strong>{{ __('Customer Name') }}</strong>: {{ $order->customer_name }}</span><br>
                            <span><strong>{{ __('Customer Email') }}</strong>: {{ $order->customer_email }}</span><br>
                            <span><strong>{{ __('Customer') . ' ' . $customer_doc_str }}</strong>:
                                {{ $order->customer_document }}</span><br>
                            <span><strong>{{ __('Customer Phone') }}</strong>: {{ $order->customer_phone }}</span><br>
                            <span><strong>{{ __('Address') }}</strong>: {{ $order->customer_address }}</span><br>
                            <span><strong>{{ __('Number') }}</strong>: {{ $order->customer_address_number }}</span><br>
                            <span><strong>{{ __('Customer complement') }}</strong>:
                                {{ $order->customer_complement }}</span><br>
                            <span><strong>{{ __('District') }}</strong>: {{ $order->customer_district }}</span><br>
                            <span><strong>{{ __('City') }}</strong>: {{ $order->customer_city }}</span><br>
                            <span><strong>{{ __('State') }}</strong>: {{ $order->customer_state }}</span><br>
                            <span><strong>{{ __('Country') }}</strong>: {{ $order->customer_country }}</span><br>
                            <span><strong>{{ __('Postal Code') }}</strong>: {{ $order->customer_zip }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="invoice_table">
                            <div class="mr-table">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-hover dt-responsive" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Product') }}</th>
                                                @if ($gs->is_invoice_photo)
                                                    <th>{{ __('Photo') }}</th>
                                                @endif
                                                <th>{{ __('Details') }}</th>
                                                <th>{{ __('Total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $subtotal = 0;
                                                $subtotal_original = 0;
                                                $tax = 0;
                                                $tax_original = 0;
                                            @endphp
                                            @if(!empty($cart['items'])) {
                                            @foreach ($cart['items'] as $product)
                                                <tr>
                                                    <td width="30%">
                                                        <p>{{ $product['item']['name'] }}</p>
                                                        @php $prod = App\Models\Product::find($product['item']['id']); @endphp
                                                        @if (isset($prod))
                                                            <p style="margin-bottom: 0; font-size: 10px">
                                                                {{ __('Product SKU') }} -
                                                                {{ $prod->sku }}</p>
                                                            <p style="font-size: 10px"> {{ __('Reference Code') }} -
                                                                {{ $prod->ref_code }}</p>
                                                        @endif
                                                    </td>
                                                    @if ($gs->is_invoice_photo)
                                                        <td width="10%">
                                                            <img src="{{ asset('storage/images/products') . '/' . $prod->photo }}"
                                                                width="285" alt="">
                                                        </td>
                                                    @endif
                                                    <td>
                                                        @if ($product['size'])
                                                            <p>
                                                                <strong>{{ __('Size') }}: </strong>
                                                                {{ str_replace('-', ' ', $product['size']) }}
                                                            </p>
                                                        @endif
                                                        @if ($product['color'])
                                                            <p>
                                                                <strong>{{ __('color') }} :</strong> <span
                                                                    style="width: 40px; height: 20px; display: block; background: #{{ $product['color'] }};"></span>
                                                            </p>
                                                        @endif
                                                        <p>
                                                            <strong>{{ __('Price') }} :</strong>
                                                            {{ $order->currency_sign }}{{ number_format(
                                                                $product['item']['price'] * $order->currency_value,
                                                                $order_curr->decimal_digits,
                                                                $order_curr->decimal_separator,
                                                                $order_curr->thousands_separator,
                                                            ) }}
                                                        </p>
                                                        <p>
                                                            <small>{{ $first_curr->sign . ' ' . __('Price') }} :
                                                                {{ $first_curr->sign }}{{ number_format(
                                                                    $product['item']['price'],
                                                                    $first_curr->decimal_digits,
                                                                    $first_curr->decimal_separator,
                                                                    $first_curr->thousands_separator,
                                                                ) }}</small>
                                                        </p>
                                                        <p>
                                                            <strong>{{ __('Qty') }} :</strong> {{ $product['qty'] }}
                                                            {{ $product['item']['measure'] }}
                                                        </p>
                                                        @if (!empty($product['customizable_name']))
                                                            <p>
                                                                <strong>{{ __('Custom Name') }}:
                                                                    {{ $product['customizable_name'] }}</strong>
                                                            </p>
                                                        @endif
                                                        @if (!empty($product['customizable_number']))
                                                            <p>
                                                                <strong>{{ __('Custom Number') }}:
                                                                    {{ $product['customizable_number'] }}</strong>
                                                            </p>
                                                        @endif
                                                        @if (!empty($product['keys']))
                                                            @foreach (array_combine(explode(',', $product['keys']), explode('~', $product['values'])) as $key => $value)
                                                                <p>
                                                                    <b>{{ App\Models\Attribute::where('input_name', $key)->first()->name }}
                                                                        : </b> {{ $value }}
                                                                </p>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
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
                                                    </td>
                                                    @php
                                                        $subtotal += $product['price'] * $order->currency_value;
                                                        $subtotal_original += $product['price'];
                                                    @endphp
                                                </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                @if ($gs->is_invoice_photo)
                                                    <td></td>
                                                @endif
                                                <td colspan="2">{{ __('Subtotal') }}</td>
                                                <td>{{ $order->currency_sign }}{{ number_format($subtotal, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}
                                                    <br><small>{{ $first_curr->sign }}{{ number_format($subtotal_original, $first_curr->decimal_digits, $first_curr->decimal_separator, $first_curr->thousands_separator) }}</small>
                                                </td>
                                            </tr>
                                            @if ($order->shipping_cost != 0)
                                                <tr>
                                                    @if ($gs->is_invoice_photo)
                                                        <td></td>
                                                    @endif
                                                    <td colspan="2">{{ __('Shipping') }}</td>
                                                    <td>
                                                        {{ $order->currency_sign }}{{ number_format($order->shipping_cost * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}
                                                        <br><small>{{ $first_curr->sign }}{{ number_format($order->shipping_cost, $first_curr->decimal_digits, $first_curr->decimal_separator, $first_curr->thousands_separator) }}</small>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($order->packing_cost != 0)
                                                <tr>
                                                    @if ($gs->is_invoice_photo)
                                                        <td></td>
                                                    @endif
                                                    <td colspan="2">{{ __('Packaging') }}</td>
                                                    <td>{{ $order->currency_sign }}{{ number_format(
                                                        $order->packing_cost * $order->currency_value,
                                                        $order_curr->decimal_digits,
                                                        $order_curr->decimal_separator,
                                                        $order_curr->thousands_separator,
                                                    ) }}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($order->tax != 0)
                                                <tr>
                                                    @if ($gs->is_invoice_photo)
                                                        <td></td>
                                                    @endif
                                                    <td colspan="2">{{ __('TAX') }}</td>
                                                    @php
                                                        $tax_original = ($subtotal_original / 100) * $order->tax;
                                                        $tax = $order->currency_value != 1 ? floor($tax_original * $order->currency_value) : $tax_original;
                                                    @endphp
                                                    <td>
                                                        {{ $order->currency_sign }}{{ number_format($tax, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}
                                                        <br><small>{{ $first_curr->sign }}{{ number_format($tax_original, $first_curr->decimal_digits, $first_curr->decimal_separator, $first_curr->thousands_separator) }}</small>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($order->coupon_discount != null)
                                                <tr>
                                                    @if ($gs->is_invoice_photo)
                                                        <td></td>
                                                    @endif
                                                    <td colspan="2">
                                                        {{ __('Coupon Discount') }}({{ $order->currency_sign }})</td>
                                                    <td>{{ $order->currency_sign }}{{ number_format(
                                                        $order->coupon_discount * $order->currency_value,
                                                        $order_curr->decimal_digits,
                                                        $order_curr->decimal_separator,
                                                        $order_curr->thousands_separator,
                                                    ) }}
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                @if ($gs->is_invoice_photo)
                                                    <td></td>
                                                @endif
                                                <td colspan="1"></td>
                                                <td>{{ __('Total') }}<br><small>{{ $first_curr->sign . ' ' . __('Total') }}</small>
                                                </td>
                                                <td>
                                                    {{ $order->currency_sign }}{{ number_format($order->pay_amount * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}
                                                    <br><small>{{ $first_curr->sign }}{{ number_format($order->pay_amount, $first_curr->decimal_digits, $first_curr->decimal_separator, $first_curr->thousands_separator) }}</small>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content Area End -->
    </div>
    </div>
    </div>
@endsection
