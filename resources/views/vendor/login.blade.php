@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')

@section('content')
    <section class="login-signup">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-reg" role="tabpanel" aria-labelledby="nav-reg-tab">
                            <div class="login-area signup-area">
                                <div class="header-area">
                                    <h4 class="title">{{ __('Signup Now') }}</h4>
                                </div>
                                <div class="login-form signup-form">
                                    @include('includes.admin.form-login')
                                    <form class="vendor-registerform" action="{{ route('vendor.register-submit') }}"
                                        method="POST" name="register_form">
                                        {{ csrf_field() }}

                                        <div class="form-input">
                                            <input type="text" class="User Name" name="name"
                                                placeholder="{{ __('Full Name') }}" required="" pattern="^(\S*)\s+(.*)$">
                                            <i class="icofont-user-alt-5"></i>
                                        </div>

                                        <div class="form-input">
                                            <input type="text" class="CNPJ" name="vendor_document"
                                                placeholder="{{ __('CNPJ') }}" maxlength="18" required=""
                                                onKeyPress="MascaraCNPJ(register_form.vendor_document);">
                                            <i class="icofont-building"></i>
                                        </div>

                                        <div class="form-input">
                                            <input type="email" class="User Name" name="email"
                                                placeholder="{{ __('Email Address') }}" required="">
                                            <i class="icofont-email"></i>
                                        </div>

                                        <div class="form-input">
                                            <input type="password" id="register_password" class="Password" name="password"
                                                placeholder="{{ __('Password') }}" required="">
                                            <span toggle="#register_password"
                                                class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            <i class="icofont-ui-password"></i>
                                        </div>

                                        <div class="form-input">
                                            <input type="password" id="confirmation_password" class="Password"
                                                name="password_confirmation" placeholder="{{ __('Confirm Password') }}"
                                                required="">
                                            <span toggle="#confirmation_password"
                                                class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            <i class="icofont-ui-password"></i>
                                        </div>

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
                                                    placeholder="{{ __('Enter Code') }}" required="">
                                                <i class="icofont-refresh"></i>
                                            </div>
                                        @endif
                                        @php
                                            $url = $gs->privacy_policy ? true : false;
                                            $url_terms = $gs->vendor_policy ? true : false;
                                        @endphp
                                        <div class="form-forgot-pass">
                                            <div class="left">
                                                <input type="checkbox" name="agree_privacy_policy"
                                                    id="agree_privacy_policy">
                                                <label for="agree_privacy_policy">{{ __("I agree with the") }} <a target="_blank"
                                                        href="{{ $url ? route('front.privacypolicy') : '' }}"> {{ __("Privacy Policy") }}</a> {{ __("And the") }}<a target="_blank"
                                                        href="{{ $url_terms ? route('front.vendorpolicy') : '' }}"> {{ __("Terms of use") }}</a>.</label>
                                            </div>
                                        </div>

                                        <input class="mprocessdata" type="hidden" value="{{ __('Processing...') }}">
                                        <button type="submit" class="submit-btn">{{ __('Register') }}</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $('.toggle-password').click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        function MascaraCNPJ(cnpj) {
            if (mascaraInteiro(cnpj) == false) {
                event.returnValue = false;
            }
            return formataCampo(cnpj, '00.000.000/0000-00', event);
        }
        //formata de forma generica os campos
        function formataCampo(campo, Mascara, evento) {
            var boleanoMascara;

            var Digitato = evento.keyCode;
            exp = /\-|\.|\/|\(|\)| /g
            campoSoNumeros = campo.value.toString().replace(exp, "");

            var posicaoCampo = 0;
            var NovoValorCampo = "";
            var TamanhoMascara = campoSoNumeros.length;;

            if (Digitato != 8) { // backspace
                for (i = 0; i <= TamanhoMascara; i++) {
                    boleanoMascara = ((Mascara.charAt(i) == "-") || (Mascara.charAt(i) == ".") || (Mascara.charAt(i) ==
                        "/"))
                    boleanoMascara = boleanoMascara || ((Mascara.charAt(i) == "(") || (Mascara.charAt(i) == ")") || (Mascara
                        .charAt(i) == " "))
                    if (boleanoMascara) {
                        NovoValorCampo += Mascara.charAt(i);
                        TamanhoMascara++;
                    } else {
                        NovoValorCampo += campoSoNumeros.charAt(posicaoCampo);
                        posicaoCampo++;
                    }
                }
                campo.value = NovoValorCampo;
                return true;
            } else {
                return true;
            }
        }
        //valida numero inteiro com mascara
        function mascaraInteiro() {
            if (event.keyCode < 48 || event.keyCode > 57) {
                event.returnValue = false;
                return false;
            }
            return true;
        }
    </script>
@endsection
