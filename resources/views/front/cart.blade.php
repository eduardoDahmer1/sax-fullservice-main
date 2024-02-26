@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')

@section('styles')
    @parent
    <style>
        #freight-form input {
            width: 190px;
            height: 35px;
            background: #f3f8fc;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0px 10px;
            font-size: 14px;
        }

        #freight-form button {
            height: 35px;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.15);
            font-size: 14px;
            text-transform: uppercase;
            cursor: pointer;
            -webkit-transition: all 0.3s ease-in;
            -o-transition: all 0.3s ease-in;
            transition: all 0.3s ease-in;
        }

        #freight-form button:hover {
            color: #fff;
        }

        #shipping-area-aex .radio-design,
        #shipping-area .radio-design {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding-top: 10px;
            padding-right: 10px;
            cursor: default;
        }

        #shipping-area-aex .radio-design input,
        #shipping-area .radio-design input {
            cursor: default;
        }

        #shipping-area-aex .radio-design .checkmark,
        #shipping-area .radio-design .checkmark {
            display: none;
        }
    </style>
@endsection

@section('content')

    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pages">
                        <li>
                            <a href="{{ route('front.index') }}">
                                {{ __('Home') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('front.cart') }}">
                                {{ __('Cart') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <!-- Cart Area Start -->
    <section class="cartpage">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="preloader" id="preloader_cart"
                        style="background: url({{ asset('storage/images/' . $gs->loader) }}) no-repeat scroll center center;
            background-color: rgba(0,0,0,0.5);
            display: none">
                    </div>
                    <div class="left-area">
                        <div class="cart-table">
                            <table class="table">
                                @include('includes.form-success')
                                <thead>
                                    <tr>
                                        <th>{{ __('Product Name') }}</th>
                                        <th width="30%">{{ __('Details') }}</th>
                                        @if($admstore->show_product_prices)
                                            <th>{{ __('Unit Price') }}</th>
                                            <th>{{ __('Sub Total') }}</th>
                                        @endif
                                        <th><i class="icofont-close-squared-alt d-flex justify-content-end"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (Session::has('cart'))
                                        @foreach ($products as $product)
                                            @php
                                                $custom_item_id =
                                                    $product['item']['id'] .
                                                    $product['size'] .
                                                    $product['color'] .
                                                    $product['material'] .
                                                    $product['customizable_gallery'] .
                                                    $product['customizable_name'] .
                                                    $product['customizable_number'] .
                                                    $product['customizable_logo'] .
                                                    str_replace(
                                                        str_split('
                                    ,'),
                                                        '',
                                                        $product['values'],
                                                    );
                                            $custom_item_id = str_replace(['\'', '"', ',', '.', ' ', ';', '<', '>'], '', $custom_item_id); @endphp <tr class="cremove{{ str_replace(['~', '/', '-'],'',$custom_item_id) }}">
                                                <td class="product-img">
                                                    <div class="item">
                                                        <img src="{{ filter_var($product['item']['photo'], FILTER_VALIDATE_URL)
                                                            ? $product['item']['photo']
                                                            : asset('storage/images/products/' . $product['item']['photo']) }}"
                                                            alt="product">
                                                        <p class="name"><a
                                                                href="{{ route('front.product', $product['item']['slug']) }}">{{ mb_strlen($product['item']->name, 'utf-8') > 35
                                                                    ? mb_substr($product['item']->name, 0, 35, 'utf-8') . '...'
                                                                    : $product['item']->name }}</a>
                                                        </p>
                                                        @if (!empty($product['item']->ship))
                                                            <p>{{ __('Estimated shipping time') }}:
                                                                {{ $product['item']->ship }}</p>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if (!empty($product['size']))
                                                        <b>{{ __('Size') }}</b>:
                                                        {{ $product['item']['measure'] }}{{ str_replace('-', ' ', $product['size']) }}
                                                        <br>
                                                    @endif
                                                    @if (!empty($product['material']))
                                                        <b>{{ __('Material') }}</b>:
                                                        {{ $product['item']['measure'] }}{{ str_replace('-', ' ', $product['material']) }}
                                                        <br>
                                                    @endif
                                                    @if (!empty($product['color']))
                                                        <div class="d-flex mt-2">
                                                            <b>{{ __('Color') }}</b>: <span id="color-bar"
                                                                style="border: 10px solid #{{ $product['color'] == '' ? ' white' : $product['color'] }};"></span>
                                                        </div>
                                                    @endif

                                                    @if (!empty($product['keys']))
                                                        @foreach (array_combine(explode(',', $product['keys']), explode('~', $product['values'])) as $key => $value)
                                                            <b>{{ App\Models\Attribute::where('input_name', $key)->first()->name }}
                                                                : </b>
                                                            {{ $value }} <br>
                                                        @endforeach
                                                    @endif

                                                    @if (env('ENABLE_CUSTOM_PRODUCT') || env('ENABLE_CUSTOM_PRODUCT_NUMBER'))
                                                        @if (!empty($product['customizable_name']))
                                                            <b>{{ __('Custom Name') }}</b>:
                                                            <p>{{ $product['customizable_name'] }}</p>
                                                        @endif
                                                    @endif

                                                    @if (env('ENABLE_CUSTOM_PRODUCT'))
                                                        @if (!empty($product['customizable_gallery']))
                                                            <div class="d-flex mt-2">
                                                                <b>{{ __('Photo') }}</b>:
                                                                <figure>
                                                                    <img src="{{ asset('storage/images/galleries/' . $product['customizable_gallery']) }}"
                                                                        style="width: 45px; height:auto; border-radius: 30px; margin-left: 5px; margin-top: -10px; "></img>
                                                                </figure>
                                                            </div>
                                                        @endif

                                                        @if (!empty($product['customizable_logo']))
                                                            <div class="d-flex mt-4">
                                                                <b>{{ __('Logo') }}</b>:
                                                                <img src="{{ asset('storage/images/custom-logo/' . $product['customizable_logo']) }}"
                                                                    style="width: 45px; margin-left: 5px; margin-top: -10px; "></img>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    @if (env('ENABLE_CUSTOM_PRODUCT_NUMBER'))
                                                        @if (!empty($product['customizable_number']))
                                                            <b>{{ __('Custom Number') }}</b>:
                                                            <p>{{ $product['customizable_number'] }}</p>
                                                        @endif
                                                    @endif
                                                </td>

                                                @if($admstore->show_product_prices)
                                                    <td class="unit-price quantity">
                                                        @if($product['item']['promotion_price'] > 0 && $product['item']['promotion_price'] < $product['item']['price'])
                                                            <p class="product-unit-price">
                                                                {{ App\Models\Product::convertPrice($product['item']['promotion_price']) }}
                                                            </p>
                                                            <p class="product-unit-price" style="font-size: smaller;">
                                                                {{ App\Models\Product::signFirstPrice($product['item']['promotion_price']) }}
                                                            </p>
                                                        @else
                                                            <p class="product-unit-price">
                                                                {{ App\Models\Product::convertPrice($product['item']['price']) }}
                                                            </p>
                                                            <p class="product-unit-price" style="font-size: smaller;">
                                                                {{ App\Models\Product::signFirstPrice($product['item']['price']) }}
                                                            </p>
                                                        @endif
                                                        
                                                        @if ($product['item']['type'] == 'Physical')
                                                            <div class="qty">
                                                                <ul>
                                                                    <input type="hidden" class="prodid"
                                                                        value="{{ $product['item']['id'] }}">
                                                                    <input type="hidden" class="itemid"
                                                                        value="{{ $custom_item_id }}">
                                                                    <input type="hidden" class="size_qty"
                                                                        value="{{ $product['size_qty'] }}">
                                                                    <input type="hidden" class="color_qty"
                                                                        value="{{ $product['color_qty'] }}">
                                                                    <input type="hidden" class="material_qty"
                                                                        value="{{ $product['material_qty'] }}">
                                                                    <input type="hidden" class="max_quantity"
                                                                        value="{{ $product['max_quantity'] }}">

                                                                    <input type="hidden" class="size_price"
                                                                        value="{{ $product['item']['price'] }}">
                                                                    <input type="hidden" class="material_price"
                                                                        value="{{ $product['item']['price'] }}">
                                                                    <input type="hidden" class="color_price"
                                                                        value="{{ $product['item']['price'] }}">
                                                                    
                                                                    
                                                                    <li>
                                                                        <span class="qtminus1 reducing">
                                                                            <i class="icofont-minus"></i>
                                                                        </span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="qttotal1"
                                                                            id="qty{{ $custom_item_id }}">{{ $product['qty'] }}</span>
                                                                    </li>

                                                                    <li>
                                                                        <span class="qtplus1 adding">
                                                                            <i class="icofont-plus"></i>
                                                                        </span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @endif
                                                @endif
                                                    @if ($product['size_qty'])
                                                        <input type="hidden" id="stock{{ $custom_item_id }}"
                                                            value="0">
                                                        <input type="hidden" id="max_quantity{{ $custom_item_id }}"
                                                            value="{{ $product['max_quantity'] }}">
                                                    @elseif($product['item']['type'] != 'Physical')
                                                        <input type="hidden" id="stock{{ $custom_item_id }}"
                                                            value="1">
                                                    @else
                                                        <input type="hidden" id="stock{{ $custom_item_id }}"
                                                            value="0">
                                                    @endif
                                                </td>

                                                @if($admstore->show_product_prices)
                                                    <td class="total-price">
                                                        @if($product['item']['promotion_price'] > 0 && $product['item']['promotion_price'] < $product['item']['price'])
                                                            <p id="prc{{ $custom_item_id }}">
                                                                {{ App\Models\Product::convertPrice($product['item']['promotion_price'] * $product['qty']) }}
                                                            </p>
                                                        @else
                                                            <p id="prc{{ $custom_item_id }}">
                                                                {{ App\Models\Product::convertPrice($product['price']) }}
                                                            </p>
                                                        @endif
                                                    </td>
                                                @endif
                                                
                                                <td>
                                                    <div class="d-flex justify-content-end">
                                                        <span class="removecart cart-remove"
                                                        data-class="cremove{{ str_replace(['~', '/', '-'],'',$custom_item_id) }}"
                                                        data-href="{{ route('product.cart.remove', $custom_item_id) }}"><i
                                                            class="icofont-ui-delete"></i>
                                                    </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if (Session::has('cart'))
                <div class="col-lg-4">
                        @if($admstore->show_product_prices)
                            <div class="right-area">
                                <div class="order-box">
                                    <h4 class="title">{{ __('PRICE DETAILS') }}</h4>
                                    <ul class="order-list">
                                        <li>
                                            <p>
                                                {{ __('Total MRP') }}
                                            </p>
                                            <P>
                                                <b
                                                    class="cart-total">{{ Session::has('cart') ? App\Models\Product::convertPrice($totalPrice) : '0.00' }}</b>
                                            </P>
                                        </li>
                                        <li>
                                            <p>
                                                {{ __('Tax') }}
                                            </p>
                                            <P>
                                                <b>{{ $tx }}%</b>
                                            </P>
                                        </li>
                                        <li>
                                            <p>
                                                {{ __('Discount') }}
                                            </p>
                                            <P>
                                                <b class="discount">{{ App\Models\Product::convertPrice(0) }}</b>
                                                <input type="hidden" id="d-val"
                                                    value="{{ App\Models\Product::convertPrice(0) }}">
                                            </P>
                                        </li>
                                    </ul>
                                    <div class="total-price">
                                        <p style="margin-bottom:0px;">
                                            {{ __('Total') }}
                                        </p>
                                        <p style="margin-bottom:0px;">
                                            <span
                                                class="main-total">{{ Session::has('cart') ? App\Models\Product::convertPrice($mainTotal) : '0.00' }}</span>
                                        </p>
                                    </div>
                                    <div class="total-price">
                                        <p>

                                        </p>
                                        <p>
                                            <span
                                                class="main-total2">{{ Session::has('cart') ? App\Models\Product::signFirstPrice($mainTotal) : '0.00' }}</span>
                                        </p>
                                    </div>
                                    @if ($gs->is_standard_checkout)
                                        <div class="cupon-box">
                                            @if ($gs->is_zip_validation)
                                                <h4 class="title">{{ __('Shipping Price Calculation') }}</h4>
                                                <p class="PAC-sheep border rounded p-1">
                                                    <small>
                                                        {{ __('Methods and prices displayed for your convenience.') }}
                                                    </small>
                                                </p>
                                                <form id="freight-form" class="coupon">
                                                    <input type="text" name="zip" id="shippingZip"
                                                        placeholder="{{ __('Postal Code') }}">
                                                    <button type="submit">{{ __('Calculate') }}</button>
                                                    <div class="shipping-area-class text-left mt-4" id="shipping-area"></div>
                                                </form>
                                            @endif
                                            @if ($gs->is_aex && config('features.aex_shipping'))
                                                <h4 class="title">
                                                    <small>{{ __('AEX Shipping') }}</small>
                                                </h4>
                                                <form id="freight-form-aex" class="coupon">
                                                    <select class="form-control" id="aex_destination" name="aex_destination">
                                                        <option value="">{{ __('Select City') }}</option>
                                                        @foreach ($aex_cities as $city)
                                                            <option value="{{ $city->codigo_ciudad }}">
                                                                {{ $city->denominacion }} -
                                                                {{ $city->departamento_denominacion }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="shipping-area-class text-left mt-4" id="shipping-area-aex">
                                                    </div>
                                                </form>
                                            @endif
                                            <div id="coupon-link" class="mt-3">
                                                {{ __('Have a promotion code?') }}
                                            </div>
                                            <form id="coupon-form" class="coupon">
                                                <input type="text" placeholder="{{ __('Coupon Code') }}" id="code"
                                                    required="" autocomplete="off">
                                                <input type="hidden" class="coupon-total" id="grandtotal"
                                                    value="{{ Session::has('cart') ? App\Models\Product::convertPrice($mainTotal) : '0.00' }}">
                                                <button type="submit">{{ __('Apply') }}</button>
                                            </form>
                                        </div>
                                    @endif

                                    @if ($gs->is_standard_checkout)
                                        <a href="{{ route('front.checkout') }}" class="order-btn">
                                            {{ __('Place Order') }}
                                        </a>
                                    @endif
                        @endif
                                @if ($gs->is_simplified_checkout && !empty($gs->simplified_checkout_number) && $admstore->show_product_prices)
                                    <a href="#" class="order-btn mt-2" data-toggle="modal"
                                        data-target="#simplified-checkout-modal">{{ __('Simplified Checkout') }}</a>
                                @else
                                    <div class="d-grid text-center justify-content-center order-box">
                                        <h5 class="pb-4" style="font-family:Arial, Helvetica, sans-serif">{{ __('Place Order') }}</h5>
                                        <a href="#" class="order-btn mt-2" data-toggle="modal"
                                        data-target="#simplified-checkout-modal">{{ __('Simplified Checkout') }}</a>
                                    </div>    
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- Cart Area End -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var pixel_name = "{{ session('pixel_name') }}";
            var pixel_category = "{{ session('pixel_category') }}";
            var pixel_id = "{{ session('pixel_id') }}";
            var pixel_type = "{{ session('pixel_type') }}";
            var pixel_price = "{{ session('pixel_price') }}";
            var pixel_currency = "{{ session('pixel_currency') }}";

            if (pixel_name || pixel_category || pixel_id || pixel_type || pixel_price || pixel_currency) {
                if (typeof fbq != 'undefined') {
                    fbq('track', 'AddToCart', {
                        content_name: pixel_name,
                        content_category: pixel_category,
                        content_ids: pixel_id,
                        content_type: pixel_type,
                        value: pixel_price,
                        currency: pixel_currency
                    });
                    if ("{{ session('is_customizable') }}" == true) {
                        fbq('track', 'CustomizeProduct');
                    }
                }
            }
        });

        var city_id, state_id, country_id, zip_code;

        function busca_cep(zip_code) {
            $.ajax({
                type: "GET",
                url: mainurl + "/checkout/cep",
                data: {
                    cep: zip_code
                },
                success: function(data) {
                    city_id = "";
                    state_id = "";
                    country_id = "";
                    if (data.city_id) {
                        city_id = data.city_id;
                        state_id = data.state_id;
                        country_id = data.country_id;
                        getShippings("ship");
                    }
                    if (data.error) {
                        var html = '<p class="PAC-sheep border rounded p-1"><small>' + data.error +
                            '</small></p>';
                        $('#shipping-area').append(html);
                        $('#preloader_cart').hide();
                        return;
                    }
                }
            });
        }

        function getShippings(bill_ship) {
            $.ajax({
                type: 'GET',
                url: mainurl + '/checkout/getShippingsOptions',
                data: {
                    city_id: city_id,
                    state_id: state_id,
                    country_id: country_id,
                    zip_code: zip_code
                },
                success: function(data) {
                    $('#shipping-area').html('');
                    $('#shipping-area').append(data.content);

                    // Calculate new prices when clicking the shipping methods
                    $('.shipping').on('click', function() {
                        calc_ship_pack();
                    });

                    $("input:radio[name=shipping]:not(:disabled):first").prop('checked', 'checked');
                },
                error: function(err) {
                    console.log(err);
                },
                complete: function() {
                    $('#preloader_cart').hide();
                }
            })
        }

        $('#freight-form').on('submit', function(e) {
            e.preventDefault();
            $('#preloader_cart').show();
            $('#shipping-area').html('');
            zip_code = $('#shippingZip').val();
            busca_cep(zip_code);
        });

        //#region aex
        function busca_aex(codigo_ciudad) {
            var html = '';
            $.ajax({
                type: "GET",
                url: mainurl + "/checkout/aex",
                data: {
                    codigo_ciudad: codigo_ciudad
                },
                success: function(data) {

                    if (data.error) {
                        html = '<p class="PAC-sheep border rounded p-1"><small>' + data.error + '</small></p>';
                    } else {
                        html = data;
                    }
                },
                error: function() {
                    html = '<p class="PAC-sheep border rounded p-1"><small>{{ __('Error') }}</small></p>';
                },
                complete: function() {
                    $('#shipping-area-aex').append(html);
                    $('#preloader_cart').hide();
                    // Calculate new prices when clicking the shipping methods
                    $('.shipping').on('click', function() {
                        calc_ship_pack();
                    });

                    $("input:radio[name=shipping]:not(:disabled):first").prop('checked', 'checked');
                }
            });
        }

        $('#aex_destination').on('change', function(e) {
            e.preventDefault();
            $('#preloader_cart').show();
            $('#shipping-area-aex').html('');
            codigo_ciudad = $('#aex_destination').val();
            busca_aex(codigo_ciudad);
        });
        //#endregion
    </script>
@endsection
