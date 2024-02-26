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
                    <h4 class="heading">{{ __('Loader') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-logo') }}">{{ __('Theme') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-load') }}">{{ __('Loader') }}</a>
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
        @include('includes.admin.partials.theme-tabs')
        <div class="add-logo-area">
            @include('includes.admin.form-both')
            <div class="gocover"
                style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Website Loader') }}
                            </h4>
                        </div>

                        <form class="uplogo-form" id="geniusform" action="{{ route('admin-gs-update') }}" method="POST"
                            enctype="multipart/form-data" style="display:block">
                            {{ csrf_field() }}

                            @include('includes.admin.form-both')

                            <div class="currrent-logo">
                                <h4 class="title">
                                    {{ __('Current Loader') }} :
                                </h4>
                                <img src="{{ $admstore->loaderUrl }}" alt="">
                            </div>

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Loader') }} :
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_loader == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-isloader', 1) }}"
                                                        {{ $admstore->is_loader == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0" value="{{ route('admin-gs-isloader', 0) }}"
                                                        {{ $admstore->is_loader == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-9">
                                        <div class="input-form input-form-center" style="overflow:hidden;">
                                            <h4 class="heading">
                                                <i class="icofont-question-circle" data-toggle="tooltip"
                                                    style="display: inline-block " data-placement="top"
                                                    title="{{ __('Loader animation') }}"></i> {{ __('Set New Loader') }} :
                                            </h4>
                                            <input class="img-upload1" type="file" name="loader">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="submit-area mb-4" style="padding-bottom:25px;">
                                <button type="submit" class="submit-btn">{{ __('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Admin Loader') }}
                            </h4>
                        </div>

                        <form class="uplogo-form" id="geniusform" action="{{ route('admin-gs-update') }}" method="POST"
                            enctype="multipart/form-data" style="display:block;">
                            {{ csrf_field() }}

                            @include('includes.admin.form-both')

                            <div class="currrent-logo">
                                <h4 class="title">
                                    {{ __('Current Loader') }} :
                                </h4>

                                <img src="{{ $admstore->adminLoaderUrl }}" alt="">
                            </div>

                            <div class="container-fluid">
                                <div class="row">

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Loader') }} :
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_admin_loader == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-is-admin-loader', 1) }}"
                                                        {{ $admstore->is_admin_loader == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-is-admin-loader', 0) }}"
                                                        {{ $admstore->is_admin_loader == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-9">
                                        <div class="input-form" style="overflow:hidden;">
                                            <h4 class="heading">
                                                <i class="icofont-question-circle" data-toggle="tooltip"
                                                    style="display: inline-block " data-placement="top"
                                                    title="{{ __('Loader animation') }}"></i> {{ __('Set New Loader') }} :
                                            </h4>
                                            <input class="img-upload1" type="file" name="admin_loader">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="submit-area mb-4" style="padding-bottom:25px;">
                                <button type="submit" class="submit-btn">{{ __('Submit') }}</button>
                            </div>
                        </form>
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
