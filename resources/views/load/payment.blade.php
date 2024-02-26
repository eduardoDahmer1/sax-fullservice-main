@if ($payment == 'bancard')
    @php
        $bancard_api = $gs->bancard_mode == 'sandbox' ? 'https://vpos.infonet.com.py:8888' : 'https://vpos.infonet.com.py';
    @endphp
    <script src="{{ $bancard_api }}/checkout/javascript/dist/bancard-checkout-3.0.0.js"></script>
    <div class="col-lg-12 d-none">
        <p>{{ __('If you want to pay using Zimple, please type your phone number') }}.</p>
        <p>{{ __('Please remember this is not required. If this field is empty, payment will proceed with Bancard normally') }}.
        </p>
        <label>{{ __('Zimple Phone#') }} *</label>
        <input class="form-control" name="zimple_phone" type="text" />
    </div>
@endif
@if ($payment == 'cod')
    <input type="hidden" name="method" value="Cash On Delivery">
@endif
@if ($payment == 'pagarme')
    <script src="https://assets.pagar.me/checkout/1.1.0/checkout.js"></script>
    <p>{{ __('Please check if your phone number is in the international format, with the country code, before continuing. Eg. +5545999999999') }}
    </p>
@endif
@if ($payment == 'paypal')
    <input type="hidden" name="method" value="Paypal">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="lc" value="UK">
    <input type="hidden" name="currency_code" value="{{ $curr->name }}">
    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest">
@endif

@if ($payment == 'stripe')
    <input type="hidden" name="method" value="Stripe">
    <div class="row">
        <div class="col-lg-6">
            <input class="form-control card-elements" name="cardNumber" type="text"
                placeholder="{{ __('Card Number') }}" autocomplete="off" autofocus
                oninput="validateCard(this.value);" />
            <span id="errCard"></span>
        </div>
        <div class="col-lg-6">
            <input class="form-control card-elements" name="cardCVC" type="text" placeholder="{{ __('Cvv') }}"
                autocomplete="off" oninput="validateCVC(this.value);" />
            <span id="errCVC"></span>
        </div>
        <div class="col-lg-6">
            <input class="form-control card-elements" name="month" type="text" placeholder="{{ __('Month') }}" />
        </div>
        <div class="col-lg-6">
            <input class="form-control card-elements" name="year" type="text" placeholder="{{ __('Year') }}" />
        </div>
    </div>


    <script type="text/javascript" src="{{ asset('assets/front/js/payvalid.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/front/js/paymin.js') }}"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript" src="{{ asset('assets/front/js/payform.js') }}"></script>


    <script type="text/javascript">
        var cnstatus = false;
        var dateStatus = false;
        var cvcStatus = false;

        function validateCard(cn) {
            cnstatus = Stripe.card.validateCardNumber(cn);
            if (!cnstatus) {
                $("#errCard").html("{{ __('Card number not valid') }}");
            } else {
                $("#errCard").html('');
            }



        }

        function validateCVC(cvc) {
            cvcStatus = Stripe.card.validateCVC(cvc);
            if (!cvcStatus) {
                $("#errCVC").html('{{ __('CVC number not valid') }}');
            } else {
                $("#errCVC").html('');
            }

        }
    </script>
@endif


@if ($payment == 'instamojo')
    <input type="hidden" name="method" value="Instamojo">
@endif


@if ($payment == 'paystack')
    <input type="hidden" name="ref_id" id="ref_id" value="">
    <input type="hidden" name="sub" id="sub" value="0">
    <input type="hidden" name="method" value="Paystack">
@endif

@if ($payment == 'razorpay')
    <input type="hidden" name="method" value="Razorpay">
@endif

@if ($payment == 'molly')
    <input type="hidden" name="method" value="Molly">
@endif


@if ($payment == 'other')
    <input type="hidden" name="method" value="{{ $gateway->title }}">

    <div class="row">

        <div class="col-lg-12 pb-2">

            {!! $gateway->details !!}

        </div>


        <div class="col-lg-6">
            <label>{{ __('Transaction ID#') }} *</label>
            <input class="form-control" name="txn_id4" type="text" placeholder="{{ __('Transaction ID#') }}" />
        </div>


    </div>
@endif
