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
                    <h4 class="heading">{{ __('Logo') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-logo') }}">{{ __('Theme') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-logo') }}">{{ __('Logo') }}</a>
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
            <div class="gocover"
                style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-4 col-md-6">
                    <div class="special-box bg-gray">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Header Logo') }}
                            </h4>
                        </div>

                        <form class="uplogo-form" id="geniusform" action="{{ route('admin-gs-update') }}" method="POST"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include('includes.admin.form-both')
                            <div class="currrent-logo">
                                <img src="{{ $admstore->logoUrl }}" alt="">
                            </div>
                            <div class="set-logo">
                                <input class="img-upload1" type="file" name="logo">
                            </div>

                            <div class="submit-area mb-4">
                                <button type="submit" class="submit-btn">{{ __('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="special-box  bg-gray">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Footer Logo') }}
                            </h4>
                        </div>

                        <form class="uplogo-form" id="geniusform" action="{{ route('admin-gs-update') }}" method="POST"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include('includes.admin.form-both')
                            <div class="currrent-logo">
                                <img src="{{ $admstore->footerLogoUrl }}" alt="">
                            </div>
                            <div class="set-logo">
                                <input class="img-upload1" type="file" name="footer_logo">
                            </div>

                            <div class="submit-area mb-4">
                                <button type="submit" class="submit-btn">{{ __('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="special-box  bg-gray">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Invoice Logo') }}
                            </h4>
                        </div>

                        <form class="uplogo-form" id="geniusform" action="{{ route('admin-gs-update') }}" method="POST"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include('includes.admin.form-both')

                            <div class="currrent-logo">
                                <img src="{{ $admstore->invoiceLogoUrl }}" alt="">
                            </div>

                            <div class="set-logo">
                                <input class="img-upload1" type="file" name="invoice_logo">
                            </div>

                            <div class="submit-area mb-4">
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
