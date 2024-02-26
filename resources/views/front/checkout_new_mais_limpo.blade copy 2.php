@extends('front.themes.theme-15.checkout_layout')

@section('content')

<div class="modal fade" id="iframe-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
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

@if ($checked)
<!-- LOGIN MODAL -->
<div class="modal fade" id="comment-log-reg1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="comment-log-reg-Title" aria-hidden="true">
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
                        <a class="nav-item nav-link login active" id="nav-log-tab" data-toggle="tab" href="#nav-log"
                            role="tab" aria-controls="nav-log" aria-selected="true">
                            {{ __('Login') }}
                        </a>
                        <a class="nav-item nav-link" id="nav-reg-tab" data-toggle="tab" href="#nav-reg" role="tab"
                            aria-controls="nav-reg" aria-selected="false">
                            {{ __('Register') }}
                        </a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-log" role="tabpanel" aria-labelledby="nav-log-tab">
                        <div class="login-area">
                            <div class="header-area">
                                <h4 class="title" style="font-family: auto;">{{ __('LOGIN NOW') }}</h4>
                            </div>
                            <div class="login-form signin-form">
                                @include('includes.admin.form-login')
                                <!-- <form id="loginform" action="{{ route('user.login.submit') }}" method="POST"> -->
                                {{ csrf_field() }}
                                <div class="form-input">
                                    <input type="email" name="email" placeholder="{{ __('Type Email Address') }} *"
                                        required="">
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
                                        <input type="checkbox" name="remember" id="mrp" {{ old('remember') ? 'checked'
                                            : '' }}>
                                        <label for="mrp">{{ __('Remember Password') }}</label>
                                    </div>
                                    <div class="right">
                                        <a href="{{ route('user-forgot') }}">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                    </div>
                                </div>
                                <input id="authdata" type="hidden" value="{{ __('Authenticating...') }}">
                                <button type="submit" class="submit-btn">{{ __('Login') }}</button>
                                @if (App\Models\Socialsetting::find(1)->f_check == 1 ||
                                App\Models\Socialsetting::find(1)->g_check == 1)
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
                                <form id="registerform" action="{{ route('user-register-submit') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-input">
                                        <input type="text" class="User Name" name="name"
                                            title="{{ __('Input first name and last name') }}"
                                            placeholder="{{ __('Full Name') }} *" required="">
                                        <i class="icofont-user-alt-5"></i>
                                    </div>
                                    <div class="form-input">
                                        <input type="email" class="User Name" name="email"
                                            placeholder="{{ __('Email Address') }} *" required="">
                                        <i class="icofont-email"></i>
                                    </div>
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
                                    @if ($gs->is_capcha == 1)
                                    <ul class="captcha-area">
                                        <li>
                                            <p><img class="codeimg1" src="{{ asset('storage/images/capcha_code.png') }}"
                                                    alt=""> <i class="fas fa-sync-alt pointer refresh_code "></i></p>
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
                                            <label for="agree_privacy_policy">{{ __("I agree with the") }} <a
                                                    target="_blank"
                                                    href="{{ $url ? route('front.privacypolicy') : '' }}"> {{
                                                    __("Privacy Policy") }}</a>.</label>
                                        </div>
                                    </div>
                                    <input id="processdata" type="hidden" value="{{ __('Processing...') }}">
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
<form id="myforms" action="" method="POST" class="checkoutform">
    @include('includes.form-success')
    @include('includes.form-error')
    {{ csrf_field() }}

    <header class="bg-black text-center py-3 mb-5"><img src="https://i.ibb.co/1dFF5PK/logosax.png" alt=""></header>
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
            <!-- Step 1 -->
            <div class="step col-10 row align-items-center justify-content-center mt-4">
                <div class="d-flex align-items-center bg-top my-4 py-2">
                    <h6 class="col-8 text-uppercase">{{ __('Product') }}</h6>
                    <h6 class="col-2 d-lg-block d-none text-uppercase">{{ __('Amount') }}</h6>
                    <h6 class="col-2 d-lg-block d-none text-uppercase">{{ __('Price') }}</h6>
                </div> @foreach ($products as $product) <div
                    class="d-flex flex-wrap align-items-center p-0 pb-5 border-bottom-f1 mb-4">
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
                        <h6 class="col-6 text-uppercase">{{ __('Price') }}</h6>
                    </div>
                    <p class="col-lg-2 col-6 m-lg-0 mt-3">{{ $product['qty'] }}</p>
                    <div class="col-lg-2 prices col-6">
                        <h5 class="mb-0 fw-semibold">{{ App\Models\Product::convertPrice($product['item']['price'])
                            }}
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
            <!-- Step 2 -->
            <div class="step col-sm-10 row align-items-center justify-content-center mt-4">
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
                    <div class="col-12 text-center mt-4 d-md-none d-block">
                        <button class="btn-back">{{ __('To go back') }}</button>
                        <button class="px-5 btn-continue" id="step-2-continue">{{ __('Continue')}}</button>
                    </div>
                </div>
            </div>
            <!-- Step 3 -->
            <div class="step col-sm-10 row align-items-center justify-content-center mt-4">
                <div class="d-flex align-items-center p-0 pb-3 border-bottom-f1">
                    <h5 class="fw-semibold">{{ __('Shipping method') }}</h5>
                </div>
                <div class="bg-top mt-5 py-4">
                    <!-- retirar no meu endereço -->
                    <div class="border-bottom-f1 pb-4 d-flex justify-content-between"> @if(Auth::guard('web')->check()
                        && Auth::guard('web')->user()->address != '') <div>
                            <input id="myaddress" name="shipping" value="1" type="radio">
                            <label for="myaddress">{{ __('Receive at my address') }}</label>
                            <p style="font-size: 14px;" class="mb-0 color-1 px-3">
                                {{Auth::guard('web')->user()->address ?? ''}}
                            </p>
                        </div>
                        <h6 class="px-2 color-3">U$10.00</h6> @endif
                    </div>
                    <!-- adicionar endereço -->
                    <div class="border-bottom-f1 py-4 d-flex flex-wrap justify-content-between">
                        <div>
                            <input id="newaddress" name="shipping" value="2" type="radio" checked>
                            <label for="newaddress">{{ __('Add new address') }}</label>
                        </div>
                        <h6 class="px-2 color-3">U$10.00</h6>
                        <div class="d-block col-12 mt-3 new-address">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <p style="font-size: 14px;" class="m-0 color-1 fw-semibold px-1">{{ __('Country') }}
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
                    <!-- retirar na sax -->
                    <div class="py-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div>
                                <input id="withdrawal" name="shipping" value="3" type="radio">
                                <label for="withdrawal">{{ __('Pick up in') }} SAX</label>
                            </div>
                            <select class="select-local d-none mx-2" name="local" id="local">
                                <option value="1">CDE</option>
                                <option value="2">ASUNCIÓN</option>
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
                    <div class="col-12 text-center mt-4 d-md-none d-block pb-4">
                        <button class="btn-back">{{ __('To go back') }}</button>
                        <button class="px-5 btn-continue">{{ __('Continue')}}</button>
                    </div>
                </div>
            </div>
            <!-- Step 4 -->
            <div class="step col-sm-10 row justify-content-between mt-4">
                <div class="d-flex align-items-center bg-top my-4 py-2 justify-content-between">
                    <h6 class="col-8 text-uppercase">{{ __('Method') }}</h6>
                    <h6 class="col-3 d-lg-block d-none text-uppercase">{{ __('Total') }}</h6>
                </div>
                <div class="pay-method d-flex gap-2 col-xl-7 p-0 mb-4 justify-content-between">
                    <div>
                        <input id="credit" type="radio" name="pay-method" value="1">
                        <label for="credit">
                            <i class="bi bi-bank"></i>
                            <p>Depósito bancario</p>
                        </label>
                    </div>
                    <div>
                        <input id="transfer" type="radio" name="pay-method" value="2">
                        <label for="transfer">
                            <i class="bi bi-credit-card"></i>
                            <p>Bancard</p>
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
                <div class="col-xl-5">
                    <div class="right-area">
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
                            <h4 class="title text-black">{{ __('Shipping method') }}</h4>
                            <div class="d-flex flex-wrap">
                                <p id="freteText2" style="font-size: 14px;" class="fw-semibold colo-1 pr-1 d-none">
                                    {{__('Pick up in')}}
                                </p>
                                <p id="freteText" style="font-size: 14px;" class="fw-semibold colo-1 pr-1">
                                    {{__('Pick up in')}}
                                </p>
                                <p style="font-size: 14px;" class="fw-semibold colo-1 m-0 d-none">CDE</p>
                            </div>
                            <p id="freteGratis" class="fw-bold color-4 border-bottom-f1 pb-3 mb-3 d-none  text-end">
                            </p>
                            <p id="frete10"
                                class="fw-bold color-4 border-bottom-f1 pb-3 mb-3 d-none text-danger text-end">
                                <b class="cart-total fw-bold">{{App\Models\Product::convertPrice(10)}}</b>
                            </p>
                            <div class="total-price d-flex justify-content-between">
                                <p style="margin-bottom:0px;">{{ __('Total') }}</p>
                                <p><span id="total-cost2">{{ App\Models\Product::signFirstPrice($totalPrice)
                                        }}</span></p>
                            </div>
                            <div class="d-flex">
                                <button class="btn-back d-xl-none d-block">{{ __('To go back') }}</button>
                                <button type="button" id="enviarFormulario"
                                    class="w-100 px-sm-4 btn-continue px-1">Finalizar compra</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="" style="bottom: 0;left: 0;">
                    <button class="btn-back px-5 mt-5 d-xl-block d-none">{{ __('To go back') }}</button>
                </div>
            </div>
        </div>
</form>
<script src="{{ asset('assets/checkout/scripts.js') }}"></script>

<x-loader />
<script>
    const myModalEl = document.getElementById('final-btn')
    myModalEl.addEventListener('click', event => {
        document.getElementsByClassName('loader-checkout')[0].classList.add('d-flex')
    })
    $(document).ready(function () {
        $('#iframe-modal').on('shown.bs.modal', function (e) {
            document.getElementsByClassName('loader-checkout')[0].classList.remove('d-flex')
        })
    });
</script>
@endsection


@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function () {
        $("#enviarFormulario").click(function () {
            // Serializa os dados do formulário
            var dadosFormularioArray = $("#myform").serializeArray();

            // Cria um objeto para armazenar os dados
            var dadosObjeto = {};

            // Preenche o objeto com os dados do array
            $.each(dadosFormularioArray, function (index, field) {
                dadosObjeto[field.name] = field.value;
            });

            dadosObjeto['name'] = "Matteo Carminato";
            dadosObjeto['customer_document'] = 123456789;
            dadosObjeto['birthday'] = '1995-06-18'; // Adicione a data de nascimento desejada
            dadosObjeto['customer_gender'] = 'M'; // Adicione o gênero desejado
            dadosObjeto['phone'] = 33333333333333;
            dadosObjeto['email'] = 'mcarminato95@gmail.com';
            dadosObjeto['shipping'] = 'shipto';
            dadosObjeto['pickup_location'] = 'SAX CIUDAD DEL ESTE|1';
            dadosObjeto['zip'] = "85856-550";
            dadosObjeto['address'] = "xxxxxxxxxxx";
            dadosObjeto['address_number'] = "123";
            dadosObjeto['complement'] = "";
            dadosObjeto['district'] = "";
            dadosObjeto['country'] = 173;
            dadosObjeto['state'] = 11;
            dadosObjeto['city'] = 27;
            dadosObjeto['diff_address'] = "";
            dadosObjeto['shipping_name'] = '';
            dadosObjeto['shipping_zip'] = '';
            dadosObjeto['shipping_phone'] = '';
            dadosObjeto['shipping_address'] = '';
            dadosObjeto['shipping_address_number'] = '';
            dadosObjeto['shipping_complement'] = '';
            dadosObjeto['shipping_district'] = '';
            dadosObjeto['shipping_country'] = '';
            dadosObjeto['shipping_state'] = '';
            dadosObjeto['shipping_city'] = '';
            dadosObjeto['order_note'] = "";
            dadosObjeto['zimple_phone'] = '';
            dadosObjeto['puntoentrega'] = '';
            dadosObjeto['puntoidvalue'] = '';
            dadosObjeto['aex_city'] = '0';
            dadosObjeto['shipping_cost'] = 3;
            dadosObjeto['packing_cost'] = 1;
            dadosObjeto['dp'] = '0';
            dadosObjeto['tax'] = '0';
            dadosObjeto['totalQty'] = '1';
            dadosObjeto['vendor_shipping_id'] = '0';
            dadosObjeto['vendor_packing_id'] = '0';
            dadosObjeto['total'] = '6657200';
            dadosObjeto['coupon_code'] = '';
            dadosObjeto['coupon_discount'] = '';
            dadosObjeto['coupon_id'] = '';
            dadosObjeto['user_id'] = '1329';

            var dadosJSON = JSON.stringify(dadosObjeto);

            console.log("CHEGUEEEI", dadosObjeto)

            $.ajax({
                method: "POST",
                url: "{{ route('submit-new-checkout') }}",
                data: dadosJSON,
                dataType: 'JSON',
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Adiciona o token CSRF aos cabeçalhos
                },
                cache: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    $('.alert-ajax').hide();
                    $('.alert-ajax').find('p').text('');
                    if (data.gateway === 'bancard') {
                        if (data.is_zimple) {
                            Bancard.Zimple.createForm('iframe-container', data.process_id);
                        } else {
                            Bancard.Checkout.createForm('iframe-container', data.process_id);
                        }
                        $("#iframe-modal").modal('show');
                    }
                },
                error: function (response) {
                    if (response.responseJSON.unsuccess) {
                        $('.alert-ajax').find('p').text(response.responseJSON.unsuccess);
                        $('.alert-ajax').show('fade');
                    }
                    console.log(response);
                },
                complete: function () {
                    $('#preloader').hide();
                }
            });
        });
    });

</script>
<script>
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: 'http://localhost:8000' + '/checkout/getStatesOptions',
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
                url: 'http://localhost:8000' + '/checkout/getCitiesOptions',
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
    window.onload = function () {
        document.getElementById("customer_name").value = document.getElementById("billName").value;
        document.getElementById("customer_phone").value = document.getElementById("billPhone").value;
    }

    var billName = document.getElementById("billName");

    billName.addEventListener("change", function () {
        document.getElementById("customer_name").value = billName.value;
    });

    var billPhone = document.getElementById("billPhone");

    billPhone.addEventListener("change", function () {
        document.getElementById("customer_phone").value = billPhone.value;
    });
</script>

<script>
    // Global variables
    var pos = '{{ $gs->currency_format }}';
    var dec_sep = '{{ $curr_checkout->decimal_separator }}';
    var tho_sep = '{{ $curr_checkout->thousands_separator }}';
    var dec_dig = '{{ $curr_checkout->decimal_digits }}';
    var dec_sep2 = '{{ $first_curr->decimal_separator }}';
    var tho_sep2 = '{{ $first_curr->thousands_separator }}';
    var dec_dig2 = '{{ $first_curr->decimal_digits }}';
    var diff_address = false;
    var checkout_url = '{{ route('front.checkout') }}';
    var fbPaymentInfoClick = false;
    var currency = '{{ $curr_checkout->name }}';
    var price = '{{ $totalPrice }}';
    $(document).ready(function () {
        if (typeof fbq != 'undefined') {
            fbq('track', 'InitiateCheckout', {
                value: price,
                currency: currency
            });
        }
    });

    $(".fbPaymentInfo").click(function () {
        if (!fbPaymentInfoClick) {
            setTimeout(function () {
                if ($("#pills-step2").hasClass("active")) {
                    if (typeof fbq != 'undefined') {
                        fbq('track', 'AddPaymentInfo');
                        fbPaymentInfoClick = true;
                    }
                }
            }, 1000);
        }
    });

    // Calculate Shipping and Package in frontend
    function calc_ship_pack() {
        var mship = $('.shipping').length > 0 ? $('.shipping:checked').map(function () {
            return $(this).data('price');
        }).get() : 0;
        var mpack = $('.packing').length > 0 ? $('.packing:checked').map(function () {
            return $(this).data('price');
        }).get() : 0;
        mship = parseFloat(mship);
        mpack = parseFloat(mpack);
        if (isNaN(mship)) {
            mship = 0;
        }
        if (isNaN(mpack)) {
            mpack = 0;
        }
        var shipid = $('.shipping').length > 0 ? $('.shipping:checked').map(function () {
            return $(this).data('id');
        }).get() : 0;
        var packid = $('.packing').length > 0 ? $('.packing:checked').map(function () {
            return $(this).data('id');
        }).get() : 0;
        $('#shipping-cost').val(shipid);
        $('#packing-cost').val(packid);
        $('#aex-city').val($('#aex_destination').val());

        var ftotal = parseFloat($('#grandtotal').val()) + mship + mpack;
        ftotal = parseFloat(ftotal);
        var curr_checkout_value = parseFloat('{{ $curr_checkout->value }}');
        var ftotal2 = ftotal / curr_checkout_value;

        // if (pos == 0) {
        //     $('#final-cost').html('{{ $curr_checkout->sign }}' + $.number(ftotal, dec_dig, dec_sep, tho_sep));
        //     $('#final-cost2').html('{{ $first_curr->sign }}' + $.number(ftotal2, dec_dig2, dec_sep2, tho_sep2));
        // } else {
        //     $('#final-cost').html($.number(ftotal, dec_dig, dec_sep, tho_sep) + '{{ $curr_checkout->sign }}');
        //     $('#final-cost2').html($.number(ftotal2, dec_dig2, dec_sep2, tho_sep2) + '{{ $first_curr->sign }}');
        // }
    }
    // End Calculate Shipping and Package in frontend
</script>
<script>
    function gerarPonto(id) {
        var pontoselecionado = document.querySelector('input[name="puntoentrega"]:checked').value;
        document.getElementById("punto-selected").value = pontoselecionado;

        document.getElementById("punto-id").value = id;
    }

    function excluirPonto() {
        var envioStandard = document.querySelector('input[data-itemtype="standard"]:checked');
        if (envioStandard) {
            var pontos = document.getElementsByName("puntoentrega");
            for (var i = 0; i < pontos.length; i++) {
                pontos[i].checked = false;
            }
            document.getElementById("punto-selected").value = null;
            document.getElementById("punto-id").value = null;
        }
    }

    // Create Account checkbox
    $("#open-pass").on("change", function () {
        if (this.checked) {
            $('.set-account-pass').removeClass('d-none');
            $('.set-account-pass input').prop('required', true);
            $('#personal-email').prop('required', true);
            $('#personal-name').prop('required', true);
        } else {
            $('.set-account-pass').addClass('d-none');
            $('.set-account-pass input').prop('required', false);
            $('#personal-email').prop('required', false);
            $('#personal-name').prop('required', false);
        }
    });
    // End Create Account checkbox

    // Pickup and Address shipping select
    $('#shipop').on('change', function () {
        var val = $(this).val();
        if (val == 'pickup') {
            $('#shipshow').removeClass('d-none');
            $("#ship-diff-address").parent().addClass('d-none');
            $("#ship-diff-address").removeAttr('checked');
            $('.ship-diff-addres-area').addClass('d-none');
            $('.ship-diff-addres-area input, .ship-diff-addres-area select').prop('required', false);
            $('#empty-ship').addClass('d-none');
            $('#pickup-ship').removeClass('d-none');
            $('.normal-sheep').remove();
            $('.PAC-sheep').remove();
            $('.SEDEX-sheep').remove();
            $('.aex-sheep').remove();
            $('#aex-box').addClass('d-none');
            calc_ship_pack();
        } else {
            $('#shipshow').addClass('d-none');
            $("#ship-diff-address").parent().removeClass('d-none');
            $('#empty-ship').removeClass('d-none');
            $('#pickup-ship').addClass('d-none');
            $('#billCity').trigger('change');
            $('#aex-box').removeClass('d-none');
        }
    });
    // End Pickup and Address shipping select

    // Shipping Address Checking
    $("#ship-diff-address").on("change", function () {
        if (this.checked) {
            diff_address = true;
            $('#shippingCity').trigger('change');
            $('.ship-diff-addres-area').removeClass('d-none');
            $('.ship-diff-addres-area input, .ship-diff-addres-area select').prop('required', true);
        } else {
            diff_address = false;
            $('#billCity').trigger('change');
            $('.ship-diff-addres-area').addClass('d-none');
            $('.ship-diff-addres-area input, .ship-diff-addres-area select').prop('required', false);
        }
    });
    // End Shipping Address Checking

    // Resets country selection based on logged user and session available
    @if (!Auth:: check() && !Session:: has('session_order'))
    $('.js-country').val('');
    @endif

    // Reload the page to work with the ajax if there was a session available
    if ($('#has_temporder').val() === 'true') {
        $('#has_temporder').val('false');
        window.location = checkout_url;
    }


    // Calculate initial prices with first shipping upon loading
    calc_ship_pack();

    // Calculate new prices when clicking the packages available
    $('.packing').on('click', function () {
        calc_ship_pack();
    });

    // Calculate Coupon Discounts if applied
    $('#check-coupon-form').on('submit', function (e) {
        $('#preloader_checkout').show();
        e.preventDefault();
        var val = $('#code').val();
        var total = $('#grandtotal').val();
        var ship = 0;
        $.ajax({
            type: 'GET',
            url: mainurl + '/carts/coupon/check',
            data: {
                code: val,
                total: total,
                shipping_cost: ship
            },
            success: function (data) {
                //Coupon not found
                if (data.not_found) {
                    toastr.error(data['not_found']);
                    $('#code').val('');
                    return;
                }

                //Coupon already applied
                if (data.already) {
                    toastr.error(data['already']);
                    $('#code').val('');
                    return;
                }

                // Display Discount applied
                $('#check-coupon-form').toggle();
                $('.discount-bar').removeClass('d-none');

                // In the following, data is an array with the representation:
                // data[0] = cart total price in store currency
                // data[1] = the coupon code
                // data[2] = the coupon value or percentage
                // data[3] = the coupon ID
                // data[4] = 0 if coupon is a value; the coupon price in percentage
                // data[5] = 1
                // data[6] = cart total price in currency 1

                if (pos == 0) {
                    $('#total-cost').html('{{ $curr_checkout->sign }}' + $.number(data[0],
                        dec_dig,
                        dec_sep, tho_sep));
                    $('#total-cost2').html('{{ $first_curr->sign }}' + $.number(data[6],
                        dec_dig2, dec_sep2, tho_sep2));
                    $('#discount').html('{{ $curr_checkout->sign }}' + $.number(data[2], dec_dig,
                        dec_sep, tho_sep));
                } else {
                    $('#total-cost').html($.number(data[0], dec_dig, dec_sep, tho_sep) +
                        '{{ $curr_checkout->sign }}');
                    $('#total-cost2').html($.number(data[6], dec_dig2, dec_sep2, tho_sep2) +
                        '{{ $first_curr->sign }}');
                    $('#discount').html($.number(data[2], dec_dig, dec_sep, tho_sep) +
                        '{{ $curr_checkout->sign }}');
                }

                $('#grandtotal').val(data[0]);
                $('#tgrandtotal').val(data[0]);
                $('#coupon_code').val(data[1]);
                $('#coupon_discount').val(data[2]);

                if (data[4] != 0) {
                    $('.dpercent').html('(' + data[4] + ')');
                } else {
                    $('.dpercent').html('');
                }

                toastr.success(data['success']);
                $('#code').val('');
            },
            error: function (err) {
                console.log(err);
            },
            complete: function () {
                calc_ship_pack();
                $('#preloader_checkout').hide();
            }
        })
    });

    // Search address by zipcode
    $('.js-zipcode').on('change', function () {
        var address_field = 'billAddress';
        var district_field = 'billDistrict';
        var select_country = 'billCountry';

        if ($(this).data('type') == 'shipping') {
            address_field = 'shippingAddress';
            district_field = 'shippingDistrict';
            select_country = 'shippingCountry';
        }

        $('#preloader_checkout').show();
        $('#checkoutZipError').hide();
        $.ajax({
            type: 'GET',
            url: mainurl + '/checkout/cep',
            data: {
                cep: $(this).val()
            },
            success: function (data) {
                // Invalid zipcode
                if (data.error) {
                    $('#checkoutZipError').show();
                    $('#preloader_checkout').hide();
                    return;
                }

                // Fill address inputs
                $('#' + address_field).val(data['street']);
                $('#' + district_field).val(data['district']);

                //Select country based on the zipcode, passing the zipdata.
                // Then the selects are triggered in sequence, checking the zipdata
                // in each trigger
                $('#' + select_country).val(data['country_id']).trigger('change', data);

            },
            error: function (err) {
                console.log(err);
                $('#preloader_checkout').hide();
            }
        })
    });

    //Change AEX Option
    $('#aex_destination').on('change', function (e) {
        $('#preloader_checkout').show();
        $('.js-city').trigger('change');
    });
</script>

{{-- If user is authenticated, bypass zipcode checking and just trigger the selects --}}
@if (Auth::check())
<script>
    var user_zipdata = {
        city: '{{ Auth::user()->city ?? '' }}',
        city_id: {{ Auth:: user() -> city_id ?? 0 }},
    country_id: { { Auth:: user() -> country_id ?? 0 } },
    state_id: { { Auth:: user() -> state_id ?? 0 } },
    state_name: '{{ Auth::user()->state ?? '' }}',
        uf: '{{ Auth::user()->state->initial ?? '' }}',
            zipcode: '{{ Auth::user()->zip ?? '' }}'
            };
    $('#billCountry').trigger('change', user_zipdata);
</script>
@endif

{{-- If session is available, bypass zipcode checking and just trigger the selects --}}
@if (Session::has('session_order'))
<script>
    $('#has_temporder').val('true');
    var session_zipdata = {
        city: '{{ session()->get('session_order')['customer_city'] ?? '' }}',
        city_id: {{ session() -> get('session_order')['customer_city_id'] ?? 0 }},
    country_id: { { session() -> get('session_order')['customer_country_id'] ?? 0 } },
    state_id: { { session() -> get('session_order')['customer_state_id'] ?? 0 } },
    state_name: '{{ session()->get('session_order')['customer_state'] ?? '' }}',
        uf: '{{ session()->get('session_order')['customer_state_initials'] ?? '' }}',
            zipcode: '{{ session()->get('session_order')['customer_zip'] ?? '' }}'
            };
    $('#billCountry').trigger('change', session_zipdata);
</script>
@endif


<script>
  // Default checkout scripts. Just extracted to its own file

  if ($("#v-pills-tab13-tab").hasClass("active")) {
    $('#teste').removeClass('mfp-hide');
  } else {
    $('#teste').addClass('mfp-hide');
  }
  $('#v-pills-tab13-tab').on('click', function () {
    $('#teste').removeClass('mfp-hide');
  })

  $('a.payment:first').addClass('active');
  $('.checkoutform').prop('action', $('a.payment:first').data('form'));
  $($('a.payment:first').attr('href')).load($('a.payment:first').data('href'));
  var show = $('a.payment:first').data('show');
  if (show != 'no') {
    $('.pay-area').removeClass('d-none');
  } else {
    $('.pay-area').addClass('d-none');
  }
  $($('a.payment:first').attr('href')).addClass('active').addClass('show');

  @if (isset($checked))
    $('#comment-log-reg1').modal('show');
  @endif

  var ck = 0;
  $('.checkoutform').on('submit', function (e) {
    if (ck == 0) {
      e.preventDefault();
      $('#buttons2').removeClass('none');
      $('#buttons1').addClass('none');
      $('#pills-step2-tab').removeClass('disabled');
      $('#pills-step2-tab').click();
    } else {
      $('#preloader').show();
    }
    $('#pills-step1-tab').addClass('active');
  });
  $('#step1-btn').on('click', function () {
    // Redirects to checkout if session is available, to restart the process
    if ($('#has_temporder').val() === 'true') {
      window.location = checkout_url;
      return;
    }

    $('#pills-step1-tab').removeClass('active');
    $('#pills-step2-tab').removeClass('active');
    $('#pills-step3-tab').removeClass('active');
    $('#pills-step2-tab').addClass('disabled');
    $('#pills-step3-tab').addClass('disabled');
    $('#pills-step1-tab').click();
  });

  function back1() {
    $('#buttons1').removeClass('none');
    $('#buttons2').addClass('none');
    $('#buttons3').addClass('none');
  }

  function back2() {
    $('#buttons2').removeClass('none');
    $('#buttons3').addClass('none');
    $('#buttons1').addClass('none');
    $('#buttons3').find('button').prop('disabled', false)
  }

  function scrolltotop() {
    if ($(window).width() < 768) {
      $('html, body').animate({ scrollTop: $('.breadcrumb-area').offset().top }, "slow");
    }
  }

  function continue2() {
    $('#buttons3').removeClass('none');
    $('#buttons2').addClass('none');
    $('#buttons1').addClass('none');
  }

  function disableButton() {
    $('#buttons3').find('button').prop('disabled', true)
  }
  // Step 2 btn DONE
  $('#step2-btn').on('click', function () {
    $('#pills-step3-tab').removeClass('active');
    $('#pills-step1-tab').removeClass('active');
    $('#pills-step2-tab').removeClass('active');
    $('#pills-step3-tab').addClass('disabled');
    $('#pills-step2-tab').click();
    $('#pills-step1-tab').addClass('active');
    $('#final-btn').prop('id', 'myform');
    $('#step3-btn').prop('id', 'myform');
    $('.checkoutform').prop('id', 'myform');
  });
  $('#step3-btn').on('click', function () {
    $('.checkoutform').prop('id', 'myform');
    if ($('a.payment:first').data('val') == 'bancard') {
      $('#final-btn').prop('id', 'bancard-form');
      $('.checkoutform').prop('id', 'bancard-form');
    }
    if ($('a.payment:first').data('val') == 'pagarme') {
      $('#step3-btn').prop('id', 'pagarme-form');
      $('.checkoutform').prop('id', 'pagarme-form');
    }
    if ($('a.payment:first').data('val') == 'paystack') {
      $('#final-btn').prop('id', 'pagarme-form');
      $('.checkoutform').prop('id', 'step1-form');
    }
    $('#pills-step3-tab').removeClass('disabled');
    $('#pills-step3-tab').click();
    var shipping_user = !$('input[name="shipping_name"]').val() ? $('input[name="name"]').val() : $(
      'input[name="shipping_name"]').val();
    var shipping_location = !$('input[name="shipping_address"]').val() ? $('input[name="address"]').val() :
      $('input[name="shipping_address"]').val();
    var shipping_number = !$('input[name="shipping_address_number"]').val() ? $(
      'input[name="address_number"]').val() : $('input[name="shipping_address_number"]').val();
    var shipping_zip = !$('input[name="shipping_zip"]').val() ? $('input[name="zip"]').val() : $(
      'input[name="shipping_zip"]').val();
    var shipping_phone = !$('input[name="shipping_phone"]').val() ? $('input[name="phone"]').val() : $(
      'input[name="shipping_phone"]').val();
    $('#final_shipping_user').html('<i class="fas fa-user"></i>' + shipping_user);
    $('#final_shipping_location').html('<i class="fas fa-map-marked-alt"></i>' + shipping_location + ', ' +
      shipping_number);
    $('#final_shipping_zip').html('<i class="fas fa-map-marker-alt"></i>' + shipping_zip);
    $('#final_shipping_phone').html('<i class="fas fa-phone"></i>' + shipping_phone);
    $('#pills-step1-tab').addClass('active');
    $('#pills-step2-tab').addClass('active');
  });
  $('#final-btn').on('click', function () {
    ck = 1;
    $('.checkoutform').submit();
  })
  $('.payment').on('click', function () {
    $('.checkoutform').prop('id', 'myform');
    if ($(this).data('val') == 'bancard') {
      $('#final-btn').prop('id', 'bancard-form');
      $('.checkoutform').prop('id', 'bancard-form');
    }
    if ($(this).data('val') == 'pagarme') {
      $('#final-btn').prop('id', 'pagarme-form');
      $('.checkoutform').prop('id', 'pagarme-form');
    }

    if ($(this).data('val') == 'paystack') {
      $('#final-btn').prop('id', 'pagarme-form');
      $('.checkoutform').prop('id', 'step1-form');
    }
    if ($(this).data('val') == 'bankDeposit') {
      $('#teste').removeClass('mfp-hide');
    } else {
      $('#teste').addClass('mfp-hide');
    }
    $('.checkoutform').prop('action', $(this).data('form'));
    $('.pay-area #v-pills-tabContent .tab-pane.fade').not($(this).attr('href')).html('');
    var show = $(this).data('show');
    if (show != 'no') {
      $('.pay-area').removeClass('d-none');
    } else {
      $('.pay-area').addClass('d-none');
    }
    $($(this).attr('href')).load($(this).data('href'));
  });


  $(document).on('submit', '#checkoutform', function (e) {
    e.preventDefault();
    console.log("TESTEEEEEEEE CHECKOUT")
    $.ajax({
      method: "POST",
      url: $(this).prop('action'),
      data: new FormData(this),
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        console.log(data);
        $('.alert-ajax').hide();
        $('.alert-ajax').find('p').text('');
        if (data.gateway === 'bancard') {
          if (data.is_zimple) {
            Bancard.Zimple.createForm('iframe-container', data.process_id);
          } else {
            Bancard.Checkout.createForm('iframe-container', data.process_id);
          }
          $("#iframe-modal").modal('show');
        }
      },
      error: function (response) {
        if (response.responseJSON.unsuccess) {
          $('.alert-ajax').find('p').text(response.responseJSON.unsuccess);
          $('.alert-ajax').show('fade');
        }
        console.log(response);
      },
      complete: function () {
        $('#preloader').hide();
      }
    });
  });
  /
  $(document).on('submit', '#step1-form', function () {
    $('#preloader').hide();
    var val = $('#sub').val();
    var total = $('#grandtotal').val();
    total = Math.round(total);
    if (val == 0) {
      var handler = PaystackPop.setup({
        key: '{{$gs->paystack_key}}',
        email: $('input[name=email]').val(),
        amount: total * 100,
        currency: "{{$curr_checkout->name}}",
        ref: '' + Math.floor((Math.random() * 1000000000) + 1),
        callback: function (response) {
          $('#ref_id').val(response.reference);
          $('#sub').val('1');
          $('#final-btn').click();
        },
        onClose: function () {
          window.location.reload();
        }
      });
      handler.openIframe();
      return false;
    } else {
      $('#preloader').show();
      return true;
    }
  });

</script>

@endsection