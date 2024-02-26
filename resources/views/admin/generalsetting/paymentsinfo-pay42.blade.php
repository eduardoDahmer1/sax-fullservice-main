@extends('layouts.admin')

@section('styles')
    <style type="text/css">
        .img-upload #image-preview {
            background-size: unset !important;
        }

        .mr-breadcrumb .links .action-list li {
            display: block;
        }

        .mr-breadcrumb .links .action-list ul {
            overflow-y: auto;
            max-height: 240px;
        }

        .mr-breadcrumb .links .action-list .go-dropdown-toggle {
            padding-left: 20px;
            padding-right: 30px;
        }
    </style>
@endsection

@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Gateways') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-payments-index') }}">{{ __('Payment Methods') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-payments-gateway') }}">{{ __('Gateways') }}</a>
                        </li>
                        @if (config('features.multistore'))
                            <li>
                                <div class="action-list godropdown">
                                    <select id="store_filter" class="process select go-dropdown-toggle">
                                        @foreach ($stores as $store)
                                            <option
                                                value="{{ route('admin-stores-isconfig', ['id' => $store['id'], 'redirect' => true]) }}"
                                                {{ $store['id'] == $admstore->id ? 'selected' : '' }}>{{ $store['domain'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        @include('includes.admin.partials.gateway-tabs')
        <div class="add-product-content so`al-links-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $admstore->adminLoaderUrl }})
                                no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    @include('includes.admin.partials.gateway-menu')
                                </div>
                                <div class="col-lg-9">
                                    <form action="{{ route('admin-gs-update-payment') }}" id="geniusform" method="POST"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}

                                        @include('includes.admin.form-both')

                                        <div class="row">

                                            <div class="col-xl-3">
                                                <div class="input-form input-form-center">
                                                    <h4 class="heading">
                                                        {{ __('Pay42 Pix') }}
                                                    </h4>
                                                    <div class="action-list">
                                                        <select
                                                            class="process select droplinks {{ $admstore->is_pay42_pix == 1 ? 'drop-success' : 'drop-danger' }}">
                                                            <option data-val="1"
                                                                value="{{ route('admin-gs-pay42-pix', 1) }}"
                                                                {{ $admstore->is_pay42_pix == 1 ? 'selected' : '' }}>
                                                                {{ __('Activated') }}
                                                            </option>
                                                            <option data-val="0"
                                                                value="{{ route('admin-gs-pay42-pix', 0) }}"
                                                                {{ $admstore->is_pay42_pix == 0 ? 'selected' : '' }}>
                                                                {{ __('Deactivated') }}</option>
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="input-form input-form-center">
                                                    <h4 class="heading">
                                                        {{ __('Sandbox') }}
                                                        <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="{{ __('Enable the sandbox mode to make payment tests only, it will not work for real purchases.') }}"></i></span>
                                                    </h4>
                                                    <div class="action-list">
                                                        <select
                                                            class="process select droplinks {{ $admstore->is_pay42_sandbox == 1 ? 'drop-success' : 'drop-danger' }}">
                                                            <option data-val="1"
                                                                value="{{ route('admin-gs-pay42-sandbox', 1) }}"
                                                                {{ $admstore->is_pay42_sandbox == 1 ? 'selected' : '' }}>
                                                                {{ __('Activated') }}</option>
                                                            <option data-val="0"
                                                                value="{{ route('admin-gs-pay42-sandbox', 0) }}"
                                                                {{ $admstore->is_pay42_sandbox == 0 ? 'selected' : '' }}>
                                                                {{ __('Deactivated') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="input-form input-form-center">
                                                    <h4 class="heading">
                                                        {{ __('Pay42 currency') }}
                                                    </h4>
                                                    <div class="action-list">
                                                        <select class="process select droplinks drop-success">
                                                            <option data-val="BRL"
                                                                value="{{ route('admin-gs-pay42-currency', 'BRL') }}"
                                                                {{ $admstore->pay42_currency == 'BRL' ? 'selected' : '' }}>
                                                                {{ __('BRL') }}
                                                            </option>
                                                            <option data-val="USD"
                                                                value="{{ route('admin-gs-pay42-currency', 'USD') }}"
                                                                {{ $admstore->pay42_currency == 'USD' ? 'selected' : '' }}>
                                                                {{ __('USD') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="input-form input-form-center">
                                                    <h4 class="heading">
                                                        {{ __('Billet') }}
                                                    </h4>
                                                    <div class="action-list">
                                                        <select
                                                            class="process select droplinks {{ $admstore->is_pay42_billet == 1 ? 'drop-success' : 'drop-danger' }}">
                                                            <option data-val="1"
                                                                value="{{ route('admin-gs-pay42-billet', 1) }}"
                                                                {{ $admstore->is_pay42_billet == 1 ? 'selected' : '' }}>
                                                                {{ __('Activated') }}</option>
                                                            <option data-val="0"
                                                                value="{{ route('admin-gs-pay42-billet', 0) }}"
                                                                {{ $admstore->is_pay42_billet == 0 ? 'selected' : '' }}>
                                                                {{ __('Deactivated') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="input-form input-form-center">
                                                    <h4 class="heading">
                                                        {{ __('Card') }}
                                                    </h4>
                                                    <div class="action-list">
                                                        <select
                                                            class="process select droplinks {{ $admstore->is_pay42_card == 1 ? 'drop-success' : 'drop-danger' }}">
                                                            <option data-val="1"
                                                                value="{{ route('admin-gs-pay42-card', 1) }}"
                                                                {{ $admstore->is_pay42_card == 1 ? 'selected' : '' }}>
                                                                {{ __('Activated') }}</option>
                                                            <option data-val="0"
                                                                value="{{ route('admin-gs-pay42-card', 0) }}"
                                                                {{ $admstore->is_pay42_card == 0 ? 'selected' : '' }}>
                                                                {{ __('Deactivated') }}</option>
                                                        </select>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="col-xl-9">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Pay42 Key') }} *
                                                        <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="{{ __('Consult your token with the payment method provider.') }}"></i></span>
                                                    </h4>
                                                    <textarea class="input-field" name="pay42_token" placeholder="{{ __('Key') }}">{{ $admstore->pay42_token }}</textarea>

                                                </div>

                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Days until Expiration') }} *
                                                    </h4>
                                                    <textarea class="input-field" name="pay42_due_date" placeholder="{{ __('Calendar days to maturity.') }}">{{ $admstore->pay42_due_date }}</textarea>

                                                </div>

                                            </div>

                                        </div>
                                        <!--FECHAMENTO TAG ROW-->

                                        <div class="row justify-content-center">

                                            <button class="addProductSubmit-btn"
                                                type="submit">{{ __('Save') }}</button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('document').ready(function() {
            $("#store_filter").niceSelect('update');
        });

        $("#store_filter").on('change', function() {
            window.location.href = $(this).val();
        });
    </script>
@endsection
