@if (Session::get('cookie_alert') == null)
    <section class="cookie-alert" style="z-index: 2">
        <div class="row">
            <div class="col-md-6 col-sm-12 col-lg-6 col-xs-12 col-xl-4 button-fixed">
                <div class="p-3 pb-4 bg-custom text-white">
                    <div class="row">
                        <div class="col-10">
                            <h4>Cookies</h4>
                        </div>
                        <div class="col-2 text-center">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                    <p>{{ __('We use cookies to improve your experience on the website. By continuing to browse, you agree with our') }}
                        <a target="_blank" href="{{ route('front.privacypolicy') }}">{{ __('Privacy Policy') }}</a>.
                    <a class="accept-cookies-link" data-href="{{ route('front.acceptcookies') }}">
                        <button type="button" class="btn w-100 btn-accept-cookies">Concordar</button>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endif
