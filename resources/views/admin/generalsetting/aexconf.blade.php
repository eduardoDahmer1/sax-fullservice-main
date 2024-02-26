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
                    <h4 class="heading">{{ __('AEX Shipping') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-shipping-index') }}">{{ __('Shipping Methods') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-aexconf') }}">{{ __('AEX Shipping') }}</a>
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
        @include('includes.admin.partials.shipping-tabs')
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <div class="submit-loader">
                                <img src="{{ $admstore->adminLoaderUrl }}" alt="">
                            </div>
                            <form action="{{ route('admin-gs-update') }}" id="geniusform" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('AEX Public Key') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="aex_public" class="input-field" value="{{ $admstore->aex_public }}"
                                            required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('AEX Private Key') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="aex_private" class="input-field" value="{{ $admstore->aex_private }}"
                                            required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Origin City') }}:</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <select id="aex_origin" name="aex_origin">
                                            <option value="">{{ __('Select City') }}</option>
                                            @foreach ($aex_cities as $city)
                                                <option {{ $city->codigo_ciudad == $admstore->aex_origin ? 'selected' : '' }}
                                                    value="{{ $city->codigo_ciudad }}">{{ $city->denominacion }} -
                                                    {{ $city->departamento_denominacion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Main street') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="aex_calle_principal" class="input-field"
                                            value="{{ $admstore->aex_calle_principal }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Cross street') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="aex_calle_transversal" class="input-field"
                                            value="{{ $admstore->aex_calle_transversal }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Building number') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="aex_numero_casa" class="input-field"
                                            value="{{ $admstore->aex_numero_casa }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Telephone') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="aex_telefono" class="input-field"
                                            value="{{ $admstore->aex_telefono }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Production Mode') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select
                                                class="process select droplinks {{ $admstore->is_aex_production ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1" value="{{ route('admin-gs-aex-production', 1) }}"
                                                    {{ $admstore->is_aex_production ? 'selected' : '' }}>
                                                    {{ __('Activated') }}</option>
                                                <option data-val="0" value="{{ route('admin-gs-aex-production', 0) }}"
                                                    {{ $admstore->is_aex_production == 0 ? 'selected' : '' }}>
                                                    {{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Calculate Insurance') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select
                                                class="process select droplinks {{ $admstore->is_aex_insurance ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1" value="{{ route('admin-gs-aex-insurance', 1) }}"
                                                    {{ $admstore->is_aex_insurance ? 'selected' : '' }}>
                                                    {{ __('Activated') }}</option>
                                                <option data-val="0" value="{{ route('admin-gs-aex-insurance', 0) }}"
                                                    {{ $admstore->is_aex_insurance == 0 ? 'selected' : '' }}>
                                                    {{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <a class="mybtn1 btn-info btn-update-aex-cities"
                                            data-href="{{ route('admin-gs-update-aex-cities') }}">
                                            <i class="fas fa-sync-alt"></i><span>{{ __('Update AEX Cities') }}</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                                    </div>
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
    <script>
        $('.btn-update-aex-cities').on('click', function(e) {
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $.ajax({
                type: "GET",
                url: $(this).attr('data-href'),
                success: function(data) {
                    if ((data.errors)) {
                        $('.alert-success').hide();
                        $('.alert-danger').show();
                        $('.alert-danger p').html(data);
                        for (var error in data.errors) {
                            $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>')
                        }
                        if (admin_loader == 1) {
                            $('.submit-loader').hide();
                        }
                    } else {
                        var aex_cities_link = '{{ route('admin-gs-load-aex-cities') }}';
                        if (aex_cities_link != '') {
                            $.ajax({
                                type: "GET",
                                url: aex_cities_link,
                                success: function(data_cities) {
                                    let attrHtml = data_cities;
                                    $("#aex_origin").html(attrHtml);
                                },
                                complete: function(data_cities) {
                                    $('.alert-danger').hide();
                                    $('.alert-success').show();
                                    $('.alert-success p').html(data);
                                    if (admin_loader == 1) {
                                        $('.submit-loader').hide();
                                    }
                                }
                            });
                        }
                    }
                }
            });
            return false;
        });
    </script>
@endsection
