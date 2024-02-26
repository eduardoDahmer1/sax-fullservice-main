@extends('layouts.admin')

@section('styles')
    <style>
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
                    <h4 class="heading">{{ __('Shipping') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-storeconf') }}">{{ __('Store') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-shippingconf') }}">{{ __('Shipping') }}</a>
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
        @include('includes.admin.partials.store-tabs')
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form action="{{ route('admin-gs-update') }}" id="geniusform" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row">

                                    <div class="col-lg-3">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Country Shipping') }} *</h4>
                                            <div class="form-group">
                                                <select id="country_ship" name="country_ship">
                                                    <option value="">{{ __('Global') }}</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->country_code }}"
                                                            {{ $admstore->country_ship == $country->country_code ? 'selected' : '' }}>
                                                            {{ $country->country_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">{{ __('Multiple Shipping') }} :</h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->multiple_shipping == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-mship', 1) }}"
                                                        {{ $admstore->multiple_shipping == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-mship', 0) }}"
                                                        {{ $admstore->multiple_shipping == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Display Correios') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_correios == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-iscorreios', 1) }}"
                                                        {{ $admstore->is_correios == 1 ? 'selected' : '' }}>
                                                        {{ __('Yes') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-iscorreios', 0) }}"
                                                        {{ $admstore->is_correios == 0 ? 'selected' : '' }}>
                                                        {{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    @if (config('features.aex_shipping'))
                                        <div class="col-lg-3">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">
                                                    {{ __('Display AEX') }}:
                                                </h4>
                                                <div class="action-list">
                                                    <select
                                                        class="process select droplinks {{ $admstore->is_aex == 1 ? 'drop-success' : 'drop-danger' }}">
                                                        <option data-val="1" value="{{ route('admin-gs-isaex', 1) }}"
                                                            {{ $admstore->is_aex == 1 ? 'selected' : '' }}>
                                                            {{ __('Yes') }}</option>
                                                        <option data-val="0" value="{{ route('admin-gs-isaex', 0) }}"
                                                            {{ $admstore->is_aex == 0 ? 'selected' : '' }}>
                                                            {{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (config('features.melhorenvio_shipping'))
                                        <div class="col-lg-3">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">
                                                    {{ __('Display Melhor Envio') }}:
                                                </h4>
                                                <div class="action-list">
                                                    <select
                                                        class="process select droplinks {{ $admstore->is_melhorenvio == 1 ? 'drop-success' : 'drop-danger' }}">
                                                        <option data-val="1"
                                                            value="{{ route('admin-gs-ismelhorenvio', 1) }}"
                                                            {{ $admstore->is_melhorenvio == 1 ? 'selected' : '' }}>
                                                            {{ __('Yes') }}</option>
                                                        <option data-val="0"
                                                            value="{{ route('admin-gs-ismelhorenvio', 0) }}"
                                                            {{ $admstore->is_melhorenvio == 0 ? 'selected' : '' }}>
                                                            {{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (config('features.fedex_shipping'))
                                        <div class="col-lg-3">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">
                                                    {{ __('Display Fedex') }}:
                                                </h4>
                                                <div class="action-list">
                                                    <select
                                                        class="process select droplinks {{ $admstore->is_fedex == 1 ? 'drop-success' : 'drop-danger' }}">
                                                        <option data-val="1"
                                                            value="{{ route('admin-gs-isfedex', 1) }}"
                                                            {{ $admstore->is_fedex == 1 ? 'selected' : '' }}>
                                                            {{ __('Yes') }}</option>
                                                        <option data-val="0"
                                                            value="{{ route('admin-gs-isfedex', 0) }}"
                                                            {{ $admstore->is_fedex == 0 ? 'selected' : '' }}>
                                                            {{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-lg-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Multiple Packaging') }} :
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->multiple_packaging == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-mpackage', 1) }}"
                                                        {{ $admstore->multiple_packaging == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-mpackage', 0) }}"
                                                        {{ $admstore->multiple_packaging == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Enable ZIP Auto Search') }}
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_zip_validation == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-is-zip-validation', 1) }}"
                                                        {{ $admstore->is_zip_validation == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-is-zip-validation', 0) }}"
                                                        {{ $admstore->is_zip_validation == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!--FECHAMENTO TAG ROW-->

                                <div class="row justify-content-center">

                                    <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>

                                </div>
                            </form>
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
