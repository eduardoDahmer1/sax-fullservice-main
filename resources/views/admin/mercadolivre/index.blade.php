@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Mercado Livre') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-integrations') }}">{{ __('Marketplaces') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    @include('includes.admin.partials.integration-menu')
                                </div>
                                <div class="col-lg-9">
                                    <form action="{{ route('admin-gs-integrations-mercadolivre-update') }}" id="geniusform"
                                        method="POST" enctype="multipart/form-data">
                                        {{ csrf_field() }}

                                        @include('includes.admin.form-both')
                                        <div class="row">

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Mercado Livre Client ID/App ID') }} *
                                                        <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="{{ __('Consult your token with the payment method provider.') }}"></i></span>
                                                    </h4>
                                                    <textarea class="input-field" name="app_id" placeholder="{{ __('Token') }}">{{ $meli->app_id }}</textarea>

                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Mercado Livre Client Secret') }} *
                                                        <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="{{ __('Consult your token with the payment method provider.') }}"></i></span>
                                                    </h4>
                                                    <textarea class="input-field" name="client_secret" placeholder="Client Secret">{{ $meli->client_secret }}</textarea>
                                                </div>
                                            </div>

                                            @if ($meli->app_id && $meli->redirect_uri)
                                                <div class="col-xl-12">
                                                    <div class="input-form">
                                                        <h4 class="heading">
                                                            {{ __('Mercado Livre Authorization Code, Access &
                                                                                                                                                                            Refresh Token') }}
                                                            *
                                                            <span>({{ __('Get all Mercado Livre credentials by simply clicking
                                                                                                                                                                                the button below.') }})</span>
                                                        </h4>
                                                        <a class="btn btn-success" target="_blank"
                                                            href="https://auth.mercadolivre.com.br/authorization?response_type=code&client_id={{ $meli->app_id }}&redirect_uri={{ $meli->redirect_uri }}">{{ __('Get it here') }}</a>
                                                        <p>
                                                            <b>{{ $meli->authorization_code }}</b>
                                                        </p>
                                                        <p> {{ __('Updated at') }}:
                                                            {{ \Carbon\Carbon::parse($meli->updated_at)->format('d-m-Y H:i:s') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-xl-4">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Mercado Livre Redirect URL') }}
                                                        <span>({{ __('Current Mercado Livre Redirect URL') }})</span>
                                                    </h4>
                                                    <p>
                                                        <textarea class="input-field" name="redirect_uri" placeholder="Client Secret">{{ $meli->redirect_uri }}</textarea>
                                                    </p>
                                                    <p>{{ __('This URL is always the same. You need to configure your Redirect URL at Mercado Livre with this URL.') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-xl-4">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Current Access Token') }}
                                                        <span>({{ __('Current Mercado Livre Access Token') }})</span>
                                                    </h4>
                                                    <p>
                                                        <b>{{ $meli->access_token }}</b>
                                                    </p>
                                                    <p> {{ __('Updated at') }}:
                                                        {{ \Carbon\Carbon::parse($meli->updated_at)->format('d-m-Y H:i:s') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-xl-4">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Current Refresh Token') }}
                                                        <span>({{ __('Current Mercado Livre Refresh Token') }})</span>
                                                    </h4>
                                                    <p>
                                                        <b>{{ $meli->refresh_token }}</b>
                                                    </p>
                                                    <p> {{ __('Updated at') }}:
                                                        {{ \Carbon\Carbon::parse($meli->updated_at)->format('d-m-Y H:i:s') }}
                                                    </p>
                                                </div>
                                            </div>

                                        </div>

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
