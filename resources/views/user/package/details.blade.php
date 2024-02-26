@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')

    <section class="user-dashbord">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="user-profile-details">

                        <div class="account-info">
                            <div class="header-area">
                                <h4 class="title">
                                    {{ __('Plan Details') }} <a class="mybtn1" href="{{ route('user-package') }}"> <i
                                            class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
                                </h4>
                            </div>
                            <div class="pack-details">
                                <div class="row">

                                    <div class="col-lg-4">
                                        <h5 class="title">
                                            {{ __('Plan:') }}
                                        </h5>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="value">
                                            {{ $subs->title }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h5 class="title">
                                            {{ __('Price:') }}
                                        </h5>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="value">
                                            {{ $curr->sign }} {{ $subs->price }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h5 class="title">
                                            {{ __('Durations:') }}
                                        </h5>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="value">
                                            {{ $subs->days }} {{ __('Day(s)') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h5 class="title">
                                            {{ __('Product(s) Allowed:') }}
                                        </h5>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="value">
                                            {{ $subs->allowed_products == 0 ? __('Unlimited') : $subs->allowed_products }}
                                        </p>
                                    </div>
                                </div>

                                @if (!empty($package))
                                    @if ($package->subscription_id != $subs->id)
                                        <div class="row">
                                            <div class="col-lg-4">
                                            </div>
                                            <div class="col-lg-8">
                                                <span class="notic"><b>{{ __('Note:') }}</b>
                                                    {{ __('Your Previous Plan will be deactivated!') }}</span>
                                            </div>
                                        </div>

                                        <br>
                                    @else
                                        <br>
                                    @endif
                                @else
                                    <br>
                                @endif

                                <form id="subscribe-form" class="pay-form"
                                    action="{{ route('user-vendor-request-submit') }}" method="POST">

                                    @include('includes.form-success')
                                    @include('includes.form-error')
                                    @include('includes.admin.form-error')

                                    {{ csrf_field() }}

                                    @if ($user->is_vendor == 0)
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ __('Shop Name') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="input-field" name="shop_name"
                                                    placeholder="{{ __('Shop Name') }}" required="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ __('Corporate Name') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="input-field" name="vendor_corporate_name"
                                                    placeholder="{{ __('Corporate Name') }}" required="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ __('Phone') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="input-field" name="vendor_phone"
                                                    placeholder="{{ __('Phone') }}" required="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ __('Opening Hours') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="input-field" name="vendor_opening_hours"
                                                    placeholder="{{ __('Opening Hours') }}" required="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ __('Payment Methods') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="input-field" name="vendor_payment_methods"
                                                    placeholder="{{ __('Payment Methods') }}" required="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ __('Delivery Info') }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="input-field" name="vendor_delivery_info"
                                                    placeholder="{{ __('Delivery Info') }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ __('Registration Number') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="input-field" name="reg_number"
                                                    placeholder="{{ __('Registration Number') }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ __('Owner Name') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="input-field" name="owner_name"
                                                    placeholder="{{ __('Owner Name') }}" required="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ __('Shop Number') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="input-field" name="shop_number"
                                                    placeholder="{{ __('Shop Number') }}" required="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ __('Shop Address') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="input-field" name="shop_address"
                                                    placeholder="{{ __('Shop Address') }}" required="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ __('Embed Google Maps') }}
                                                        <small>{{ __('(Optional)') }}</small></h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="input-field" name="vendor_map_embed"
                                                    placeholder="{{ __('Paste Google Maps Embed here') }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ __('Shop Details') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <textarea class="input-field trumboedit" name="shop_details" placeholder="{{ __('Shop Details') }}"></textarea>
                                            </div>
                                        </div>

                                        <br>

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">
                                                        {{ __('Message') }}<small>{{ __('(Optional)') }}</small></h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <textarea class="input-field trumboedit" name="shop_message" placeholder="{{ __('Shop Message') }}"></textarea>
                                            </div>
                                        </div>

                                        <br>
                                    @endif
                                    <input type="hidden" name="subs_id" value="{{ $subs->id }}">

                                    <div class="row">
                                        <div class="col-lg-4">
                                        </div>
                                        <div class="col-lg-8">
                                            <button type="submit" id="final-btn"
                                                class="mybtn1">{{ __('Submit') }}</button>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).on('submit', '#subscribe-form', function() {
            $('#preloader').show();
        });
    </script>
@endsection
