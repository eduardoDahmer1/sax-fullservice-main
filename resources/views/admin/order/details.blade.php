@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Order Details') }} <a class="add-btn" href="javascript:history.back();"><i
                                class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-order-index') }}">{{ __('Orders') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-order-show', $order->id) }}">{{ __('Order Details') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="order-table-wrap">
            @include('includes.admin.form-both')
            @include('includes.form-success')
            <div class="row">
                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Order Details') }}
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="45%" width="45%">{{ __('Order ID') }}</th>
                                        <td width="10%">:</td>
                                        <td class="45%" width="45%">{{ $order->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Total Product') }}</th>
                                        <td width="10%">:</td>
                                        <td width="45%">{{ $order->totalQty }}</td>
                                    </tr>
                                    @if ($order->shipping_cost != 0)
                                        <tr>
                                            <th width="45%">{{ $order->shipping_type }}</th>
                                            <td width="10%">:</td>
                                            <td width="45%">
                                                {{ $order->currency_sign }}{{ number_format(
                                                    $order->shipping_cost * $order->currency_value,
                                                    $order_curr->decimal_digits,
                                                    $order_curr->decimal_separator,
                                                    $order_curr->thousands_separator,
                                                ) }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($order->packing_cost != 0)
                                        <tr>
                                            <th width="45%">{{ $order->packing_type }}</th>
                                            <td width="10%">:</td>
                                            <td width="45%">
                                                {{ $order->currency_sign }}{{ number_format(
                                                    $order->packing_cost * $order->currency_value,
                                                    $order_curr->decimal_digits,
                                                    $order_curr->decimal_separator,
                                                    $order_curr->thousands_separator,
                                                ) }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th width="45%">{{ __('Total Cost') }}</th>
                                        <td width="10%">:</td>
                                        <td width="45%">
                                            {{ $order->currency_sign }}{{ number_format(
                                                $order->pay_amount * $order->currency_value,
                                                $order_curr->decimal_digits,
                                                $order_curr->decimal_separator,
                                                $order_curr->thousands_separator,
                                            ) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ $first_curr->sign . ' ' . __('Total:') }}</th>
                                        <td width="10%">:</td>
                                        <td width="45%">
                                            {{ $first_curr->sign }}{{ number_format(
                                                $order->pay_amount,
                                                $first_curr->decimal_digits,
                                                $first_curr->decimal_separator,
                                                $first_curr->thousands_separator,
                                            ) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Ordered Date') }}</th>
                                        <td width="10%">:</td>
                                        <td width="45%">
                                            @php
                                                setlocale(LC_ALL, \App\Helpers\Helper::strLocaleVariations($lang->locale));
                                            @endphp
                                            {{ $order->created_at->formatLocalized('%d-%b-%Y %T') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Payment Method') }}</th>
                                        <td width="10%">:</td>
                                        <td width="45%">{{ $order->method }}</td>
                                    </tr>
                                    @if ($order->method != 'Cash On Delivery')
                                        @if ($order->method == 'Stripe')
                                            <tr>
                                                <th width="45%">{{ $order->method }} {{ __('Charge ID') }}</th>
                                                <td width="10%">:</td>
                                                <td width="45%">{{ $order->charge_id }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th width="45%">{{ $order->method }} {{ __('Transaction ID') }}</th>
                                            <td width="10%">:</td>
                                            <td width="45%">{{ $order->txnid }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th width="45%">{{ __('Payment Status') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{!! $order->payment_status == 'Pending'
                                            ? "<span
                                                                                    class='badge badge-danger'>" .
                                                __('Unpaid') .
                                                '</span>'
                                            : "<span
                                                                                    class='badge badge-success'>" .
                                                __('Paid') .
                                                '</span>' !!}</td>
                                    </tr>

                                    <tr>
                                        <th width="45%">{{__('Billing')}}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">
                                            <span>{{$order->billing ? __('Yes') : __('No')}}</span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <th width="45%">{{__('CEC Number')}}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">
                                            <span>{{$order->number_cec}}</span>
                                        </td>
                                    </tr>

                                    @if (!empty($order->order_note))
                                        <tr>
                                            <th width="45%">{{ __('Order Note') }}</th>
                                            <th width="10%">:</th>
                                            <td width="45%">{{ $order->order_note }}</td>
                                        </tr>
                                    @endif
                                    @if (!empty($order->internal_note))
                                        <tr>
                                            <th width="45%">{{ __('Note') }}</th>
                                            <th width="10%">:</th>
                                            <td width="45%">{{ $order->internal_note }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="footer-area">
                            <a href="{{ route('admin-order-invoice', $order->id) }}" class="mybtn1"><i
                                    class="fas fa-eye"></i> {{ __('View Invoice') }}</a>

                            @if ($order->method == 'Bank Deposit' && $order->receipt != null)
                                <br><a href="{{ route('admin-order-receipt', $order->id) }}" class="mybtn1"><i
                                        class="fas fa-search-dollar"></i> {{ __('View Receipt') }}</a>
                            @elseif($order->method == 'Paghiper')
                                <br><a href="{{ route('admin-order-billet-status', $order->id) }}" class="mybtn1"><i
                                        class="fas fa-search-dollar"></i> {{ __('Check Billet Status') }}</a>
                            @endif

                            @if ($admstore->is_melhorenvio && config('features.melhorenvio_shipping'))
                                <a id="request-melhorenvio"
                                    href="{{ route('admin-order-confirm-melhorenvio-package', $order->id) }}"
                                    class="mybtn1 ml-5">
                                    <i class="fas fa-shipping-fast"></i>
                                    {{ __('Request Melhor Envio') }}
                                </a>
                            @endif

                            @if ($admstore->is_aex && config('features.aex_shipping'))
                                <a id="request-aex" href="{{ route('admin-order-select-aex-city', $order->id) }}"
                                    class="mybtn1 ml-5">
                                    <i class="fas fa-shipping-fast"></i>
                                    {{ __('Request AEX') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Billing Details') }}
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th width="45%">{{ __('Name') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ $order->customer_name }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Email') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ $order->customer_email }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ $customer_doc_str }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ $order->customer_document }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Phone') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ $order->customer_phone }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Address') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ $order->customer_address }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Number') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ $order->customer_address_number }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Customer complement') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ $order->customer_complement }}
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('District') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ $order->customer_district }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('City') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ $order->customer_city }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('State') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ $order->customer_state }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Country') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ $order->customer_country }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Postal Code') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ $order->customer_zip }}</td>
                                    </tr>
                                    @if ($order->tax != 0)
                                        <tr>
                                            <th width="45%">{{ __('Tax') }}</th>
                                            <th width="10%">:</th>
                                            <td width="45%">{{ $order->tax . '%' }}</td>
                                        </tr>
                                    @endif
                                    @if ($order->coupon_code != null)
                                        <tr>
                                            <th width="45%">{{ __('Coupon Code') }}</th>
                                            <th width="10%">:</th>
                                            <td width="45%">{{ $order->coupon_code }}</td>
                                        </tr>
                                    @endif
                                    @if ($order->coupon_discount != null)
                                        <tr>
                                            <th width="45%">{{ __('Coupon Discount') }}</th>
                                            <th width="10%">:</th>
                                            @if ($admstore->currency_format == 0)
                                                <td width="45%">
                                                    {{ $order->currency_sign }}{{ number_format(
                                                        $order->coupon_discount * $order->currency_value,
                                                        $order_curr->decimal_digits,
                                                        $order_curr->decimal_separator,
                                                        $order_curr->thousands_separator,
                                                    ) }}
                                                </td>
                                            @else
                                                <td width="45%">
                                                    {{ number_format(
                                                        $order->coupon_discount * $order->currency_value,
                                                        $order_curr->decimal_digits,
                                                        $order_curr->decimal_separator,
                                                        $order_curr->thousands_separator,
                                                    ) }}{{ $order->currency_sign }}
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                    @if ($order->affilate_user != null)
                                        <tr>
                                            <th width="45%">{{ __('Affilate User') }}</th>
                                            <th width="10%">:</th>
                                            <td width="45%">{{ $order->affilate_user }}</td>
                                        </tr>
                                    @endif
                                    @if ($order->affilate_charge != null)
                                        <tr>
                                            <th width="45%">{{ __('Affilate Charge') }}</th>
                                            <th width="10%">:</th>
                                            @if ($admstore->currency_format == 0)
                                                <td width="45%">
                                                    {{ $order->currency_sign }}{{ $order->affilate_charge }}</td>
                                            @else
                                                <td width="45%">
                                                    {{ $order->affilate_charge }}{{ $order->currency_sign }}</td>
                                            @endif
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if ($order->dp == 0)
                    <div class="col-lg-6">
                        <div class="special-box">
                            <div class="heading-area">
                                <h4 class="title">
                                    {{ __('Shipping Details') }}
                                </h4>
                            </div>
                            <div class="table-responsive-sm">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th width="45%"><strong>{{ __('Packing Type') }}:</strong></th>
                                            <th width="10%">:</th>
                                            <td>{{ $order->packing_type }}</td>
                                        </tr>
                                        @if ($order->shipping == 3)
                                            <tr>
                                                <th width="45%"><strong>{{ __('Pickup Location') }}:</strong></th>
                                                <th width="10%">:</th>
                                                <td width="45%">{{ $order->pickup_location }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <th width="45%"><strong>{{ __('Shipping Type') }}:</strong></th>
                                                <th width="10%">:</th>
                                                <td>{{ $order->shipping_type }}</td>
                                            </tr>
                                            @if ($order->puntoentrega != null)
                                                <tr>
                                                    <th width="45%"><strong>{{ __('Delivery Point') }}:</strong></th>
                                                    <th width="10%">:</th>
                                                    <td>{{ $order->puntoentrega }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th width="45%"><strong>{{ __('Name') }}:</strong></th>
                                                <th width="10%">:</th>
                                                <td>{{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="45%"><strong>{{ __('Phone') }}:</strong></th>
                                                <th width="10%">:</th>
                                                <td width="45%">
                                                    {{ $order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="45%"><strong>{{ __('Address') }}:</strong></th>
                                                <th width="10%">:</th>
                                                <td width="45%">
                                                    {{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="45%"><strong>{{ __('Number') }}:</strong></th>
                                                <th width="10%">:</th>
                                                <td width="45%">
                                                    {{ $order->shipping_address_number == null
                                                        ? $order->customer_address_number .
                                                            "
                                                                                            " .
                                                            $order->customer_complement
                                                        : $order->shipping_address_number .
                                                            "
                                                                                            " .
                                                            $order->shipping_complement }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="45%"><strong>{{ __('City') }}:</strong></th>
                                                <th width="10%">:</th>
                                                <td width="45%">
                                                    {{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="45%"><strong>{{ __('State') }}:</strong></th>
                                                <th width="10%">:</th>
                                                <td width="45%">
                                                    {{ $order->shipping_state == null ? $order->customer_state : $order->shipping_state }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="45%"><strong>{{ __('Country') }}:</strong></th>
                                                <th width="10%">:</th>
                                                <td width="45%">
                                                    {{ $order->shipping_country == null ? $order->customer_country : $order->shipping_country }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="45%"><strong>{{ __('Postal Code') }}:</strong></th>
                                                <th width="10%">:</th>
                                                <td width="45%">
                                                    {{ $order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip }}
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                @if(!empty($cart['items']))
                    @foreach ($cart['items'] as $key => $product)
                        @if (!empty($product['customizable_name']) ||
                            !empty($product['customizable_number']) ||
                            !empty($product['customizable_logo']) ||
                            !empty($product['customizable_gallery']))
                            <div class="col-lg-6">
                                <div class="special-box">
                                    <div class="heading-area">
                                        <h4 class="title">
                                            {{ __('Custom Products') }}
                                        </h4>
                                    </div>
                                    <div class="table-responsive-sm">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th width="45%"><strong>{{ __('Product Title') }}:</strong></th>
                                                    <th width="10%">:</th>
                                                    <td><a target="_blank"
                                                            href="{{ route('front.product', $product['item']['slug']) }}">{{ mb_strlen($product['item']['name'], 'utf-8') > 30
                                                                ? mb_substr($product['item']['name'], 0, 30, 'utf-8') . '...'
                                                                : $product['item']['name'] }}</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="45%"><strong>{{ __('Product SKU') }}:</strong></th>
                                                    <th width="10%">:</th>
                                                    @php $prod = App\Models\Product::find($product['item']['id']); @endphp
                                                    @if (isset($prod))
                                                        <td>{{ $prod->sku }}</td>
                                                    @endif
                                                </tr>
                                                @if (env('ENABLE_CUSTOM_PRODUCT') || env('ENABLE_CUSTOM_PRODUCT_NUMBER'))
                                                    @if (!empty($product['customizable_name']))
                                                        <tr>
                                                            <th width="45%"><strong>{{ __('Custom Name') }}:</strong></th>
                                                            <th width="10%">:</th>
                                                            <td>{{ $product['customizable_name'] }}</td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                @if (env('ENABLE_CUSTOM_PRODUCT_NUMBER'))
                                                    @if (!empty($product['customizable_number']))
                                                        <tr>
                                                            <th width="45%"><strong>{{ __('Custom Number') }}:</strong>
                                                            </th>
                                                            <th width="10%">:</th>
                                                            <td>{{ $product['customizable_number'] }}</td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                @if (env('ENABLE_CUSTOM_PRODUCT'))
                                                    @if (!empty($product['customizable_logo']))
                                                        <tr class="mb-2">
                                                            <th width="45%"><strong>{{ __('Custom Logo') }}:</strong></th>
                                                            <th width="10%">:</th>
                                                            <td width="45%">
                                                                <a href="{{ route('admin-customprod-download', $product['customizable_logo']) }}"
                                                                    class="{{ isset($product['customizable_logo']) ? '' : 'collapse' }}">
                                                                    <button class="btn btn uploadLogoBtn">
                                                                        <p>
                                                                            <i class="fa fa-download"></i>
                                                                            {{ __('Download File') }}
                                                                        </p>
                                                                    </button>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($product['customizable_gallery']))
                                                        <tr>
                                                            <th width="45%">
                                                                <strong>{{ __('Custom Gallery Image') }}:</strong></th>
                                                            <th width="10%">:</th>
                                                            @php $gal = App\Models\CategoryGallery::where('customizable_gallery', $product['customizable_gallery'])->first(); @endphp
                                                            <td>
                                                                <div style="display:inline-block;">
                                                                    <div
                                                                        style="position:relative;display:flex;justify-content:center;align-items:center;">
                                                                        @if (isset($gal))
                                                                            <span
                                                                                class="galleryId">{{ $gal->id }}</span>
                                                                        @endif
                                                                        <img src="{{ asset('storage/images/galleries/' . $product['customizable_gallery']) }}"
                                                                            style="width:60px;border-radius:30px;"
                                                                            alt="">
                                                                    </div>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                    @endif
                                                @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="row">
                <div class="col-lg-12 order-details-table">
                    <div class="mr-table">
                        <h4 class="title">{{ __('Products Ordered') }}</h4>
                        <div class="table-responsiv">
                            <table id="example2" class="table table-hover dt-responsive" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                    <tr>
                                        <th width="25%" style="text-align: center;">{{ __('Product ID#') }}</th>
                                        <th width="25%">{{ __('Product Title') }}</th>
                                        <th width="25%">{{ __('Details') }}</th>
                                        <th width="25%">{{ __('Total Price') }}</th>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(!empty($cart['items']))
                                    @foreach ($cart['items'] as $key => $product)
                                        <tr>
                                            <td><input type="hidden" value="{{ $key }}"
                                                    style="text-align: center">{{ $product['item']['id'] }}</td>
                                            <td>
                                                <input type="hidden" value="{{ $product['license'] }}">
                                                <a target="_blank"
                                                    href="{{ route('front.product', $product['item']['slug']) }}">{{ mb_strlen($product['item']['name'], 'utf-8') > 30
                                                        ? mb_substr($product['item']['name'], 0, 30, 'utf-8') . '...'
                                                        : $product['item']['name'] }}</a>
                                                @if ($product['license'] != '')
                                                    <a href="javascript:;" data-toggle="modal"
                                                        data-target="#confirm-delete" class="btn btn-info product-btn"
                                                        id="license" style="padding: 5px 12px;"><i
                                                            class="fa fa-eye"></i> {{ __('View License') }}</a>
                                                @endif
                                                @php $prod = App\Models\Product::find($product['item']['id']); @endphp
                                                @if (isset($prod))
                                                    <p style="margin-bottom: 0; font-size: 10px"> {{ __('Product SKU') }}
                                                        -
                                                        {{ $prod->sku }}</p>
                                                    <p style="font-size: 10px"> {{ __('Reference Code') }} -
                                                        {{ $prod->ref_code }}</p>
                                                @endif
                                            </td>
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
                                                @if (isset($product['material']))
                                                    @if (!empty($product['material']))
                                                        <p>
                                                            <strong>{{ __('Material') }}: </strong>
                                                            {{ str_replace('-', ' ', $product['material']) }}
                                                        </p>
                                                    @endif
                                                @endif
                                                <p>
                                                    <strong>{{ __('Price') }} :</strong>
                                                    {{ $order->currency_sign }}{{ number_format(
                                                        $product['item']['price'] * $order->currency_value,
                                                        $order_curr->decimal_digits,
                                                        $order_curr->decimal_separator,
                                                        $order_curr->thousands_separator,
                                                    ) }}
                                                    <small><br>{{ $first_curr->sign . ' ' . __('Price') }} :
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
                                                @if (!empty($product['keys']))
                                                    @foreach (array_combine(explode(',', $product['keys']), explode('~', $product['values'])) as $key => $value)
                                                        <p>
                                                            <b>{{ App\Models\Attribute::where('input_name', $key)->first()->name }}
                                                                :
                                                            </b> {{ $value }}
                                                        </p>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td style="text-align: left;">
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
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if ($admstore->is_melhorenvio && config('features.melhorenvio_shipping'))
                <div class="row">
                    <div class="col-lg-12 order-details-table">
                        <div class="mr-table">
                            <h4 class="title">{{ __('Melhor Envio Requests') }}</h4>
                            <div class="table-responsiv">
                                <table id="melhorenvio_table" class="table table-hover dt-responsive" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th width="20%">{{ __('Protocol') }}</th>
                                            <th width="15%" style="text-align: center;">{{ __('Service') }}</th>
                                            <th width="15%">{{ __('Price') }}</th>
                                            <th width="15%">{{ __('Tracking') }}</th>
                                            <th width="15%" style="text-align: center;">{{ __('Status') }}</th>
                                            <th width="20%" style="text-align: center;">{{ __('Options') }}</th>
                                        </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->melhorenvio_requests as $request)
                                            <tr>
                                                <td>{{ $request->protocol }}</td>
                                                <td style="text-align: center;">
                                                    {{ $request->service->company->name }}
                                                    <br><small>{{ $request->service->name }}</small>
                                                </td>
                                                <td>R${{ number_format($request->price, 2, ',', '.') }}</td>
                                                <td><a href="https://www.melhorrastreio.com.br/meu-rastreio/{{ $request->tracking }}"
                                                        target="_blank">{{ $request->tracking }}</a></td>
                                                <td style="text-align: center;">
                                                    @if ($request->status == 'pending')
                                                        <span class="badge badge-secondary">{{ __('Pending') }}</span>
                                                    @elseif($request->status == 'released')
                                                        <span class="badge badge-info">{{ __('Released') }}</span>
                                                    @elseif($request->status == 'posted')
                                                        <span class="badge badge-primary">{{ __('Posted') }}</span>
                                                    @elseif($request->status == 'delivered')
                                                        <span class="badge badge-success">{{ __('Delivered') }}</span>
                                                    @elseif($request->status == 'canceled')
                                                        <span class="badge badge-danger">{{ __('Canceled') }}</span>
                                                    @elseif($request->status == 'undelivered')
                                                        <span class="badge badge-warning">{{ __('Undelivered') }}</span>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="">
                                                        @php
                                                            $disable = !empty($request->posted_at) || !empty($request->canceled_at) || !empty($request->expired_at) || !empty($request->delivered_at);
                                                        @endphp
                                                        <a class="btn {{ empty($request->preview_url) ? ' disabled' : '' }}"
                                                            href="{{ $request->preview_url }}" target="_blank"
                                                            data-toggle="tooltip" title="{{ __('Preview') }}">
                                                            <i class="fas fa-eye h5"></i>
                                                        </a>
                                                        <a class="btn {{ !empty($request->generated_at) || $disable ? ' disabled' : '' }}"
                                                            href="{{ route('admin-order-generate-melhorenvio', $request->id) }}"
                                                            data-toggle="tooltip" title="{{ __('Generate') }}">
                                                            <i class="fas fa-check-circle h5"></i>
                                                        </a>
                                                        <a class="btn {{ empty($request->generated_at) || empty($request->print_url) || $disable
                                                            ? "
                                                                                                        disabled"
                                                            : '' }}"
                                                            href="{{ $request->print_url }}" target="_blank"
                                                            data-toggle="tooltip" title="{{ __('Print') }}">
                                                            <i class="fas fa-print h5"></i>
                                                        </a>
                                                        <a class="btn {{ $disable ? ' disabled' : '' }}"
                                                            href="{{ route('admin-order-cancel-melhorenvio', $request->id) }}"
                                                            data-toggle="tooltip" title="{{ __('Cancel') }}">
                                                            <i class="fas fa-trash-alt h5"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">

                @if ($order->method != 'Simplified')
                    <div class="col-lg-12 text-center mt-2">
                        <a class="btn sendEmail send" href="{{ route('admin-order-send-order', $order->id) }}"
                            class="send" data-email="{{ $order->customer_email }}">
                            <i class="fa fa-paper-plane"></i> {{ __('Send Order') }}
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
    <!-- Main Content Area End -->

    {{-- LICENSE MODAL --}}
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('License Key') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">{{ __('The License Key is') }} : <span id="key"></span> <a
                            href="javascript:;" id="license-edit">{{ __('Edit License') }}</a><a href="javascript:;"
                            id="license-cancel" class="showbox">{{ __('Cancel') }}</a></p>
                    <form method="POST" action="{{ route('admin-order-license', $order->id) }}" id="edit-license"
                        style="display: none;">
                        {{ csrf_field() }}
                        <input type="hidden" name="license_key" id="license-key" value="">
                        <div class="form-group text-center">
                            <input type="text" name="license" placeholder="{{ __('Enter New License Key') }}"
                                style="width: 40%; border: none;" required=""><input type="submit" name="submit"
                                class="btn btn-primary" style="border-radius: 0; padding: 2px; margin-bottom: 2px;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- LICENSE MODAL ENDS --}}

    {{-- MESSAGE MODAL --}}
    <div class="sub-categori">
        <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vendorformLabel">{{ __('Send Email') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="contact-form">
                                        <form id="emailreply">
                                            {{ csrf_field() }}
                                            <ul>
                                                <li>
                                                    <input type="email" class="input-field eml-val" id="eml"
                                                        name="to" placeholder="{{ __('Email') }} *"
                                                        value="" required="">
                                                </li>
                                                <li>
                                                    <input type="text" class="input-field" id="subj"
                                                        name="subject" placeholder="{{ __('Subject') }} *"
                                                        required="">
                                                </li>
                                                <li>
                                                    <textarea class="input-field textarea" name="message" id="msg" placeholder="{{ __('Your Message') }} *"
                                                        required=""></textarea>
                                                </li>
                                            </ul>
                                            <button class="submit-btn" id="emlsub"
                                                type="submit">{{ __('Send Email') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- MESSAGE MODAL ENDS --}}

    {{-- ORDER MODAL --}}
    <div class="modal fade" id="confirm-delete2" tabindex="-1" role="dialog" aria-labelledby="modal1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ $admstore->adminLoaderUrl }}" alt="">
                </div>
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('Update Status') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">{{ __("You are about to update the order's status.") }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-success btn-ok order-btn">{{ __('Proceed') }}</a>
                </div>
            </div>
        </div>
    </div>
    {{-- ORDER MODAL ENDS --}}

@endsection

@section('scripts')
    <script type="text/javascript">
        $('#example2').dataTable({
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
            },
            "ordering": false,
            'lengthChange': false,
            'searching': false,
            'ordering': false,
            'info': false,
            'autoWidth': false,
            'responsive': true
        });
    </script>

    @if ($admstore->is_melhorenvio && config('features.melhorenvio_shipping'))
        <script>
            $('#melhorenvio_table').dataTable({
                language: {
                    url: '{{ $datatable_translation }}',
                    processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
                },
                "ordering": false,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'info': false,
                'autoWidth': false,
                'responsive': true
            });
        </script>
    @endif

    <script type="text/javascript">
        $(document).on('click', '#license', function(e) {
            var id = $(this).parent().find('input[type=hidden]').val();
            var key = $(this).parent().parent().find('input[type=hidden]').val();
            $('#key').html(id);
            $('#license-key').val(key);
        });
        $(document).on('click', '#license-edit', function(e) {
            $(this).hide();
            $('#edit-license').show();
            $('#license-cancel').show();
        });
        $(document).on('click', '#license-cancel', function(e) {
            $(this).hide();
            $('#edit-license').hide();
            $('#license-edit').show();
        });
        $(document).on('submit', '#edit-license', function(e) {
            e.preventDefault();
            $('button#license-btn').prop('disabled', true);
            $.ajax({
                method: 'POST',
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if ((data.errors)) {
                        for (var error in data.errors) {
                            $.notify('<li>' + data.errors[error] + '</li>', 'error');
                        }
                    } else {
                        $.notify(data, 'success');
                        $('button#license-btn').prop('disabled', false);
                        $('#confirm-delete').modal('toggle');
                    }
                }
            });
        });
    </script>
@endsection
