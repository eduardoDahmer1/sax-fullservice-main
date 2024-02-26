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
                <h4 class="heading">{{ __('Fedex Shipping') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('admin-shipping-index') }}">{{ __('Shipping Methods') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('admin-gs-fedexconf') }}">{{ __('Fedex Shipping') }}</a>
                    </li>
                    @if (config('features.multistore'))
                    <li>
                        <div class="action-list godropdown">
                            <select id="store_filter" class="process select go-dropdown-toggle">
                                @foreach ($stores as $store)
                                <option value="{{ route('admin-stores-isconfig', ['id' => $store['id'], 'redirect' => true]) }}" {{ $store['id'] == $admstore->id ? 'selected' : '' }}>{{ $store['domain'] }}
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
                        <div class="gocover" style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <form action="{{ route('admin-gs-update-fedex') }}" id="geniusform" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include('includes.admin.form-both')
                            <div class="row justify-content-center">
                                <div class="col-lg-12">
                                    <img class="card-img-top m-auto" src="{{ asset('assets/images/58428e7da6515b1e0ad75ab5.png') }}" alt="Fedex imagem logo" style="width: 25%; display: block">
                                </div>
                            </div>

                            <div class="row justify-content-center">

                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading">
                                            <span><i class="icofont-question-circle" data-toggle="tooltip" data-placement="top" title="{{ __('Turns on real shipping. Disable it to test.') }}"></i></span>
                                            {{ __('Production Mode') }}:
                                        </h4>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="action-list">
                                        <select class="process select droplinks {{ $admstore->fedex->production == 1 ? 'drop-success' : 'drop-danger' }}">
                                            <option data-val="1" value="{{ route('admin-gs-fedex-production', 1) }}" {{ $admstore->fedex->production == 1 ? 'selected' : '' }}>
                                                {{ __('Activated') }}
                                            </option>
                                            <option data-val="0" value="{{ route('admin-gs-fedex-production', 0) }}" {{ $admstore->fedex->production == 0 ? 'selected' : '' }}>
                                                {{ __('Deactivated') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Client id') }}
                                        </h4>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <input name="client_id" class="input-field" placeholder="{{ __('e.g XXXX-XXX-XXXX-XXX') }}" value="{{ isset($fedex->client_id) ? $fedex->client_id : '' }}">
                                </div>

                                <i class="icofont-question-circle" data-toggle="tooltip" data-placement="top" title="{{ __('Specify the Client ID also known as API Key received during FedEx Developer portal registration.') }}"></i>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Client secret') }}
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input name="client_secret" class="input-field" placeholder="{{ __('e.g XXXX-XXX-XXXX-XXX') }}" value="{{ isset($fedex->client_secret) ? $fedex->client_secret : '' }}">
                                </div>
                                <i class="icofont-question-circle" data-toggle="tooltip" data-placement="top" title="{{ __('Specify the Client secret also known as Secret Key received during FedEx Developer portal registration.') }}"></i>
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