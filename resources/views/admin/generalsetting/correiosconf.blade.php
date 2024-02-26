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
                    <h4 class="heading">{{ __('Correios Shipping') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-shipping-index') }}">{{ __('Shipping Methods') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-correiosconf') }}">{{ __('Correios Shipping') }}</a>
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
                            <form action="{{ route('admin-gs-update') }}" id="geniusform" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')
                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="alert alert-info">
                                            <p>{{ __('Search your Zip Code:') }} <a
                                                    href="https://buscacepinter.correios.com.br/app/faixa_cep_uf_localidade/index.php"
                                                    target="_blank">BuscaFaixaCep</a></p>
                                            <p>{{ __('Local zip code will use the settings registered with the General
                                                                                        Shipping, not the address settings.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Store Zip Code') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="correios_cep" class="input-field"
                                            placeholder="{{ __('e.g 01000-000') }}" value="{{ $admstore->correios_cep }}">
                                    </div>
                                    <i class="icofont-question-circle" data-toggle="tooltip" data-placement="top"
                                        title="{{ __('Enter your local store zip code.') }}"></i>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Local Zip Code Start') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="localcep_start" class="input-field"
                                            placeholder="{{ __('e.g 01000-000') }}"
                                            value="{{ $admstore->localcep_start }}">
                                    </div>
                                    <i class="icofont-question-circle" data-toggle="tooltip" data-placement="top"
                                        title="{{ __('Insert the beginning of the zip code range that will be defined as Regional Freight.') }}"></i>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Local Zip Code End') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="localcep_end" class="input-field"
                                            placeholder="{{ __('e.g 01000-000') }}" value="{{ $admstore->localcep_end }}">
                                    </div>
                                    <i class="icofont-question-circle" data-toggle="tooltip" data-placement="top"
                                        title="{{ __('Insert the end of the zip code range that will be defined as Regional Freight') }}"></i>
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
@endsection
