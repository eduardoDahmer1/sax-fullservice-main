@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Integrations') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-integrations') }}">{{ __('Integrations') }}</a>
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
                                    <form action="{{ route('admin-social-update') }}" id="geniusform" method="POST"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}

                                        @include('includes.admin.form-both')

                                        <div class="row">

                                            <div class="col-xl-4">
                                                <div class="input-form input-form-center">
                                                    <h4 class="heading">
                                                        {{ __('Google Login') }}
                                                    </h4>
                                                    <div class="action-list">
                                                        <select
                                                            class="process select droplinks {{ $data->g_check == 1 ? 'drop-success' : 'drop-danger' }}">
                                                            <option data-val="1"
                                                                value="{{ route('admin-social-googleup', 1) }}"
                                                                {{ $data->g_check == 1 ? 'selected' : '' }}>
                                                                {{ __('Activated') }}</option>
                                                            <option data-val="0"
                                                                value="{{ route('admin-social-googleup', 0) }}"
                                                                {{ $data->g_check == 0 ? 'selected' : '' }}>
                                                                {{ __('Deactivated') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-8">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Client ID') }} *
                                                        <span>{{ __('Get Your Client ID from console.cloud.google.com') }}</span>
                                                    </h4>
                                                    <input type="text" class="input-field"
                                                        placeholder="{{ __('Enter Client ID') }}" name="gclient_id"
                                                        value="{{ $data->gclient_id }}" required="">
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Client Secret') }} *
                                                        <span>{{ __('Get Your Client Secret from console.cloud.google.com') }}</span>
                                                    </h4>

                                                    <input type="text" class="input-field"
                                                        placeholder="{{ __('Enter Client Secret') }}" name="gclient_secret"
                                                        value="{{ $data->gclient_secret }}" required="">
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Website URL') }} *</h4>
                                                    <input type="text" class="input-field"
                                                        placeholder="{{ __('Website URL') }}" value="{{ url('/') }}"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Redirect URL') }} *
                                                        <span>{{ __('Copy this url and paste it to your Redirect URL in console.cloud.google.com.') }}</span>
                                                    </h4>
                                                    <input type="text" class="input-field"
                                                        placeholder="{{ __('Enter Site URL') }}" name="gredirect"
                                                        value="{{ url('/auth/google/callback') }}" readonly>
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
