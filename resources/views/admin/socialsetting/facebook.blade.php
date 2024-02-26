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
                                                <div class="input-form">
                                                    <h4 class="heading">
                                                        {{ __('Facebook Login') }}
                                                    </h4>
                                                    <div class="action-list">
                                                        <select
                                                            class="process select droplinks {{ $data->f_check == 1 ? 'drop-success' : 'drop-danger' }}">
                                                            <option data-val="1"
                                                                value="{{ route('admin-social-facebookup', 1) }}"
                                                                {{ $data->f_check == 1 ? 'selected' : '' }}>
                                                                {{ __('Activated') }}</option>
                                                            <option data-val="0"
                                                                value="{{ route('admin-social-facebookup', 0) }}"
                                                                {{ $data->f_check == 0 ? 'selected' : '' }}>
                                                                {{ __('Deactivated') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-8">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('App ID') }} *
                                                        <span>{{ __('Get Your App ID from developers.facebook.com') }}</span>
                                                    </h4>

                                                    <input type="text" class="input-field"
                                                        placeholder="{{ __('Enter App ID') }}" name="fclient_id"
                                                        value="{{ $data->fclient_id }}" required="">
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('App Secret') }} *
                                                        <span>{{ __('Get Your App Secret from developers.facebook.com') }}</span>
                                                    </h4>

                                                    <input type="text" class="input-field"
                                                        placeholder="{{ __('Enter App Secret') }}" name="fclient_secret"
                                                        value="{{ $data->fclient_secret }}" required="">
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Website URL') }} *</h4>
                                                    <input type="text" class="input-field"
                                                        placeholder="{{ __('Website URL') }}" value="{{ url('/') }}"
                                                        readonly="">
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Valid OAuth Redirect URI') }} *
                                                        <span>{{ __('Copy this url and paste it to your Valid OAuth Redirect URI in
                                                                                    developers.facebook.com.') }}</span>
                                                    </h4>
                                                    @php
                                                        $url = url('/auth/facebook/callback');
                                                        $url = preg_replace('/^http:/i', 'https:', $url);
                                                    @endphp
                                                    <input type="text" class="input-field"
                                                        placeholder="{{ __('Enter Site URL') }}" name="fredirect"
                                                        value="{{ $url }}" readonly>
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
