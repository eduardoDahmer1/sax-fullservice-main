@extends('front.themes.theme-15.checkout_layout')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/checkout/style.css') }}">


 <header class="bg-black text-center py-3 mb-5"><img src="https://i.ibb.co/1dFF5PK/logosax.png" alt=""></header>

@section('content')
    <input type="hidden" id="has_temporder" value="false">
    <!-- Breadcrumb Area Start -->
    <section class="checkout">
        <div class="container">
            <div class="row">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="step-icons d-flex col-10 col-md-8 align-items-center">
                            <div class="d-flex align-items-center"><i class="bi bi-bag-check-fill color-2"></i></div>
                            <div class="line"></div>
                            <div class="d-flex align-items-center"><i class="bi bi-person-fill"></i></div>
                            <div class="line"></div>
                            <div class="d-flex align-items-center"><i class="bi bi-truck"></i></div>
                            <div class="line"></div>
                            <div class="d-flex align-items-center"><i class="bi bi-credit-card"></i></div>
                        </div>

                        <div class="col-lg-8">
                            <div class="preloader" id="preloader_checkout" style="background: url({{ $gs->loaderUrl }}) no-repeat scroll center center; background-color: rgba(0,0,0,0.5); display: none">
                            </div>
                        
                            <form id="bancard-form" action="" method="POST" class="checkoutform d-flex justify-content-center replace-bank">
                                @include('includes.form-success')
                                @include('includes.form-error')
                                {{ csrf_field() }}

                                <div class="step col-12 row align-items-center justify-content-center mt-4">
                                    <div class="d-flex align-items-center bg-top my-4 py-2">
                                        <h6 class="col-8 text-uppercase">{{ __('Product') }}</h6>
                                        <h6 class="col-2 d-lg-block d-none text-uppercase">{{ __('Amount') }}</h6>
                                        <h6 class="col-2 d-lg-block d-none text-uppercase">{{ __('Price') }}</h6>
                                    </div> @foreach ($products as $product) 
                                    <div class="d-flex flex-wrap align-items-center p-0 pb-5 border-bottom-f1 mb-4">
                                        <div class="col-lg-8 prod-img px-0">
                                            <img src="{{ filter_var($product['item']['photo'], FILTER_VALIDATE_URL) ? $product['item']['photo'] : asset('storage/images/products/' . $product['item']['photo']) }}"
                                                alt="">
                                            <div class="pl-sm-4 pl-1">
                                                <h5 class="fw-normal fs-16">{{ $product['item']->name }}</h5>
                                                <p class="color-1">{{ __('Product code') }}: {{ $product['item']->sku }}</p>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex align-items-center bg-top my-4 py-2 d-lg-none d-block">
                                            <h6 class="col-6 text-uppercase">{{ __('Amount') }}</h6>
                                            <h6 class="col-6 text-uppercase text-left">{{ __('Price') }}</h6>
                                        </div>
                                        <p class="col-lg-2 col-6 m-lg-0 mt-3">{{ $product['qty'] }}</p>
                                        <div class="col-lg-2 prices col-6">
                                            <h5 class="mb-0 fw-semibold">{{ App\Models\Product::convertPrice($product['item']['price']) }}
                                            </h5>
                                            <span>{{ App\Models\Product::convertPriceDolar($product['item']['price']) }}</span>
                                        </div>
                                        </div> @endforeach <div class="bg-top py-5 d-flex flex-wrap justify-content-between mt-3">
                                            <div class="prices d-flex justify-content-between px-2 col-12 col-md-7">
                                                <p class="color-1 m-0">{{ __('Total') }} ({{$totalQty}} {{ __('items') }}):</p>
                                                <div class="px-lg-5">
                                                    <h5 class="mb-0 fw-semibold">{{ App\Models\Product::convertPrice($totalPrice) }}</h5>
                                                    <span class="color-1 m-0">{{ App\Models\Product::convertPriceDolar($totalPrice)
                                                        }}</span>
                                                </div>
                                            </div>
                                            <button class="px-5 btn-continue col-md-4 col-lg-3 col-12 mt-4 mt-md-0">{{ __('Continue')}}</button>
                                        </div>
                                    </div>
                                    <div class="step col-sm-12 row align-items-center justify-content-center mt-4">
                                        <div class="d-flex align-items-center p-0 pb-3 border-bottom-f1">
                                            <h5 class="fw-semibold">{{ __('Personal data') }}</h5>
                                        </div>
                                        <div class="bg-top py-5 row justify-content-center mt-5 personal-data">
                                            <div class="col-md-6 mb-3">
                                                <p class="m-0 color-1 fw-semibold px-1">{{ __('Full Name') }} *</p>
                                                <input id="billName" name="name" class="col-12 mx-1 required-input" type="text"
                                                required
                                                    value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->name : old('names') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p class="m-0 color-1 fw-semibold px-1">{{ __('Document') }} *</p>
                                                <input id="billCpf" name="document" class="col-12 mx-1 required-input" type="text"
                                                    pattern="[0-9]+"
                                                    value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->document : old('customer_documents') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p class="m-0 color-1 fw-semibold px-1">{{ __('Email') }} *</p>
                                                <input id="billEmail" name="email" class="col-12 mx-1 required-input" type="text"
                                                    value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->email : old('email') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p class="m-0 color-1 fw-semibold px-1">{{ __('Phone Number') }} *</p>
                                                <input id="billPhone" name="phone" class="col-12 mx-1 required-input" type="text"
                                                    value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->phone : old('phone') }}">
                                            </div>
                                            <div class="col-12 text-end mt-4 d-md-block d-none">
                                                <button class="btn-back">{{ __('To go back') }}</button>
                                                <button class="px-5 btn-continue" id="step-2-continue">{{ __('Continue')}}</button>
                                            </div>
                                            <div class="col-12 text-center mt-4 d-md-none d-flex btns flex-wrap">
                                                <button class="btn-back">{{ __('To go back') }}</button>
                                                <button class="px-5 btn-continue" id="step-2-continue">{{ __('Continue')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="step col-sm-12 row align-items-center justify-content-center mt-4">
                                        <div class="d-flex align-items-center p-0 pb-3 border-bottom-f1">
                                            <h5 class="fw-semibold">{{ __('Shipping method') }}</h5>
                                        </div>
                                        <div class="bg-top mt-5 py-4">
                                            <!-- retirar no meu endereço -->
                                            <div class="border-bottom-f1 pb-4 d-flex justify-content-between"> @if(Auth::guard('web')->check()
                                                && Auth::guard('web')->user()->address != '') <div>
                                                    <input id="myaddress" name="shipping" value="1" type="radio" @checked(isset(Auth::guard('web')->user()->address))>
                                                    <label for="myaddress">{{ __('Receive at my address') }}</label>
                                                    <p style="font-size: 14px;" class="mb-0 color-1 px-3">
                                                        {{Auth::guard('web')->user()->address ?? ''}}
                                                    </p>
                                                    
                                                    <input type="hidden" name="nc_address" id="nc_address" value="{{Auth::guard('web')->user()->address ?? ''}}"  style="font-size: 14px;" class="mb-0 color-1 px-3" readonly>
                                                </div>
                                                <h6 class="px-2 color-3">U$10.00</h6> @endif
                                            </div>
                                            <!-- adicionar endereço -->
                                            <div class="border-bottom-f1 py-4 d-flex flex-wrap justify-content-between">
                                                <div>
                                                    <input id="newaddress" name="shipping" value="2" type="radio" @checked(!isset(Auth::guard('web')->user()->address))>
                                                    <label for="newaddress">{{ __('Add new address') }}</label>
                                                </div>
                                                <h6 class="px-2 color-3">U$10.00</h6>
                                                <div @class(['d-none' => isset(Auth::guard('web')->user()->address), 'col-12', 'mt-3', 'new-address'])>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <p style="font-size: 14px;" class="m-0 color-1 fw-semibold px-1">{{ __('State') }}
                                                            </p>
                                                            <select class="form-control js-state" name="shipping_state" data-type="shipping"
                                                                id="shippingState"> </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <p style="font-size: 14px;" class="m-0 color-1 fw-semibold px-1">{{ __('City')
                                                                }}</p>
                                                            <select class="form-control js-city" name="shipping_city" data-type="shipping"
                                                                id="shippingCity" readonly> </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <p style="font-size: 14px;" class="m-0 color-1 fw-semibold px-1">{{ __('Address') }}
                                                            </p>
                                                            <input id="address" name="address" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="py-4 d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <input id="withdrawal" name="shipping" value="3" type="radio">
                                                        <label for="withdrawal">{{ __('Pick up in') }} SAX</label>
                                                    </div>
                                                    <select class="select-local d-none mx-2" name="pickup_location" id="local">
                                                        @foreach ($allPickups as $pickup)
                                                            <option value="{{ $pickup->location }}|{{ $pickup->id }}">
                                                                {{ $pickup->location }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <span style="font-size: 14px;">FREE</span>
                                            </div>
                                            <div class="col-12">
                                                <iframe style="height: 300px;" class="w-100 CDE-MAP d-none"
                                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14403.483045695173!2d-54.625295595894784!3d-25.509356390177906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94f69aaaec5ef03d%3A0xff12a8b090a63ebd!2sSAX%20Department%20Store!5e0!3m2!1sen!2sbr!4v1701116211878!5m2!1sen!2sbr"
                                                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                <iframe style="height: 300px;" class="w-100 ASUNCION-MAP d-none"
                                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3607.579420944204!2d-57.56840971875741!3d-25.284729820038848!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x945da8a8f48ce025%3A0x2715791645730d75!2sSAX%20Department%20Store-Asunci%C3%B3n!5e0!3m2!1sen!2sbr!4v1701116467727!5m2!1sen!2sbr"
                                                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                                            </div>
                                            <div class="col-12 text-end mt-4 d-md-block d-none">
                                                <button class="btn-back">{{ __('To go back') }}</button>
                                                <button class="px-5 btn-continue">{{ __('Continue')}}</button>
                                            </div>
                                            <div class="col-12 text-center mt-4 d-md-none d-flex pb-4 btns flex-wrap">
                                                <button class="btn-back">{{ __('To go back') }}</button>
                                                <button class="px-5 btn-continue">{{ __('Continue')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="step col-sm-12 row justify-content-between mt-4">
                                        <div class="d-flex align-items-center bg-top my-4 py-2 justify-content-between">
                                            <h6 class="col-8 text-uppercase">{{ __('Method') }}</h6>
                                            <h6 class="col-3 d-lg-block d-none text-uppercase">{{ __('Total') }}</h6>
                                        </div>
                                        <div class="pay-method d-flex gap-2 col-xl-7 p-0 mb-4 justify-content-left-center">
                                            <div class="d-flex align-items-center justify-content-center color-2">
                                                <input id="transfer" type="radio" name="pay-method" value="2" data-form="{{ route('bancard.submit') }}" checked>
                                                <label for="transfer">
                                                    <i class="bi bi-credit-card color-2"></i>
                                                    <p>Bancard</p>
                                                </label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <input id="credit" type="radio" name="pay-method" value="1" data-form="{{ route('bank.submit') }}">
                                                <label for="credit">
                                                    <i class="bi bi-bank"></i>
                                                    <p>Depósito bancario</p>
                                                </label>
                                            </div>
                                            <!-- <div>
                                                    <input id="now" type="radio" name="pay-method" value="3">
                                                    <label for="now">
                                                        <i class="bi bi-cash-coin"></i>
                                                        <p>Pargar na Entrega</p>
                                                    </label>
                                                </div> -->
                                        </div>
                                        @foreach ($bank_accounts as $bank_account)
                                            <ul class="list-group position-absolute data-deposit d-none" style="margin-top: 10px;">
                                                <li class="list-group-item border-0 px-3"
                                                    style="padding: 5px;">
                                                    {{ strtoupper($bank_account->name) }}</li>
                                                <li class="list-group-item border-0">
                                                    {!! nl2br(str_replace('', '&nbsp;', $bank_account->info)) !!}</li>
                                            </ul>
                                        @endforeach
                                        @foreach ($bank_accounts as $bank_account)
                                            <ul class="list-group data-deposit2 order-box-2 mb-2 d-none d-xl-none" style="margin-top: 10px;">
                                                <li class="list-group-item border-0 px-3"
                                                    style="padding: 5px;">
                                                    {{ strtoupper($bank_account->name) }}</li>
                                                <li class="list-group-item border-0">
                                                    {!! nl2br(str_replace('', '&nbsp;', $bank_account->info)) !!}</li>
                                            </ul>
                                        @endforeach
                                        <div class="col-xl-5 px-0">
                                            <div class="right-area mt-0">
                                                <div class="order-box order-box-2">
                                                    <h4 class="title text-black">{{ __('PRICE DETAILS') }}</h4>
                                                    <div class="border-bottom-f1">
                                                        <div class="d-flex justify-content-between">
                                                            <p style="font-size: 14px;" class="fw-semibold m-0">{{ __('Total MRP') }}</p>
                                                            <p style="font-size: 14px;" class="m-0"><b class="cart-total">{{
                                                                    Session::has('cart') ?
                                                                    App\Models\Product::convertPrice(Session::get('cart')->totalPrice) :
                                                                    '0.00'
                                                                    }}</b></p>
                                                        </div>
                                                        <p style="font-size: 14px;" class="m-0 text-end pb-3 mb-3"><b class="cart-total">{{
                                                                Session::has('cart') ?
                                                                App\Models\Product::convertPriceDolar(Session::get('cart')->totalPrice) :
                                                                '0.00'
                                                                }}</b></p>
                                                    </div>
                                                    <h4 class="title text-black mt-3">{{ __('Shipping method') }}</h4>
                                                    <div class="d-flex flex-wrap">
                                                        <p id="freteText" style="font-size: 14px;" class="fw-semibold colo-1 pr-1 d-none">
                                                            {{__('Pick up in')}}
                                                        </p>
                                                        <p style="font-size: 14px;" class="fw-semibold colo-1 m-0 d-none">CDE</p>
                                                    </div>
                                                    <p style="font-size: 14px;" class="primeSax fw-semibold colo-1 m-0 d-block">{{ __('Prime SAX Shipping') }}</p>
                                                    <p id="freteGratis" class="fw-bold color-4 border-bottom-f1 pb-3 mb-3 d-none text-end"></p>
                                                    <p id="frete10" class="fw-bold border-bottom-f1 pb-3 mb-3 text-end"><b style="font-size: 14px;" class="cart-total fw-bold">+ {{App\Models\Product::convertPrice(10)}}</b><br>
                                                        <b class="cart-total fw-bold"> U$10</b>
                                                    </p>
                                                    <div class="total-price d-flex justify-content-between">
                                                        <p style="margin-bottom:0px;">{{ __('Total') }}</p>
                                                        <p><span class="add10">{{ App\Models\Product::signFirstPrice($totalPrice + 10) }}</span></p>
                                                        <p class="d-none price-10"><span id="total-cost2">{{ App\Models\Product::signFirstPrice($totalPrice) }}</span></p>
                                                    </div>
                                                    <div class="d-flex btns2 flex-wrap">
                                                        <button class="btn-back d-xl-none d-block">{{ __('To go back') }}</button>
                                                        <button style="z-index: 9;" type="submit" id="final-btn" class="btn-back px-5 w-100" form="myform">{{ __('Continue') }}</button>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="position-absolute btn-deposit" style="bottom: 30px;left: 0;">
                                            <button class="btn-back px-5 mt-5 d-xl-block d-none">{{ __('To go back') }}</button>
                                        </div>
                                    </div>
                                    <div class="checkout-area d-none">
                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="pills-step1" role="tabpanel"
                                                aria-labelledby="pills-step1-tab">
                                                <div class="content-box">
                                                    <div class="content">
                                                        <div class="personal-info">
                                                            <div class="row">
                                                                <div class="col-lg-12  mt-3">
                                                                    <p><small>* {{ __('indicates a required field') }}</small></p>
                                                                </div>
                                                            </div>
                                                            <h5 class="title">
                                                                {{ __('Personal Information') }} :
                                                            </h5>
                                                            @if (Session::has('session_order'))
                                                                <div class="billing-address">
                                                                    <!-- CLASSE INSERIDA APENAS PARA FORMATAÇÃO DOS CAMPOS NO FORM -->
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <input type="text" pattern="^(\S*)\s+(.*)$"
                                                                                id="personal-name" class="form-control"
                                                                                name="personal_name"
                                                                                placeholder="{{ __('Full Name') }}"
                                                                                title="{{ __('Input first name and last name') }}"
                                                                                value="{{ session()->get('session_order')['customer_name'] }}">
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <input type="email" id="personal-email"
                                                                                class="form-control" name="personal_email"
                                                                                placeholder="{{ __('Enter Your Email') }}"
                                                                                value="{{ session()->get('session_order')['customer_email'] }}">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                @if (!Auth::check())
                                                                    <div class="row">
                                                                        <div class="col-lg-12 mt-3">
                                                                            <input class="styled-checkbox" id="open-pass"
                                                                                type="checkbox" value="1" name="pass_check">
                                                                            <label
                                                                                for="open-pass">{{ __('Create an account ?') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row set-account-pass d-none">
                                                                        <div class="col-lg-6">
                                                                            <input type="password" name="personal_pass"
                                                                                id="personal-pass" class="form-control"
                                                                                placeholder="{{ __('Enter Your Password') }}">
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <input type="password" name="personal_confirm"
                                                                                id="personal-pass-confirm" class="form-control"
                                                                                placeholder="{{ __('Confirm Your Password') }}">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                        </div>

                                                        <div class="billing-address">
                                                            <h5 class="title">
                                                                {{ __('Billing Details') }}
                                                            </h5>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <!-- Full name -->
                                                                <div>
                                                                    <input class="form-control" type="text" name="name"
                                                                        id="billName" placeholder="{{ __('Full Name') }} *"
                                                                        required="" pattern="^(\S*)\s+(.*)$"
                                                                        title="{{ __('Input first name and last name') }}"
                                                                        value="{{ session()->get('session_order')['customer_name'] }}">
                                                                </div>
            
                                                                <!-- Document -->
                                                                <div>
                                                                    <input class="form-control" type="text"
                                                                        name="customer_document" id="billCpf"
                                                                        placeholder="{{ __('Document') }} *" required=""
                                                                        pattern="[0-9]+"
                                                                        title="{{ __('Field only accepts numbers') }}"
                                                                        value="{{ session()->get('session_order')['customer_document'] }}">

                                                                </div>
            
                                                                <!-- Date -->
                                                                <div class="col-lg-6">
                                                                        <input placeholder="{{ __('Date of Birth') }}" class="form-control" value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->birth_date : old('birthday') }}" type="text" id="birthday" name="birthday" required/>
                                                                    </div>
                                                            
                                                                <!-- Gender -->
                                                                <div>
                                                                    <select class="form-control" name="customer_gender" id="customer_gender">
                                                                        <option value="">{{ __("Gender") }}</option>
                                                                        <option value="M" {{ old('customer_gender') == 'M' ? 'selected' : '' }}> {{ __("Male") }}</option>
                                                                        <option value="F" {{ old('customer_gender') == 'F' ? 'selected' : '' }}>{{ __("Female") }}</option>
                                                                        <option value="O" {{ old('customer_gender') == 'O' ? 'selected' : '' }}>{{ __("Other") }}</option>
                                                                        <option value="N" {{ old('customer_gender') == 'N' ? 'selected' : '' }}>{{ __("Not Declared") }}</option>
                                                                    </select>
                                                                </div>
            
                                                                <!-- Phone -->
                                                                <div>
                                                                    <input class="form-control" type="text" name="phone-old"
                                                                        id="billPhone" placeholder="{{ __('Phone Number') }} *"
                                                                        required=""
                                                                        value="{{ session()->get('session_order')['customer_phone'] }}">
                                                                </div>
            
                                                                <!-- E-mail -->
                                                                <div>
                                                                    <input class="form-control" type="text" name="email"
                                                                        id="billEmail" placeholder="{{ __('Email') }} *"
                                                                        required=""
                                                                        value="{{ session()->get('session_order')['customer_email'] }}">
                                                                </div>
                                                                </div>
                                                                
                                                                <div class="col-lg-6">
                                                                    <div class=" {{ $digital == 1 ? 'd-none' : '' }}">
                                                                        <select class="form-control" id="shipop" name="shipping_old"
                                                                            required="" style="margin-bottom: 10px;">
                                                                            <option value="shipto">{{ __('Ship To Address') }}</option>
                                                                            @if($allPickups->count() > 0)
                                                                                <option value="pickup">{{ __('Pick Up') }}</option>
                                                                            @endif
                                                                        </select>
                                                                    </div>
                
                                                                    <div class=" d-none" id="shipshow">
                                                                        <select class="form-control"
                                                                            style="margin-bottom: 10px;">
                                                                            @foreach ($allPickups as $pickup)
                                                                                <option value="{{ $pickup->location }}|{{ $pickup->id }}">
                                                                                    {{ $pickup->location }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                
                                                                    @if ($gs->is_zip_validation)
                                                                        <div>
                                                                            <input class="form-control js-zipcode" type="text"
                                                                                name="zip" data-type="bill" id="billZip"
                                                                                placeholder="{{ __('Postal Code') }}" required=""
                                                                                value="{{ session()->get('session_order')['customer_zip'] }}">
                                                                        </div>
                                                                    @else
                                                                        <div>
                                                                            <input class="form-control" type="text" name="zip"
                                                                                data-type="bill" id="zip"
                                                                                placeholder="{{ __('Postal Code') }}" required=""
                                                                                value="{{ session()->get('session_order')['customer_zip'] }}">
                                                                        </div>
                                                                    @endif
                
                                                                    <div>
                                                                        <input class="form-control" type="text" name="address"
                                                                            id="billAddress" placeholder="{{ __('Address') }} *"
                                                                            required=""
                                                                            value="{{ session()->get('session_order')['customer_address'] }}">
                                                                    </div>
                
                                                                    <div>
                                                                        <input class="form-control" type="text" name="address_number"
                                                                            id="billAdressNumber" placeholder="{{ __('Number') }} *"
                                                                            required=""
                                                                            value="{{ session()->get('session_order')['customer_address_number'] }}">
                                                                    </div>
                
                                                                    <div>
                                                                        <input class="form-control" type="text" name="complement"
                                                                            id="billComplement" placeholder="{{ __('Complement') }} *"
                                                                            value="{{ session()->get('session_order')['customer_complement'] }}">
                                                                    </div>
                
                                                                    <div>
                                                                        <input class="form-control" type="text" name="district"
                                                                            id="billDistrict" placeholder="{{ __('District') }} *"
                                                                            value="{{ session()->get('session_order')['customer_district'] }}">
                                                                    </div>
                
                                                                    <div>
                                                                        <select class="form-control js-country" name="country"
                                                                            data-type="bill" id="billCountry" required="">
                                                                            <option value="" data-code="">
                                                                                {{ __('Select Country') }} *
                                                                            </option>
                                                                            @foreach ($countries as $country)
                                                                                <option value="{{ $country->id }}"
                                                                                    {{ session()->get('session_order')['customer_country_id'] == $country->id ? 'selected' : '' }}
                                                                                    data-code="{{ $country->country_code }}">
                                                                                    {{ $country->country_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                
                                                                    <div>
                                                                        <select class="form-control js-state" name="state"
                                                                            data-type="bill" id="billState" required="">
                                                                            <option
                                                                                value="{{ session()->get('session_order')['customer_state_id'] }}">
                                                                                {{ session()->get('session_order')['customer_state'] }}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                
                                                                    <div>
                                                                        <select class="form-control js-city" name="city"
                                                                            data-type="bill" id="billCity" required="">
                                                                            <option
                                                                                value="{{ session()->get('session_order')['customer_city_id'] }}">
                                                                                {{ session()->get('session_order')['customer_city'] }}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="alert alert-warning" id="checkoutZipError"
                                                            style="display:none; background-color: #FFBF39; color: #fff;">
                                                            {{ __('Invalid Zip Code. Please fill the fields manually!') }}
                                                        </div>
                                                        <div class="row {{ $digital == 1 ? 'd-none' : '' }}">
                                                            <div class="col-lg-12 mt-3">
                                                                <input class="styled-checkbox" id="ship-diff-address"
                                                                    name="diff_address" type="checkbox" value="value1">
                                                                <label
                                                                    for="ship-diff-address">{{ __('Ship to a Different Address?') }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="ship-diff-addres-area d-none">
                                                            <h5 class="title">
                                                                {{ __('Shipping Details') }}
                                                            </h5>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <input class="form-control ship_input" pattern="^(\S*)\s+(.*)$"
                                                                        type="text" name="shipping_name" id="shippingName"
                                                                        title={{ __('Input first name and last name') }}
                                                                        placeholder="{{ __('Full Name') }} *"
                                                                        value="{{ session()->get('session_order')['shipping_name'] }}">
                                                                </div>
                                                                @if ($gs->is_zip_validation)
                                                                    <div class="col-lg-6">
                                                                        <input class="form-control js-zipcode" type="text"
                                                                            name="shipping_zip" data-type="shipping" id="shippingZip"
                                                                            placeholder="{{ __('Postal Code') }}">
                                                                    </div>
                                                                @else
                                                                    <div class="col-lg-6">
                                                                        <input class="form-control" type="text"
                                                                            name="shipping_zip" data-type="shipping" id="zip"
                                                                            placeholder="{{ __('Postal Code') }}">
                                                                    </div>
                                                                @endif
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text" name="shipping_phone"
                                                                        id="shippingPhone" placeholder="{{ __('Phone Number') }}"
                                                                        value="{{ session()->get('session_order')['shipping_phone'] }}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text"
                                                                        name="shipping_address" id="shippingAddress"
                                                                        placeholder="{{ __('Address') }}"
                                                                        value="{{ session()->get('session_order')['shipping_address'] }}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text"
                                                                        name="shipping_address_number" id="shippingAddressNumber"
                                                                        placeholder="{{ __('Number') }}"
                                                                        value="{{ session()->get('session_order')['shipping_address_number'] }}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text"
                                                                        name="shipping_complement" id="shippingComplement"
                                                                        placeholder="{{ __('Complement') }}"
                                                                        value="{{ session()->get('session_order')['shipping_complement'] }}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text"
                                                                        name="shipping_district" id="shippingDistrict"
                                                                        placeholder="{{ __('District') }}"
                                                                        value="{{ session()->get('session_order')['shipping_district'] }}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <select class="form-control js-country" name="shipping_country"
                                                                        data-type="shipping" id="shippingCountry">
                                                                        <option value="" data-code="">
                                                                            {{ __('Select Country') }}</option>
                                                                        @foreach ($countries as $country)
                                                                            <option value="{{ $country->id }}"
                                                                                data-code="{{ $country->country_code }}">
                                                                                {{ $country->country_name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <select class="form-control js-state" name="shipping_state"
                                                                        data-type="shipping" id="shippingState" readonly>
                                                                        <option value="">{{ __('Select country first') }}
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <select class="form-control js-city" name="shipping_city"
                                                                        data-type="shipping" id="shippingCity" readonly>
                                                                        <option value="">{{ __('Select state first') }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="alert alert-warning" id="shippingZipError"
                                                                style="display:none; background-color: #FFBF39; color: #fff;">
                                                                {{ __('Invalid Zip Code, fill the fields manually!') }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <!-- SE NÃO TEM DADOS DA SESSÃO -->
                                                        <!-- SE O CADASTRO ESTIVER INCOMPLETO (CIDADE ESTADO E PAÍS PREENCHE COM BASE NO CEP) HABILITA UM BOTÃO QUE REDIRECIONA PARA A RODA EDIT USER -->
                                                        @if (Auth::check())
                                                            @if (Auth::user()->zip == null ||
                                                                Auth::user()->document == null ||
                                                                Auth::user()->address == null ||
                                                                Auth::user()->address_number == null ||
                                                                Auth::user()->phone == null)
                                                                <a href="{{ route('user-profile') }}"><span
                                                                        class="badge badge-primary-checkout">{{ __('Complete your profile information') }}</span></a>
                                                            @endif
                                                        @endif
                                                        <div class="billing-address">
                                                            <!-- CLASSE INSERIDA APENAS PARA FORMATAÇÃO DOS CAMPOS NO FORM -->
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <input type="text" pattern="^(\S*)\s+(.*)$" id="personal-name"
                                                                        class="form-control" name="personal_name"
                                                                        title="{{ __('Input first name and last name') }}"
                                                                        placeholder="{{ __('Full Name') }}"
                                                                        value="{{ Auth::check() ? Auth::user()->name : old('personal_name') }}"
                                                                        {!! Auth::check() ? 'readonly' : '' !!}>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input type="email" id="personal-email" class="form-control"
                                                                        name="personal_email"
                                                                        placeholder="{{ __('Enter Your Email') }}"
                                                                        value="{{ Auth::check() ? Auth::user()->email : old('personal_email') }}"
                                                                        {!! Auth::check() ? 'readonly' : '' !!}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if (!Auth::check())
                                                            <div class="row">
                                                                <div class="col-lg-12 mt-3">
                                                                    <input class="styled-checkbox" id="open-pass" type="checkbox"
                                                                        value="1" name="pass_check">
                                                                    <label for="open-pass">{{ __('Create an account ?') }}</label>
                                                                </div>
                                                            </div>
                                                            <div class="row set-account-pass d-none">
                                                                <div class="col-lg-6">
                                                                    <input type="password" name="personal_pass" id="personal-pass"
                                                                        class="form-control"
                                                                        placeholder="{{ __('Enter Your Password') }}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input type="password" name="personal_confirm"
                                                                        id="personal-pass-confirm" class="form-control"
                                                                        placeholder="{{ __('Confirm Your Password') }}">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="billing-address">
                                                        <h5 class="title">{{ __('Personal Data') }}</h5>
                                                        <div class="row justify-content-center">
                                                            <div class="col-12 p-0 row">
                                                                <!-- Fullname -->
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text" name="name"
                                                                        id="billName" placeholder="{{ __('Full Name') }} *"
                                                                        required="" pattern="^(\S*)\s+(.*)$"
                                                                        title="{{ __('Input first name and last name') }}"
                                                                        value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->name : old('name') }}">
                                                                </div>
                
                                                                <!-- Document -->
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text" name="customer_document"
                                                                        id="billCpf" placeholder="{{ __('Document') }}*"
                                                                        required="" pattern="[0-9]+"
                                                                        title="{{ __('Field only accepts numbers') }}"
                                                                        value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->document : old('customer_document') }}">
                                                                </div>

                                                                <!-- CPF BRASILEIRO -->
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text" name="cpf_brasileiro"
                                                                        id="cpf_brasileiro" placeholder="{{ __('RUC') }}"pattern="[0-9]+"
                                                                        title="{{ __('Field only accepts numbers') }}">
                                                                </div>
                                                                
                                                                
                                                                <!-- Data -->
                                                                <div class="col-lg-6">
                                                                        <input placeholder="{{ __('Date of Birth') }}" class="form-control" value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->birth_date : old('birthday') }}" type="text" id="birthday" name="birthday" required/>
                                                                    </div>
                                                            
                                                                <!-- Gender -->
                                                                <div class="col-lg-6">
                                                                    <select class="form-control" name="gender" id="gender">
                                                                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}> {{ __("Male") }}</option>
                                                                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>{{ __("Female") }}</option>
                                                                        <option value="O" {{ old('gender') == 'O' ? 'selected' : '' }}>{{ __("Other") }}</option>
                                                                        <option value="N" {{ old('gender') == 'N' ? 'selected' : '' }}>{{ __("Not Declared") }}</option>
                                                                    </select>
                                                                </div>
                
                                                                <!-- Phone -->
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text" name="phone-old"
                                                                        id="billPhone" placeholder="{{ __('Phone Number') }} *"
                                                                        required=""
                                                                        value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->phone : old('phone') }}">
                                                                </div>
                
                                                                <!-- Email -->
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text" name="email"
                                                                        id="billEmail" placeholder="{{ __('Email') }} *"
                                                                        required=""
                                                                        value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->email : old('email') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <h5 class="title mt-3">{{ __('Shipping details') }}</h5>
                                                        <div class="row justify-content-center">
                                                            <div class="col-12 p-0 row">
                                                                <div class="col-lg-6 {{ $digital == 1 ? 'd-none' : '' }}">
                                                                    <select class="form-control" id="shipop" name="shipping_old"
                                                                        required="" style="margin-bottom: 10px;">
                                                                        <option value="shipto">{{ __('Ship To Address') }}</option>
                                                                        <option value="pickup">{{ __('Pick Up') }}</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6 d-none" id="shipshow">
                                                                    <select class="form-control"
                                                                        style="margin-bottom: 10px;">
                                                                        @foreach ($allPickups as $pickup)
                                                                            <option value="{{ $pickup->location }}|{{ $pickup->id }}">
                                                                                {{ $pickup->location }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                
                                                                @if ($gs->is_zip_validation)
                                                                    @if (empty($state_id && $city_id && $country_id))
                                                                        <div class="col-lg-6">
                                                                            <input class="form-control js-zipcode" type="text"
                                                                                name="zip" data-type="bill" id="billZip"
                                                                                placeholder="{{ __('Postal Code') }}
                                                                    *"
                                                                                required="" value="{{ old('zip') }}">
                                                                        </div>
                                                                    @else
                                                                        <div class="col-lg-6">
                                                                            <input class="form-control js-zipcode" type="text"
                                                                                name="zip" data-type="bill" id="billZip"
                                                                                placeholder="{{ __('Postal Code') }}
                                                                    *"
                                                                                required=""
                                                                                value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->zip : old('zip') }}">
                                                                        </div>
                                                                    @endif
                                                                @else
                                                                    <div class="col-lg-6">
                                                                        <input class="form-control" type="text" name="zip"
                                                                            data-type="bill" id="zip"
                                                                            placeholder="{{ __('Postal Code') }} *" required=""
                                                                            value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->zip : old('zip') }}">
                                                                    </div>
                                                                @endif
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text" name="address"
                                                                        id="billAddress" placeholder="{{ __('Address') }} *"
                                                                        required=""
                                                                        value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->address : old('address') }}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text" name="address_number"
                                                                        id="billAdressNumber" placeholder="{{ __('Number') }} *"
                                                                        required=""
                                                                        value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->address_number : old('address_number') }}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text" name="complement"
                                                                        id="billComplement" placeholder="{{ __('Complement') }}"
                                                                        value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->complement : old('complement') }}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text" name="district"
                                                                        id="billDistrict" placeholder="{{ __('District') }}"
                                                                        value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->district : old('district') }}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <select class="form-control js-country" name="country"
                                                                        id="billCountry" data-type="bill" required="">
                                                                        <option value="" data-code="">{{ __('Select Country') }}
                                                                            *</option>
                                                                        @foreach ($countries as $country)
                                                                            <option value="{{ $country->id }}"
                                                                                {{ Auth::guard('web')->check() && Auth::guard('web')->user()->country_id == $country->id ? 'selected' : '' }}
                                                                                data-code="{{ $country->country_code }}">
                                                                                {{ $country->country_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @if (Auth::guard('web')->check())
                                                                    <div class="col-lg-6">
                                                                        <select class="form-control js-state" name="state"
                                                                            id="billState" data-type="bill" required="" readonly>
                                                                            <option value="{{ $state_id ?? '' }}"> {{ $state_name }}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <select class="form-control js-city" name="city"
                                                                            id="billCity" data-type="bill" required="" readonly>
                                                                            <option value="{{ $city_id }}"> {{ $city_name }}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                @else
                                                                    <div class="col-lg-6">
                                                                        <select class="form-control js-state" name="state"
                                                                            id="billState" required readonly>
                                                                            <option value="">{{ __('Select country first') }}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <select class="form-control js-city" name="city"
                                                                            id="billCity" required readonly>
                                                                            <option value="">{{ __('Select state first') }}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="alert alert-warning" id="checkoutZipError"
                                                        style="display:none; background-color: #FFBF39; color: #fff;">
                                                        {{ __('Invalid Zip Code. Please fill the fields manually!') }}
                                                    </div>
                                                    <div class="row {{ $digital == 1 ? 'd-none' : '' }}">
                                                        <div class="col-lg-12 mt-3">
                                                            <input class="styled-checkbox" id="ship-diff-address" name="diff_address"
                                                                type="checkbox" value="value1">
                                                            <label
                                                                for="ship-diff-address">{{ __('Ship to a Different Address?') }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="ship-diff-addres-area d-none">
                                                        <h5 class="title">
                                                            {{ __('Shipping Details') }}
                                                        </h5>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input class="form-control ship_input" pattern="^(\S*)\s+(.*)$"
                                                                    type="text" name="shipping_name" id="shippingName"
                                                                    title="{{ __('Input first name and last name') }}"
                                                                    placeholder="{{ __('Full Name') }} *">
                                                                <input type="hidden" name="shipping_email"
                                                                    value="{{ old('shipping_name') }}">
                                                            </div>
                                                            @if ($gs->is_zip_validation)
                                                                <div class="col-lg-6">
                                                                    <input class="form-control js-zipcode" type="text"
                                                                        name="shipping_zip" data-type="shipping" id="shippingZip"
                                                                        placeholder="{{ __('Postal Code') }} *"
                                                                        value="{{ old('shipping_zip') }}">
                                                                </div>
                                                            @else
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="text" name="shipping_zip"
                                                                        data-type="shipping" id="shippingZip"
                                                                        placeholder="{{ __('Postal Code') }}"
                                                                        value="{{ old('shipping_zip') }}">
                                                                </div>
                                                            @endif
                                                            <div class="col-lg-6">
                                                                <input class="form-control" type="text" name="shipping_phone"
                                                                    id="shippingPhone" placeholder="{{ __('Phone Number') }} *"
                                                                    value="{{ old('shipping_phone') }}">
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input class="form-control" type="text" name="shipping_address"
                                                                    id="shippingAddress" placeholder="{{ __('Address') }} *"            
                                                                    value="{{ old('shipping_address') }}">
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input class="form-control" type="text"
                                                                    name="shipping_address_number" id="shippingAddressNumber"
                                                                    placeholder="{{ __('Number') }} *"
                                                                    value="{{ old('shipping_address_number') }}">
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input class="form-control" type="text" name="shipping_complement"
                                                                    id="shippingComplement" placeholder="{{ __('Complement') }} *"
                                                                    value="{{ old('shipping_complement') }}">
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <input class="form-control" type="text" name="shipping_district"
                                                                    id="shippingDistrict" placeholder="{{ __('District') }}"
                                                                    value="{{ old('shipping_complement') }}">
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <select class="form-control js-country" name="shipping_country"
                                                                    data-type="shipping" id="shippingCountry">
                                                                    <option value="" data-code="">{{ __('Select Country') }}
                                                                    </option>
                                                                    @foreach ($countries as $country)
                                                                        <option value="{{ $country->id }}"
                                                                            data-code="{{ $country->country_code }}">
                                                                            {{ $country->country_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <select class="form-control js-state" name="shipping_state"
                                                                    data-type="shipping" id="shippingState" readonly>
                                                                    <option value="">{{ __('Select country first') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <select class="form-control  js-city" name="shipping_city"
                                                                    data-type="shipping" id="shippingCity" readonly>
                                                                    <option value="">{{ __('Select state first') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="alert alert-warning" id="shippingZipError"
                                                            style="display:none; background-color: #FFBF39; color: #fff;">
                                                            {{ __('Invalid Zip Code, fill the fields manually!') }}
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <div class="order-note mt-3">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <input type="text" id="Order_Note" class="form-control"
                                                                    name="order_note"
                                                                    placeholder="{{ __('Order Note') }} ({{ __('Optional') }})"
                                                                    value="{{ old('order_note') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12  mt-3">
                                                            <p><small>* {{ __('indicates a required field') }}</small></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-step2" role="tabpanel"
                                            aria-labelledby="pills-step2-tab">
                                            <div class="content-box">
                                                <div class="content">
                                                    @if (env('ENABLE_CUSTOMER_MESSAGE'))
                                                        <div class="content alert  alert-warning" role="alert">
                                                            <i class="icofont-exclamation-tringle"></i>
                                                            {{ __('Consult with Seller') }}
                                                        </div>
                                                    @endif
                                                    <div class="order-area">
                                                        @foreach ($products as $product)
                                                            <div class="order-item">
                                                                <div class="product-img">
                                                                    <div class="d-flex">
                                                                        <img src="{{ filter_var($product['item']['photo'], FILTER_VALIDATE_URL)
                                                                            ? $product['item']['photo']
                                                                            : asset('storage/images/products/' . $product['item']['photo']) }}"
                                                                            alt="product" height="80" width="80"
                                                                            class="p-1">
                                                                    </div>
                                                                </div>
                                                                <div class="product-content">
                                                                    <p class="name"><a
                                                                            href="{{ route('front.product', $product['item']['slug']) }}"
                                                                            target="_blank">{{ $product['item']->name }}</a></p>
                                                                    <div class="unit-price">
                                                                        <h5 class="label">{{ __('Price') }} : </h5>
                                                                        <p>{{ App\Models\Product::convertPrice($product['item']['price']) }}
                                                                        </p>
                                                                    </div>
                                                                    @if (!empty($product['size']))
                                                                        <div class="unit-price">
                                                                            <h5 class="label">{{ __('Size') }} : </h5>
                                                                            <p>{{ str_replace('-', ' ', $product['size']) }}</p>
                                                                        </div>
                                                                    @endif

                                                                    @if (env('ENABLE_CUSTOM_PRODUCT') || env('ENABLE_CUSTOM_PRODUCT_NUMBER'))
                                                                        @if (!empty($product['customizable_name']))
                                                                            <div class="unit-price">
                                                                                <h5 class="label">{{ __('Custom Name') }} : </h5>
                                                                                <p>{{ $product['customizable_name'] }}</p>
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                    @if (env('ENABLE_CUSTOM_PRODUCT'))
                                                                        @if (!empty($product['customizable_gallery']))
                                                                            <div class="unit-price" style="margin-top: 5px;">
                                                                                <h5 class="label">{{ __('Photo') }} : </h5>
                                                                                <img src="{{ asset('storage/images/galleries/' . $product['customizable_gallery']) }}"
                                                                                    style="width: 33px; border-radius: 30px; margin-left: 5px; margin-top: -9px; "></img>
                                                                            </div>
                                                                        @endif

                                                                        @if (!empty($product['customizable_logo']))
                                                                            <div class="unit-price"
                                                                                style="margin-top: 15px; margin-bottom: 5px;">
                                                                                <h5 class="label">{{ __('Logo') }} : </h5>
                                                                                <img src="{{ asset('storage/images/custom-logo/' . $product['customizable_logo']) }}"
                                                                                    style="width: 33px; margin-left: 5px; margin-top: -9px; "></img>
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                    @if (env('ENABLE_CUSTOM_PRODUCT_NUMBER'))
                                                                        @if (!empty($product['customizable_number']))
                                                                            <div class="unit-price">
                                                                                <h5 class="label">{{ __('Custom Number') }} : </h5>
                                                                                <p>{{ $product['customizable_number'] }}</p>
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                    @if (!empty($product['color']))
                                                                        <div class="unit-price">
                                                                            <h5 class="label">{{ __('Color') }} : </h5>
                                                                            <span id="color-bar"
                                                                                style="border: 10px solid {{ $product['color'] == '' ? ' white' : '#' . $product['color'] }};"></span>
                                                                        </div>
                                                                    @endif
                                                                    @if (!empty($product['keys']))
                                                                        @foreach (array_combine(explode(',', $product['keys']), explode('~', $product['values'])) as $key => $value)
                                                                            <div class="quantity">
                                                                                <h5 class="label">
                                                                                    {{ App\Models\Attribute::where('input_name', $key)->first()->name }}
                                                                                    :
                                                                                </h5>
                                                                                <span class="qttotal">{{ $value }} </span>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif

                                                                    <div class="quantity">
                                                                        <h5 class="label">{{ __('Quantity') }} : </h5>
                                                                        <span class="qttotal">{{ $product['qty'] }} </span>
                                                                    </div>

                                                                    <div class="total-price">
                                                                        <h5 class="label">{{ __('Total Price') }} : </h5>
                                                                        <p>{{ App\Models\Product::convertPrice($product['price']) }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-step3" role="tabpanel"
                                            aria-labelledby="pills-step3-tab">
                                            <div class="content-box">
                                                <div class="content">
                                                    <div class="billing-info-area {{ $digital == 1 ? 'd-none' : '' }}">
                                                        <h4 class="title">
                                                            {{ __('Shipping Info') }}
                                                        </h4>
                                                        <ul class="info-list">
                                                            <li>
                                                                <p id="final_shipping_user"></p>
                                                            </li>
                                                            <li>
                                                                <p id="final_shipping_location"></p>
                                                            </li>
                                                            <li>
                                                                <p id="final_shipping_zip"></p>
                                                            </li>
                                                            <li>
                                                                <p id="final_shipping_phone"></p>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="payment-information">
                                                        <h4 class="title">
                                                            {{ __('Payment Info') }}
                                                        </h4>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="nav flex-column" role="tablist"aria-orientation="vertical">
                                                                    @if (config('gateways.bancard') && $gs->is_bancard == 1)
                                                                        <a class="nav-link payment" data-val="bancard"
                                                                            data-show="yes"
                                                                            data-form="{{ route('bancard.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'bancard', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab1-tab" data-toggle="pill"
                                                                            href="#v-pills-tab1" role="tab"
                                                                            aria-controls="v-pills-tab1" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                Bancard
                                                                                @if ($gs->bancard_text != null)
                                                                                    <small>
                                                                                        {{ $gs->bancard_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if (config('gateways.cielo') && $gs->is_cielo == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('cielo.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'cielo', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab2-tab" data-toggle="pill"
                                                                            href="#v-pills-tab2" role="tab"
                                                                            aria-controls="v-pills-tab2" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                Cielo
                                                                                @if ($gs->cielo_text != null)
                                                                                    <small>
                                                                                        {{ $gs->cielo_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if (config('gateways.mercado_pago') && $gs->is_mercadopago == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('mercadopago.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'mercadopago', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab3-tab" data-toggle="pill"
                                                                            href="#v-pills-tab3" role="tab"
                                                                            aria-controls="v-pills-tab3" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                Mercado Pago
                                                                                @if ($gs->mercadopago_text != null)
                                                                                    <small>
                                                                                        {{ $gs->mercadopago_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if (config('gateways.pagarme') && $gs->is_pagarme == 1)
                                                                        <a class="nav-link payment" data-val="pagarme" data-show="no"
                                                                            data-form="{{ route('pagarme.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'pagarme', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab14-tab" data-toggle="pill"
                                                                            href="#v-pills-tab14" role="tab"
                                                                            aria-controls="v-pills-tab14" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                Pagarme
                                                                                @if ($gs->pagarme_text != null)
                                                                                    <small>
                                                                                        {{ $gs->pagarme_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if (config('gateways.pagseguro') && $gs->is_pagseguro == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('pagseguro.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'pagseguro', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab12-tab" data-toggle="pill"
                                                                            href="#v-pills-tab12" role="tab"
                                                                            aria-controls="v-pills-tab12" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                PagSeguro
                                                                                @if ($gs->pagseguro_text != null)
                                                                                    <small>
                                                                                        {{ $gs->pagseguro_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if (config('gateways.rede') && $gs->is_rede == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('rede.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'rede', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab14-tab" data-toggle="pill"
                                                                            href="#v-pills-tab14" role="tab"
                                                                            aria-controls="v-pills-tab14" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                Rede
                                                                                @if ($gs->rede_text != null)
                                                                                    <small>
                                                                                        {{ $gs->rede_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if (config('gateways.paghiper') && $gs->is_paghiper == 1 && Session::get('cart')->totalPrice >= 3)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('paghiper.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'paghiper', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab14-tab" data-toggle="pill"
                                                                            href="#v-pills-tab14" role="tab"
                                                                            aria-controls="v-pills-tab14" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                @if ($gs->paghiper_is_discount)
                                                                                    {{ __('Bank Slip') }} (PagHiper) -
                                                                                    {{ $gs->paghiper_discount }}%
                                                                                    {{ __('of discount on the amount of the ticket.') }}
                                                                                @else
                                                                                    {{ __('Bank slip') }} (PagHiper)
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif

                                                                    @if (config('gateways.paghiper_pix') && $gs->is_paghiper_pix == 1 && Session::get('cart')->totalPrice >= 3)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('paghiper.pix-submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'paghiper-pix', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab14-tab" data-toggle="pill"
                                                                            href="#v-pills-tab14" role="tab"
                                                                            aria-controls="v-pills-tab14" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                @if ($gs->paghiper_pix_is_discount && $gs->paghiper_pix_discount > 0)
                                                                                    {{ __('PIX') }} (PagHiper) -
                                                                                    {{ $gs->paghiper_pix_discount }}%
                                                                                    {{ __('of discount on the amount of the ticket.') }}
                                                                                @else
                                                                                    {{ __('PIX') }} (PagHiper)
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif

                                                                    @if (config('gateways.pay42') && $gs->is_pay42_pix == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('pay42.pix-submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'pay42-pix', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab14-tab" data-toggle="pill"
                                                                            href="#v-pills-tab14" role="tab"
                                                                            aria-controls="v-pills-tab14" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                Pay42 pix
                                                                                <small>
                                                                                    {{ __('Pay with pay42 pix') }}
                                                                                </small>
                                                                            </p>
                                                                        </a>
                                                                    @endif

                                                                    @if (config('gateways.pay42') && $gs->is_pay42_billet == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('pay42.billet-submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'pay42-billet', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab14-tab" data-toggle="pill"
                                                                            href="#v-pills-tab14" role="tab"
                                                                            aria-controls="v-pills-tab14" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                Pay42 billet
                                                                                <small>
                                                                                    {{ __('Pay with pay42 billet') }}
                                                                                </small>
                                                                            </p>
                                                                        </a>
                                                                    @endif

                                                                    @if (config('gateways.pay42') && $gs->is_pay42_card == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('pay42.card-submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'pay42-card', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab14-tab" data-toggle="pill"
                                                                            href="#v-pills-tab14" role="tab"
                                                                            aria-controls="v-pills-tab14" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                Pay42 card
                                                                                <small>
                                                                                    {{ __('Pay with pay42 card') }}
                                                                                </small>
                                                                            </p>
                                                                        </a>
                                                                    @endif

                                                                    @if (config('gateways.pagopar') && $gs->is_pagopar == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('pagopar.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'pagopar', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab12-tab" data-toggle="pill"
                                                                            href="#v-pills-tab12" role="tab"
                                                                            aria-controls="v-pills-tab12" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                Pagopar
                                                                                @if ($gs->pagopar_text != null)
                                                                                    <small>
                                                                                        {{ $gs->pagopar_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if (config('gateways.paypal') && $gs->is_paypal == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('paypal.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'paypal', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab4-tab" data-toggle="pill"
                                                                            href="#v-pills-tab4" role="tab"
                                                                            aria-controls="v-pills-tab4" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                {{ __('PayPal Express') }}
                                                                                @if ($gs->paypal_text != null)
                                                                                    <small>
                                                                                        {{ $gs->paypal_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if ($gs->stripe_check == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="yes"
                                                                            data-form="{{ route('stripe.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'stripe', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab5-tab" data-toggle="pill"
                                                                            href="#v-pills-tab5" role="tab"
                                                                            aria-controls="v-pills-tab5" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                {{ __('Credit Card') }}
                                                                                @if ($gs->stripe_text != null)
                                                                                    <small>
                                                                                        {{ $gs->stripe_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if ($gs->cod_check == 1)
                                                                        @if ($digital == 0)
                                                                            <a class="nav-link payment" data-val="" data-show="no"
                                                                                data-form="{{ route('cash.submit') }}"
                                                                                data-href="{{ route('front.load.payment', ['slug1' => 'cod', 'slug2' => 0]) }}"
                                                                                id="v-pills-tab6-tab" data-toggle="pill"
                                                                                href="#v-pills-tab6" role="tab"
                                                                                aria-controls="v-pills-tab6" aria-selected="false">
                                                                                <div class="icon">
                                                                                    <span class="radio"></span>
                                                                                </div>
                                                                                <p>
                                                                                    {{ __('Cash On Delivery') }}
                                                                                    @if ($gs->cod_text != null)
                                                                                        <small>
                                                                                            {{ $gs->cod_text }}
                                                                                        </small>
                                                                                    @endif
                                                                                </p>
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                    @if ($gs->bank_check == 1 && $bank_accounts->isNotEmpty())
                                                                        @if ($digital == 0)
                                                                            <a class="nav-link payment" data-val="bankDeposit"
                                                                                data-show="no"
                                                                                data-form="{{ route('bank.submit') }}"
                                                                                data-href="{{ route('front.load.payment', ['slug1' => 'cod', 'slug2' => 0]) }}"
                                                                                id="v-pills-tab13-tab" data-toggle="pill"
                                                                                href="#v-pills-tab13" role="tab"
                                                                                aria-controls="v-pills-tab13" aria-selected="false">
                                                                                <div class="icon">
                                                                                    <span class="radio"></span>
                                                                                </div>
                                                                                <p>
                                                                                    {{ __('Bank Deposit') }}
                                                                                    @if ($gs->bank_text != null)
                                                                                        <small>
                                                                                            {{ $gs->bank_text }}
                                                                                        </small>
                                                                                    @endif
                                                                                </p>
                                                                            </a>
                                                                            <div class="container" id="teste"
                                                                                style="margin-top: 10px; font-size: x-medium;">
                                                                                @foreach ($bank_accounts as $bank_account)
                                                                                    <ul class="list-group" style="margin-top: 10px">
                                                                                        <li class="list-group-item"
                                                                                            style="padding: 5px;">
                                                                                            {{ strtoupper($bank_account->name) }}</li>
                                                                                        <li class="list-group-item">
                                                                                            {!! nl2br(str_replace('', '&nbsp;', $bank_account->info)) !!}</li>
                                                                                    </ul>
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                    @if ($gs->is_instamojo == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('instamojo.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'instamojo', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab7-tab" data-toggle="pill"
                                                                            href="#v-pills-tab7" role="tab"
                                                                            aria-controls="v-pills-tab7" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                {{ __('Instamojo') }}
                                                                                @if ($gs->instamojo_text != null)
                                                                                    <small>
                                                                                        {{ $gs->instamojo_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if ($gs->is_paytm == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('paytm.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'paytm', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab8-tab" data-toggle="pill"
                                                                            href="#v-pills-tab8" role="tab"
                                                                            aria-controls="v-pills-tab8" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                {{ __('Paytm') }}
                                                                                @if ($gs->paytm_text != null)
                                                                                    <small>
                                                                                        {{ $gs->paytm_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if ($gs->is_razorpay == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('razorpay.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'razorpay', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab9-tab" data-toggle="pill"
                                                                            href="#v-pills-tab9" role="tab"
                                                                            aria-controls="v-pills-tab9" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                {{ __('Razorpay') }}
                                                                                @if ($gs->razorpay_text != null)
                                                                                    <small>
                                                                                        {{ $gs->razorpay_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if ($gs->is_paystack == 1)
                                                                        <a class="nav-link payment" data-val="paystack"
                                                                            data-show="no"
                                                                            data-form="{{ route('paystack.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'paystack', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab10-tab" data-toggle="pill"
                                                                            href="#v-pills-tab10" role="tab"
                                                                            aria-controls="v-pills-tab10" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                {{ __('Paystack') }}
                                                                                @if ($gs->paystack_text != null)
                                                                                    <small>
                                                                                        {{ $gs->paystack_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if ($gs->is_molly == 1)
                                                                        <a class="nav-link payment" data-val="" data-show="no"
                                                                            data-form="{{ route('molly.submit') }}"
                                                                            data-href="{{ route('front.load.payment', ['slug1' => 'molly', 'slug2' => 0]) }}"
                                                                            id="v-pills-tab11-tab" data-toggle="pill"
                                                                            href="#v-pills-tab11" role="tab"
                                                                            aria-controls="v-pills-tab11" aria-selected="false">
                                                                            <div class="icon">
                                                                                <span class="radio"></span>
                                                                            </div>
                                                                            <p>
                                                                                {{ __('Mollie Payment') }}
                                                                                @if ($gs->molly_text != null)
                                                                                    <small>
                                                                                        {{ $gs->molly_text }}
                                                                                    </small>
                                                                                @endif
                                                                            </p>
                                                                        </a>
                                                                    @endif
                                                                    @if ($digital == 0)
                                                                        @foreach ($gateways as $gt)
                                                                            <a class="nav-link payment" data-val=""
                                                                                data-show="yes"
                                                                                data-form="{{ route('gateway.submit') }}"
                                                                                data-href="{{ route('front.load.payment', ['slug1' => 'other', 'slug2' => $gt->id]) }}"
                                                                                id="v-pills-tab{{ $gt->id }}-tab"
                                                                                data-toggle="pill"
                                                                                href="#v-pills-tab{{ $gt->id }}"
                                                                                role="tab"
                                                                                aria-controls="v-pills-tab{{ $gt->id }}"
                                                                                aria-selected="false">
                                                                                <div class="icon">
                                                                                    <span class="radio"></span>
                                                                                </div>
                                                                                <p>
                                                                                    {{ $gt->title }}
                                                                                    @if ($gt->subtitle != null)
                                                                                        <small>
                                                                                            {{ $gt->subtitle }}
                                                                                        </small>
                                                                                    @endif
                                                                                </p>
                                                                            </a>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="pay-area d-none">
                                                                    <div class="tab-content {{ $gs->is_bancard == 1 ? ' d-none' : '' }} " id="v-pills-tabContent">
                                                                        @if (config('gateways.bancard') && $gs->is_bancard == 1)
                                                                            <div class="tab-pane fade d-none" id="v-pills-tab1"
                                                                                role="tabpanel" aria-labelledby="v-pills-tab1-tab">
                                                                            </div>
                                                                        @endif
                                                                        @if (config('gateways.cielo') && $gs->is_cielo == 1)
                                                                            <div class="tab-pane fade" id="v-pills-tab2"
                                                                                role="tabpanel" aria-labelledby="v-pills-tab2-tab">
                                                                            </div>
                                                                        @endif
                                                                        @if (config('gateways.mercado_pago') && $gs->is_mercadopago == 1)
                                                                            <div class="tab-pane fade" id="v-pills-tab3"
                                                                                role="tabpanel" aria-labelledby="v-pills-tab3-tab">
                                                                            </div>
                                                                        @endif

                                                                        <div class="tab-pane fade" id="v-pills-tab14"
                                                                            role="tabpanel" aria-labelledby="v-pills-tab14-tab">
                                                                        </div>

                                                                        @if (config('gateways.pagseguro') && $gs->is_pagseguro == 1)
                                                                            <div class="tab-pane fade" id="v-pills-tab12"
                                                                                role="tabpanel" aria-labelledby="v-pills-tab12-tab">
                                                                            </div>
                                                                        @endif
                                                                        {{-- @if (config('gateways.cielo') && $gs->is_cielo == 1) --}}
                                                                        <div class="tab-pane fade" id="v-pills-tab14"
                                                                            role="tabpanel" aria-labelledby="v-pills-tab14-tab">
                                                                        </div>
                                                                        {{-- @endif --}}
                                                                        @if ($gs->paypal_check == 1)
                                                                            <div class="tab-pane fade" id="v-pills-tab4"
                                                                                role="tabpanel" aria-labelledby="v-pills-tab4-tab">
                                                                            </div>
                                                                        @endif
                                                                        @if ($gs->stripe_check == 1)
                                                                            <div class="tab-pane fade" id="v-pills-tab5"
                                                                                role="tabpanel" aria-labelledby="v-pills-tab5-tab">
                                                                            </div>
                                                                        @endif
                                                                        @if ($gs->cod_check == 1)
                                                                            @if ($digital == 0)
                                                                                <div class="tab-pane fade" id="v-pills-tab6"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="v-pills-tab6-tab"></div>
                                                                            @endif
                                                                        @endif
                                                                        @if ($gs->bank_check == 1)
                                                                            @if ($digital == 0)
                                                                                <div class="tab-pane fade" id="v-pills-tab13"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="v-pills-tab13-tab">
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                        @if ($gs->is_instamojo == 1)
                                                                            <div class="tab-pane fade" id="v-pills-tab7"
                                                                                role="tabpanel" aria-labelledby="v-pills-tab7-tab">
                                                                            </div>
                                                                        @endif
                                                                        @if ($gs->is_paytm == 1)
                                                                            <div class="tab-pane fade" id="v-pills-tab8"
                                                                                role="tabpanel" aria-labelledby="v-pills-tab8-tab">
                                                                            </div>
                                                                        @endif
                                                                        @if ($gs->is_razorpay == 1)
                                                                            <div class="tab-pane fade" id="v-pills-tab9"
                                                                                role="tabpanel" aria-labelledby="v-pills-tab9-tab">
                                                                            </div>
                                                                        @endif
                                                                        @if ($gs->is_paystack == 1)
                                                                            <div class="tab-pane fade" id="v-pills-tab10"
                                                                                role="tabpanel" aria-labelledby="v-pills-tab10-tab">
                                                                            </div>
                                                                        @endif
                                                                        @if ($gs->is_molly == 1)
                                                                            <div class="tab-pane fade" id="v-pills-tab11"
                                                                                role="tabpanel" aria-labelledby="v-pills-tab11-tab">
                                                                            </div>
                                                                        @endif
                                                                        @if ($digital == 0)
                                                                            @foreach ($gateways as $gt)
                                                                                <div class="tab-pane fade"
                                                                                    id="v-pills-tab{{ $gt->id }}"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="v-pills-tab{{ $gt->id }}-tab">
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
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
                                <input type="hidden" data-type="punto" id="punto-selected" class="puntocontroller"
                                    name="puntoentrega" value="">
                                <input type="hidden" id="punto-id" class="puntoid" name="puntoidvalue" value="">
                                <input type="hidden" id="aex-city" name="aex_city" value="0">
                                <input type="hidden" id="shipping-cost" name="shipping_cost" value="3">
                                <input type="hidden" id="packing-cost" name="packing_cost" value="1">
                                <input type="hidden" name="dp" value="{{ $digital }}">
                                <input type="hidden" name="tax" value="{{ $gs->tax }}">
                                <input type="hidden" name="totalQty" value="{{ $totalQty }}">
                                <input type="hidden" name="vendor_shipping_id" value="{{ $vendor_shipping_id }}">
                                <input type="hidden" name="vendor_packing_id" value="{{ $vendor_packing_id }}">
                                @if (Session::has('coupon_total'))
                                    <input type="hidden" name="total" id="grandtotal" value="{{ $totalPrice }}">
                                    <input type="hidden" id="tgrandtotal" value="{{ $totalPrice }}">
                                @elseif(Session::has('coupon_total1'))
                                    <input type="hidden" name="total" id="grandtotal"
                                        value="{{ preg_replace('/[^0-9,.]/', '', Session::get('coupon_total1')) }}">
                                    <input type="hidden" id="tgrandtotal"
                                        value="{{ preg_replace('/[^0-9,.]/', '', Session::get('coupon_total1')) }}">
                                @else
                                    <input type="hidden" name="total" id="grandtotal"
                                        value="{{ round($totalPrice * $curr_checkout->value, 2) }}">
                                    <input type="hidden" id="tgrandtotal"
                                        value="{{ round($totalPrice * $curr_checkout->value, 2) }}">
                                @endif
                                <input type="hidden" name="coupon_code" id="coupon_code"
                                    value="{{ Session::has('coupon_code') ? Session::get('coupon_code') : '' }}">
                                <input type="hidden" name="coupon_discount" id="coupon_discount"
                                    value="{{ Session::has('coupon') ? Session::get('coupon') : '' }}">
                                <input type="hidden" name="coupon_id" id="coupon_id"
                                    value="{{ Session::has('coupon') ? Session::get('coupon_id') : '' }}">
                                <input type="hidden" name="user_id" id="user_id"
                                    value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->id : '' }}">

                                <!-- NEW CHECKOUT-->    
                                <input id="nc_selectedShipping" name="nc_selectedShipping" type="hidden">
                                <input id="nc_priceShipping" name="nc_priceShipping" type="hidden">
                                <input id="nc_typeShipping" name="nc_typeShipping" type="hidden">
                                <input id="nc_addressShipping" name="nc_addressShipping" type="hidden">
                                
                            </form>
                        </div>
                    </div>
                </div>
                @if (Session::has('cart'))
                    <div class="col-lg-4 d-none">
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
                                                class="cart-total">{{ Session::has('cart') ? App\Models\Product::convertPrice(Session::get('cart')->totalPrice) : '0.00' }}</b>
                                        </P>
                                    </li>
                                    @if ($gs->tax != 0)
                                        <li>
                                            <p>
                                                {{ __('Tax') }}
                                            </p>
                                            <P>
                                                <b> {{ $gs->tax }}% </b>
                                            </P>
                                        </li>
                                    @endif
                                    @if (Session::has('coupon'))
                                        <li class="discount-bar">
                                            <p>
                                                {{ __('Discount') }} <span
                                                    class="dpercent">{{ Session::get('coupon_percentage') == 0 ? '' : '(' . Session::get('coupon_percentage') . ')' }}</span>
                                            </p>
                                            <P>
                                                @if ($gs->currency_format == 0)
                                                    <b
                                                        id="discount">{{ $curr_checkout->sign }}{{ number_format(
                                                            Session::get('coupon'),
                                                            $curr_checkout->decimal_digits,
                                                            $curr_checkout->decimal_separator,
                                                            $curr_checkout->thousands_separator,
                                                        ) }}</b>
                                                @else
                                                    <b
                                                        id="discount">{{ number_format(
                                                            Session::get('coupon'),
                                                            $curr_checkout->decimal_digits,
                                                            $curr_checkout->decimal_separator,
                                                            $curr_checkout->thousands_separator,
                                                        ) }}{{ $curr_checkout->sign }}</b>
                                                @endif
                                            </P>
                                        </li>
                                    @else
                                        <li class="discount-bar d-none">
                                            <p>
                                                {{ __('Discount') }} <span class="dpercent"></span>
                                            </p>
                                            <P>
                                                <b
                                                    id="discount">{{ $curr_checkout->sign }}{{ number_format(
                                                        Session::get('coupon'),
                                                        $curr_checkout->decimal_digits,
                                                        $curr_checkout->decimal_separator,
                                                        $curr_checkout->thousands_separator,
                                                    ) }}</b>
                                            </P>
                                        </li>
                                    @endif
                                </ul>
                                <div class="total-price">
                                    <p style="margin-bottom:0px;">
                                        {{ __('Total') }}
                                    </p>
                                    <p style="margin-bottom:0px;">
                                        @if (Session::has('coupon_total'))
                                            @if ($gs->currency_format == 0)
                                                <span
                                                    id="total-cost">{{ $curr_checkout->sign }}{{ number_format(
                                                        $totalPrice,
                                                        $curr_checkout->decimal_digits,
                                                        $curr_checkout->decimal_separator,
                                                        $curr_checkout->thousands_separator,
                                                    ) }}</span>
                                            @else
                                                <span
                                                    id="total-cost">{{ number_format(
                                                        $totalPrice,
                                                        $curr_checkout->decimal_digits,
                                                        $curr_checkout->decimal_separator,
                                                        $curr_checkout->thousands_separator,
                                                    ) }}{{ $curr_checkout->sign }}</span>
                                            @endif
                                    </p>
                                </div>
                                <div class="total-price">
                                    <p></p>
                                    <p>
                                        <span
                                            id="total-cost2">{{ App\Models\Product::convertPriceReverse($totalPrice) }}</span>
                                    @elseif(Session::has('coupon_total1'))
                                        @if ($gs->currency_format == 0)
                                            <span
                                                id="total-cost">{{ $curr_checkout->sign }}{{ number_format(
                                                    Session::get('coupon_total1'),
                                                    $curr_checkout->decimal_digits,
                                                    $curr_checkout->decimal_separator,
                                                    $curr_checkout->thousands_separator,
                                                ) }}</span>
                                        @else
                                            <span
                                                id="total-cost">{{ number_format(
                                                    Session::get('coupon_total1'),
                                                    $curr_checkout->decimal_digits,
                                                    $curr_checkout->decimal_separator,
                                                    $curr_checkout->thousands_separator,
                                                ) }}{{ $curr_checkout->sign }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="total-price">
                                    <p></p>
                                    <p>
                                        <span id="total-cost2">{{ App\Models\Product::convertPriceReverse(Session::get('coupon_total1')) }}</span>
                                    @else
                                        <span id="total-cost">{{ App\Models\Product::convertPrice($totalPrice) }}</span>
                                    </p>
                                </div>
                                <div class="total-price">
                                    <p></p>
                                    <p>
                                        <span id="total-cost2">{{ App\Models\Product::signFirstPrice($totalPrice) }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="cupon-box">
                                    <div id="coupon-link">
                                        <img src="{{ asset('assets/images/tag.png') }}">
                                        {{ __('Have a promotion code?') }}
                                    </div>
                                    <form id="check-coupon-form" class="coupon">
                                        <input type="text" placeholder="{{ __('Coupon Code') }} *" id="code" required=""
                                            autocomplete="off">
                                        <button type="submit">{{ __('Apply') }}</button>
                                    </form>
                                </div>
                            @if ($digital == 0)
                                {{-- Shipping Method Area Start --}}
                                <div class="shipping-area-class" id="shipping-area">
                                    <h4 class="title">{{ __('Shipping Method') }}</h4>
                                    <p id="empty-ship">{{ __('Input Address') }}</p>
                                    <p id="pickup-ship" class="d-none">{{ __('Pickup') }}</p>
                                </div>

                            @if ($gs->is_aex && config('features.aex_shipping'))
                                <div id="aex-box">
                                    <div class="alert alert-info">
                                        <small>{{ __('Please select location bellow to show AEX Shipping option') }}</small>
                                    </div>
                                    <form id="freight-form-aex" class="coupon">
                                        <select class="form-control" id="aex_destination" name="aex_destination">
                                            <option value="">{{ __('Select City') }}</option>
                                            @foreach ($aex_cities as $city)
                                                <option value="{{ $city->codigo_ciudad }}">{{ $city->denominacion }} -
                                                    {{ $city->departamento_denominacion }}</option>
                                            @endforeach
                                        </select>
                                        <div class="shipping-area-class text-left mt-4" id="shipping-area-aex"></div>
                                    </form>
                                </div>
                            @endif

                            <div class="packeging-area">
                                <h4 class="title">{{ __('Packaging') }}</h4>
                                @foreach ($package_data as $data)
                                    <div class="radio-design">
                                        <input type="radio" class="packing" id="free-package{{ $data->id }}"
                                            name="packeging" data-price="{{ $data->price * $curr_checkout->value }}"
                                            data-id="{{ $data->id }}" value="{{ $data->id }}"
                                            {{ $loop->first ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                        <label for="free-package{{ $data->id }}">
                                            {{ $data->title }}
                                            @if ($data->price != 0)
                                                +
                                                {{ $curr_checkout->sign }}{{ number_format(
                                                    $data->price * $curr_checkout->value,
                                                    $curr_checkout->decimal_digits,
                                                    $curr_checkout->decimal_separator,
                                                    $curr_checkout->thousands_separator,
                                                ) }}
                                            @endif
                                            <small>{{ $data->subtitle }}</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="final-price">
                                <span>{{ __('Final Price') }} :</span>
                                @if (Session::has('coupon_total'))
                                    @if ($gs->currency_format == 0)
                                        <span
                                            id="final-cost">{{ $curr_checkout->sign }}{{ number_format(
                                                $totalPrice,
                                                $curr_checkout->decimal_digits,
                                                $curr_checkout->decimal_separator,
                                                $curr_checkout->thousands_separator,
                                            ) }}</span>
                                    @else
                                        <span
                                            id="final-cost">{{ number_format(
                                                $totalPrice,
                                                $curr_checkout->decimal_digits,
                                                $curr_checkout->decimal_separator,
                                                $curr_checkout->thousands_separator,
                                            ) }}{{ $curr_checkout->sign }}</span>
                                    @endif
                            </div>
                            <div class="total-price">
                                <span></span>
                                <span id="final-cost2">{{ App\Models\Product::signFirstPrice($totalPrice) }}</span>
                            @elseif(Session::has('coupon_total1'))
                                <span id="final-cost"> {{ Session::get('coupon_total1') }}</span>
                            </div>
                            <div class="total-price">
                                <span></span>
                                <span
                                    id="final-cost2">{{ App\Models\Product::convertPriceReverse(Session::get('coupon_total1')) }}</span>
                            @else
                                <span id="final-cost">{{ App\Models\Product::convertPrice($totalPrice) }}</span>
                            </div>
                            <div class="total-price">
                                <span></span>
                                <span id="final-cost2">{{ App\Models\Product::signFirstPrice($totalPrice) }}</span>
                            @endif
                            </div>
                        @endif
                            <div class="row" id="buttons1">
                                <div class="col-lg-12  mt-3">
                                    <div class="bottom-area paystack-area-btn button1">
                                        <button type="submit" class="mybtn1 fbPaymentInfo" onclick="scrolltotop()" id="button1"
                                            form="myform">{{ __('Continue') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row none" id="buttons2">
                                <div class="col-lg-12 mt-3 center-buttons">
                                    <div class="bottom-area">
                                        <a href="javascript:;" onclick="back1();scrolltotop()" id="step1-btn" class="mybtn1 mr-3"
                                            form="myform">{{ __('Back') }}</a>
                                        <button href="javascript:;" onclick="continue2();scrolltotop()" id="step3-btn"
                                            class="mybtn1">{{ __('Continue') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row none" id="buttons3">
                                <div class="col-lg-12 mt-3">

                                    <div class="alert alert-danger validation alert-ajax" style="display:none;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                                aria-hidden="true">×</span></button>
                                        <p class="left-text">x</p>
                                    </div>

                                    <div class="bottom-area center-buttons">
                                        <a href="javascript:;" onclick="back2();scrolltotop()" id="step2-btn" class="mybtn1 mr-3"
                                            form="myform">{{ __('Back') }}</a>
                                    
                                    </div>
                                </div>
                            </div>
                            @if ($gs->is_simplified_checkout && $gs->simplified_checkout_number)
                                <a href="#" id="whatsapp-modal" class="order-btn mt-2 d-none" data-toggle="modal"
                                    data-target="#simplified-checkout-modal">{{ __('Simplified Checkout') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<script src="{{ asset('assets/checkout/scripts.js') }}"></script>

<!-- Check Out Area End-->
@if ($checked)
    <!-- LOGIN MODAL -->
    <div class="modal fade" id="comment-log-reg1" data-keyboard="false" data-backdrop="static" tabindex="-1"
        role="dialog" aria-labelledby="comment-log-reg-Title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-label="Close">
                        <a href="{{ url()->previous() }}"><span aria-hidden="true">&times;</span></a>
                    </button>
                </div>
                <div class="modal-body">
                    <nav class="comment-log-reg-tabmenu">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link login active" id="nav-log-tab" data-toggle="tab"
                                href="#nav-log" role="tab" aria-controls="nav-log" aria-selected="true">
                                {{ __('Login') }}
                            </a>
                            <a class="nav-item nav-link" id="nav-reg-tab" data-toggle="tab" href="#nav-reg"
                                role="tab" aria-controls="nav-reg" aria-selected="false">
                                {{ __('Register') }}
                            </a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-log" role="tabpanel"
                            aria-labelledby="nav-log-tab">
                            <div class="login-area">
                                <div class="header-area">
                                    <h4 class="title">{{ __('LOGIN NOW') }}</h4>
                                </div>
                                <div class="login-form signin-form">
                                    @include('includes.admin.form-login')
                                    <form id="loginform" action="{{ route('user.login.submit') }}"
                                        method="POST">
                                        {{ csrf_field() }}
                                        <div class="form-input">
                                            <input type="email" name="email"
                                                placeholder="{{ __('Type Email Address') }} *" required="">
                                            <i class="icofont-user-alt-5"></i>
                                        </div>
                                        <div class="form-input">
                                            <input type="password" class="Password" name="password"
                                                placeholder="{{ __('Type Password') }} *" required="">
                                            <i class="icofont-ui-password"></i>
                                        </div>
                                        <div class="form-forgot-pass">
                                            <div class="left">
                                                <input type="hidden" name="modal" value="1">
                                                <input type="checkbox" name="remember" id="mrp"
                                                    {{ old('remember') ? 'checked' : '' }}>
                                                <label for="mrp">{{ __('Remember Password') }}</label>
                                            </div>
                                            <div class="right">
                                                <a href="{{ route('user-forgot') }}">
                                                    {{ __('Forgot Password?') }}
                                                </a>
                                            </div>
                                        </div>
                                        <input id="authdata" type="hidden"
                                            value="{{ __('Authenticating...') }}">
                                        <button type="submit" class="submit-btn">{{ __('Login') }}</button>
                                        @if (App\Models\Socialsetting::find(1)->f_check == 1 || App\Models\Socialsetting::find(1)->g_check == 1)
                                            <div class="social-area">
                                                <h3 class="title">{{ __('Or') }}</h3>
                                                <p class="text">{{ __('Sign In with social media') }}</p>
                                                <ul class="social-links">
                                                    @if (App\Models\Socialsetting::find(1)->f_check == 1)
                                                        <li>
                                                            <a href="{{ route('social-provider', 'facebook') }}">
                                                                <i class="fab fa-facebook-f"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if (App\Models\Socialsetting::find(1)->g_check == 1)
                                                        <li>
                                                            <a href="{{ route('social-provider', 'google') }}">
                                                                <i class="fab google"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-reg" role="tabpanel" aria-labelledby="nav-reg-tab">
                            <div class="login-area signup-area">
                                <div class="header-area">
                                    <h4 class="title">{{ __('Signup Now') }}</h4>
                                </div>
                                <div class="login-form signup-form">
                                    @include('includes.admin.form-login')
                                    <form id="registerform" action="{{ route('user-register-submit') }}"
                                        method="POST">
                                        {{ csrf_field() }}
                                        <div class="form-input">
                                            <input type="text" class="User Name" name="name"
                                                title="{{ __('Input first name and last name') }}"
                                                placeholder="{{ __('Full Name') }} *" required=""
                                                pattern="^(\S*)\s+(.*)$">
                                            <i class="icofont-user-alt-5"></i>
                                        </div>
                                        <div class="form-input">
                                            <input type="email" class="User Name" name="email"
                                                placeholder="{{ __('Email Address') }} *" required="">
                                            <i class="icofont-email"></i>
                                        </div>
                                        <!-- <div class="form-input">
                                            <input type="text" class="User Name" name="phone"
                                                placeholder="{{ __('Phone Number') }} *" required="">
                                            <i class="icofont-phone"></i>
                                        </div> -->
                                        <!-- <div class="form-input">
                                            <input type="text" class="User Name" name="address"
                                                placeholder="{{ __('Address') }} *" required="">
                                            <i class="icofont-location-pin"></i>
                                        </div> -->
                                        <div class="form-input">
                                            <input type="password" class="Password" name="password"
                                                placeholder="{{ __('Password') }} *" required="">
                                            <i class="icofont-ui-password"></i>
                                        </div>
                                        <div class="form-input">
                                            <input type="password" class="Password" name="password_confirmation"
                                                placeholder="{{ __('Confirm Password') }} *" required="">
                                            <i class="icofont-ui-password"></i>
                                        </div>
                                        <!-- <div class="form-input">
                                            <input placeholder="{{ __('Date of Birth') }}" class="textbox-n" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="birthday" name="birthday" required/>
                                            
                                        </div> -->
                                        <!-- <div class="form-input">
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">{{ __("Gender") }}</option>
                                                <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}> {{ __("Male") }}</option>
                                                <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>{{ __("Female") }}</option>
                                                <option value="O" {{ old('gender') == 'O' ? 'selected' : '' }}>{{ __("Other") }}</option>
                                                <option value="N" {{ old('gender') == 'N' ? 'selected' : '' }}>{{ __("Not Declared") }}</option>
                                            </select>
                                        </div> -->
                                        @if ($gs->is_capcha == 1)
                                            <ul class="captcha-area">
                                                <li>
                                                    <p><img class="codeimg1"
                                                            src="{{ asset('storage/images/capcha_code.png') }}"
                                                            alt=""> <i
                                                            class="fas fa-sync-alt pointer refresh_code "></i></p>
                                                </li>
                                            </ul>
                                            <div class="form-input">
                                                <input type="text" class="Password" name="codes"
                                                    placeholder="{{ __('Enter Code') }} *" required="">
                                                <i class="icofont-refresh"></i>
                                            </div>
                                        @endif
                                        @php
                                            $url = $gs->privacy_policy ? true : false;
                                        @endphp
                                        <div class="form-forgot-pass">
                                            <div class="left">
                                                <input type="checkbox" name="agree_privacy_policy"
                                                    id="agree_privacy_policy">
                                                <label for="agree_privacy_policy">{{ __("I agree with the") }} <a target="_blank"
                                                        href="{{ $url ? route('front.privacypolicy') : '' }}"> {{ __("Privacy Policy") }}</a>.</label>
                                            </div>
                                        </div>
                                        <input id="processdata" type="hidden"
                                            value="{{ __('Processing...') }}">
                                        <button type="submit" class="submit-btn">{{ __('Register') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- LOGIN MODAL ENDS -->
@endif

<!-- BANCARD MODAL -->
    <div class="modal fade" id="iframe-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" style="transition: .5s; padding:10px;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="iframe-container"></div>
                </div>
            </div>
        </div>
    </div>
    <x-loader/>
    <script>
        const myModalEl = document.getElementById('final-btn')
        myModalEl.addEventListener('click', event => {
            document.getElementsByClassName('loader-checkout')[0].classList.add('d-flex')
        })
    </script>
@endsection

@section('scripts')

<script>
    $(document).ready(function () {
        $('input[name="shipping"]').on('change', function () {
            // Obter o valor do rádio selecionado
            var selectedValue = $('input[name="shipping"]:checked').val();
            $('#nc_addressShipping').val('');   

            // Atualizar o valor do input hidden
            $('#nc_selectedShipping').val(selectedValue);

            // Atualizar o valor do input de endereço com base na escolha
            if (selectedValue === '1' || selectedValue === '2') {
                $('#nc_priceShipping').val(10); 
                $('#nc_typeShipping').val('shipto');
                
                var address = '';

                if (selectedValue === '1'){
                    var address = $('#nc_address').val();
                    $('#nc_addressShipping').val(address);
                }

                if (selectedValue === '2'){
                    $('#address').on('input', function() {
                        $('#nc_addressShipping').val($(this).val());
                    });
                    
                }
            } else {
                $('#nc_priceShipping').val(0);
                $('#nc_typeShipping').val('pickup');
                $('#nc_addressShipping').val('1');

                $('#local').on('change', function () {
                    var local = $(this).val();
                    if (local == 1){
                        $('#nc_addressShipping').val('SAX CIUDAD DEL ESTE|1'); 
                    }
                    if (local == 2){
                        $('#nc_addressShipping').val('SAX ASUNCIÓN|2'); 
                    } 
                });          
            }
         });
    });
</script>


<script>
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: 'https://shop.saxdepartment.com' + '/checkout/getStatesOptions',
            data: {
                location_id: 173 //paraguai
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('#shippingState').append('<option value="">{{ __('Selecione seu departamento') }}</option>');
                $('#shippingState').append(data);
            },
            error: function (err) {
                console.log(err);
            },
        })


        $('#shippingState').on('change', function () {
            // Obtém o valor selecionado
            var selectedValue = $(this).val();
            $.ajax({
                type: 'GET',
                url: 'https://shop.saxdepartment.com' + '/checkout/getCitiesOptions',
                data: {
                    location_id: selectedValue //paraguai
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#shippingCity').append(data);
                    $('#shippingCity').removeAttr('readonly');
                },
                error: function (err) {
                    console.log(err);
                },
            })

        });

    });
</script>

<script>
    function checkIfStep2Valid() {
        var idcpf = document.getElementById('billCpf').value
        var idemail = document.getElementById('billEmail').value
        var idtelefone = document.getElementById('billPhone').value
        var idnome = document.getElementById('billName').value
        var allFieldsFilled = true;

        if (idcpf.trim() === '' || idemail.trim() === '' || idtelefone.trim() === '' || idnome.trim() === '') {
            allFieldsFilled = false;
        }

        return allFieldsFilled;
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.4/jquery.inputmask.min.js"></script>
<script>
    window.onload = function() {
        document.getElementById("customer_name").value = document.getElementById("billName").value;
        document.getElementById("customer_phone").value = document.getElementById("billPhone").value;
    }

    var billName = document.getElementById("billName");

    billName.addEventListener("change", function() {
        document.getElementById("customer_name").value = billName.value;
    });

    var billPhone = document.getElementById("billPhone");

    billPhone.addEventListener("change", function() {
        document.getElementById("customer_phone").value = billPhone.value;
    });
</script>

<script>
    $(document).ready(function(){ 
        $('#iframe-modal').on('shown.bs.modal', function (e) {
            document.getElementsByClassName('loader-checkout')[0].classList.remove('d-flex')
        })
        $('#iframe-modal').on('hide.bs.modal', function (e) {
            $.ajax({
                url: '/bancard-close-modal',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        })
    });
</script>

@include('includes.checkout-flow-scripts')
@endsection
