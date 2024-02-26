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
        <div class="add-product-content social-links-area">
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
                                            <div class="col-xl-4">
                                                <div class="input-form input-form-center">
                                                    <h4 class="heading">
                                                        {{ __('PagSeguro') }}
                                                    </h4>
                                                    <div class="action-list">
                                                        <select
                                                            class="process select droplinks {{ $admstore->is_pagseguro == 1 ? 'drop-success' : 'drop-danger' }}">
                                                            <option data-val="1"
                                                                value="{{ route('admin-gs-pagseguro', 1) }}"
                                                                {{ $admstore->is_pagseguro == 1 ? 'selected' : '' }}>
                                                                {{ __('Activated') }}</option>
                                                            <option data-val="0"
                                                                value="{{ route('admin-gs-pagseguro', 0) }}"
                                                                {{ $admstore->is_pagseguro == 0 ? 'selected' : '' }}>
                                                                {{ __('Deactivated') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-4">
                                                <div class="input-form input-form-center">
                                                    <h4 class="heading">
                                                        {{ __('Sandbox') }}
                                                        <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="{{ __('Enable the sandbox mode to make payment tests only, it will not work for real purchases.') }}"></i></span>
                                                    </h4>
                                                    <div class="action-list">
                                                        <select
                                                            class="process select droplinks {{ $admstore->is_pagseguro_sandbox == 1 ? 'drop-success' : 'drop-danger' }}">
                                                            <option data-val="1"
                                                                value="{{ route('admin-gs-pagseguro-sandbox', 1) }}"
                                                                {{ $admstore->is_pagseguro_sandbox == 1 ? 'selected' : '' }}>
                                                                {{ __('Activated') }}</option>
                                                            <option data-val="0"
                                                                value="{{ route('admin-gs-pagseguro-sandbox', 0) }}"
                                                                {{ $admstore->is_pagseguro_sandbox == 0 ? 'selected' : '' }}>
                                                                {{ __('Deactivated') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('PagSeguro Email') }} *
                                                        <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="{{ __('Your PagSeguro E-mail.') }}"></i></span>
                                                    </h4>
                                                    <textarea class="input-field" name="pagseguro_email" placeholder="{{ __('PagSeguro Email') }}">{{ $admstore->pagseguro_email }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('PagSeguro Token') }} *
                                                        <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="{{ __('Consult your token with the payment method provider.') }}"></i></span>
                                                    </h4>
                                                    <textarea class="input-field" name="pagseguro_token" placeholder="{{ __('PagSeguro Token') }}">{{ $admstore->pagseguro_token }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-xl-12">
                                                <div class="input-form">
                                                    @component('admin.components.input-localized', ['from' => $admstore, 'type' => 'textarea'])
                                                        @slot('name')
                                                            pagseguro_text
                                                        @endslot
                                                        @slot('value')
                                                            pagseguro_text
                                                        @endslot
                                                        {{ __('PagSeguro Text') }} * <span><i class="icofont-question-circle"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="{{ __('Enter the message that will be displayed to the customer when using the payment method.') }}"
                                                                style="margin-top: -120px; margin-right:42px;"></i></span>
                                                    @endcomponent

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
