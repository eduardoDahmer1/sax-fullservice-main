@php
$url = $gs->privacy_policy ? true : false;
$url_terms = $gs->crow_policy ? true : false;
@endphp
<form class="form-group" action="{{ route('product.backinstock', $productt->id) }}" method="POST">
    @csrf
    <label style="font-weight:200;">{{ __('Please enter your e-mail to be notified when
        the product is back in stock.') }}</label>
    <label for="email">{{ __("Email Address") }}</label>
    <input style="margin-bottom: 1rem;" class="form-control form-backinstock" id="email" type="email" name="email"
        value="{{ old('email') }}" required>
    <input style="float: right;" type="submit" class="btn btn-primary btn-backinstock" value="{{ __('Notify Me') }}">
    <div class="form-forgot-pass">
        <div class="left">
            <input type="checkbox" name="agree_privacy_policy" id="agree_privacy_policy">
            <label for="agree_privacy_policy">{{ __("I agree with the") }} <a target="_blank"
                    href="{{ $url ? route('front.privacypolicy') : ''  }}"> {{ __("Privacy Policy") }}</a> {{ __("And the") }}<a target="_blank"
                    href="{{ $url_terms ? route('front.crowpolicy') : ''  }}"> {{ __("Terms of use") }}</a>.</label>
        </div>
    </div>
</form>
