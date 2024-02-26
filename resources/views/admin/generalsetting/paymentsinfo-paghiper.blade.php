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
                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('PagHiper ApiKey') }} *
                                                        <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="{{ __('Consult your API Key with the payment method provider.') }}"></i></span>
                                                    </h4>
                                                    @php
                                                        clock($admstore);
                                                    @endphp
                                                    <textarea class="input-field" name="paghiper_api_key" placeholder="{{ __('API Key') }}">{{ $admstore->paghiper_api_key }}</textarea>

                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('PagHiper Token') }} *
                                                        <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="{{ __('Consult your PV with the payment method provider.') }}"></i></span>
                                                    </h4>
                                                    <textarea class="input-field" name="paghiper_token" placeholder="{{ __('Token') }}">{{ $admstore->paghiper_token }}</textarea>

                                                </div>
                                            </div>
                                        </div>

                                        @if (config('gateways.paghiper'))
                                            <div class="row">

                                                <div class="col-xl-12">
                                                    <div class="input-form input-form-center">
                                                        <h4 class="heading">
                                                            {{ __('PagHiper') }}
                                                        </h4>
                                                        <div class="action-list">
                                                            <select
                                                                class="process select droplinks {{ $admstore->is_paghiper == 1 ? 'drop-success' : 'drop-danger' }}">
                                                                <option data-val="1"
                                                                    value="{{ route('admin-gs-paghiper', 1) }}"
                                                                    {{ $admstore->is_paghiper == 1 ? 'selected' : '' }}>
                                                                    {{ __('Activated') }}
                                                                </option>
                                                                <option data-val="0"
                                                                    value="{{ route('admin-gs-paghiper', 0) }}"
                                                                    {{ $admstore->is_paghiper == 0 ? 'selected' : '' }}>
                                                                    {{ __('Deactivated') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-12">
                                                    <div class="input-form">
                                                        <h4 class="heading">{{ __('Days until Expiration') }} *
                                                            <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="{{ __('Calendar days to maturity.') }}"></i></span>
                                                        </h4>
                                                        <input type="number" min="0" max="400"
                                                            class="input-field" name="paghiper_days_due_date"
                                                            placeholder="{{ __('Calendar days to maturity. 0 to 400 days.') }}"
                                                            value="{{ $admstore->paghiper_days_due_date }}">

                                                    </div>
                                                </div>

                                                <div class="col-xl-6">
                                                    <div class="input-form input-form-center">
                                                        <h4 class="heading">
                                                            {{ __('Discount by Payment on Bank Slip') }}
                                                        </h4>
                                                        <div class="action-list">
                                                            <select
                                                                class="process select droplinks {{ $admstore->paghiper_is_discount == 1 ? 'drop-success' : 'drop-danger' }}">
                                                                <option data-val="1"
                                                                    value="{{ route('admin-gs-paghiper-is-discount', 1) }}"
                                                                    {{ $admstore->paghiper_is_discount == 1 ? 'selected' : '' }}>
                                                                    {{ __('Activated') }}
                                                                </option>
                                                                <option data-val="0"
                                                                    value="{{ route('admin-gs-paghiper-is-discount', 0) }}"
                                                                    {{ $admstore->paghiper_is_discount == 0 ? 'selected' : '' }}>
                                                                    {{ __('Deactivated') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-6">
                                                    <div class="input-form">
                                                        <h4 class="heading">{{ __('Discount Percentage') }} *
                                                            <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="{{ __('Discount percentage on payment through this method.') }}"></i></span>
                                                        </h4>
                                                        <input type="number" min="0" max="400"
                                                            class="input-field" name="paghiper_discount"
                                                            placeholder="{{ __('Integers only: from 0 to 100.') }}"
                                                            value="{{ $admstore->paghiper_discount }}">

                                                    </div>
                                                </div>

                                            </div>
                                            <!--FECHAMENTO TAG ROW-->
                                        @endif

                                        @if (config('gateways.paghiper_pix') && config('gateways.paghiper'))
                                            <hr>
                                        @endif

                                        @if (config('gateways.paghiper_pix'))
                                            <div class="row">

                                                <div class="col-xl-12">
                                                    <div class="input-form input-form-center">
                                                        <h4 class="heading">
                                                            {{ __('PagHiper PIX QR Code') }}
                                                            <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="{{ __('Enable PIX Payment via PagHiper.') }}"></i></span>
                                                        </h4>
                                                        <div class="action-list">
                                                            <select
                                                                class="process select droplinks {{ $admstore->is_paghiper_pix == 1 ? 'drop-success' : 'drop-danger' }}">
                                                                <option data-val="1"
                                                                    value="{{ route('admin-gs-paghiper-pix', 1) }}"
                                                                    {{ $admstore->is_paghiper_pix == 1 ? 'selected' : '' }}>
                                                                    {{ __('Activated') }}
                                                                </option>
                                                                <option data-val="0"
                                                                    value="{{ route('admin-gs-paghiper-pix', 0) }}"
                                                                    {{ $admstore->is_paghiper_pix == 0 ? 'selected' : '' }}>
                                                                    {{ __('Deactivated') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-12">
                                                    <div class="input-form">
                                                        <h4 class="heading">{{ __('Days until PIX Expiration') }} *
                                                            <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="{{ __('Calendar days to maturity.') }}"></i></span>
                                                        </h4>
                                                        <input type="number" min="0" max="400"
                                                            class="input-field" name="paghiper_pix_days_due_date"
                                                            placeholder="{{ __('Calendar days to maturity. 0 to 400 days.') }}"
                                                            value="{{ $admstore->paghiper_pix_days_due_date }}">

                                                    </div>
                                                </div>

                                                <div class="col-xl-6">
                                                    <div class="input-form input-form-center">
                                                        <h4 class="heading">
                                                            {{ __('Discount on PIX Payment') }}
                                                        </h4>
                                                        <div class="action-list">
                                                            <select
                                                                class="process select droplinks {{ $admstore->paghiper_pix_is_discount == 1 ? 'drop-success' : 'drop-danger' }}">
                                                                <option data-val="1"
                                                                    value="{{ route('admin-gs-paghiper-pix-is-discount', 1) }}"
                                                                    {{ $admstore->paghiper_pix_is_discount == 1 ? 'selected' : '' }}>
                                                                    {{ __('Activated') }}
                                                                </option>
                                                                <option data-val="0"
                                                                    value="{{ route('admin-gs-paghiper-pix-is-discount', 0) }}"
                                                                    {{ $admstore->paghiper_pix_is_discount == 0 ? 'selected' : '' }}>
                                                                    {{ __('Deactivated') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-6">
                                                    <div class="input-form">
                                                        <h4 class="heading">{{ __('PIX Discount Percentage') }} *
                                                            <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="{{ __('Discount percentage on payment through this method.') }}"></i></span>
                                                        </h4>
                                                        <input type="number" min="0" max="400"
                                                            class="input-field" name="paghiper_pix_discount"
                                                            placeholder="{{ __('Integers only: from 0 to 100.') }}"
                                                            value="{{ $admstore->paghiper_pix_discount }}">

                                                    </div>
                                                </div>
                                            </div>
                                        @endif

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
