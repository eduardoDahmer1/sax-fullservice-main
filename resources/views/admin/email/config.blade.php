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
                    <h4 class="heading">{{ __('Email Configuration') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-mail-config') }}">{{ __('Email Configuration') }}</a>
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

                                <div class="row ">

                                    <div class="col-xl-4">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('SMTP') }}
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_smtp == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-issmtp', 1) }}"
                                                        {{ $admstore->is_smtp == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0" value="{{ route('admin-gs-issmtp', 0) }}"
                                                        {{ $admstore->is_smtp == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" class="input-field" placeholder="{{ __('SMTP') }}"
                                        value="smtp" required="" readonly>

                                    <div class="col-xl-8">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('SMTP Host') }} *
                                            </h4>
                                            <input type="text" class="input-field" placeholder="{{ __('SMTP Host') }}"
                                                name="smtp_host" value="{{ $admstore->smtp_host }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('SMTP Port') }} *
                                            </h4>
                                            <input type="text" class="input-field" placeholder="{{ __('SMTP Port') }} "
                                                name="smtp_port" value="{{ $admstore->smtp_port }}">

                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Encryption') }} *
                                            </h4>
                                            <input type="text" class="input-field" placeholder="{{ __('Encryption') }} "
                                                name="email_encryption" value="{{ $admstore->email_encryption }}">

                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('SMTP Username') }} *
                                            </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('SMTP Username') }} " name="smtp_user"
                                                value="{{ $admstore->smtp_user }}">

                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('SMTP Password') }} *
                                            </h4>
                                            <input type="password" id="password_smtp" class="input-field"
                                                placeholder="{{ __('SMTP Password') }} " name="smtp_pass"
                                                value="{{ $admstore->smtp_pass }}">
                                            <span toggle="#password_smtp"
                                                class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('From Email') }} *
                                            </h4>
                                            <input type="text" class="input-field" placeholder="{{ __('From Email') }} "
                                                name="from_email" value="{{ $admstore->from_email }}">

                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('From Name') }} *
                                            </h4>
                                            <input type="text" class="input-field" placeholder="{{ __('From Name') }} "
                                                name="from_name" value="{{ $admstore->from_name }}">

                                        </div>
                                    </div>


                                </div>
                                <!--FECHAMENTO TAG ROW-->


                                <div class="row justify-content-center">

                                    <button class="addProductSubmit-btn" type="submit">{{ __('Submit') }}</button>

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
        $('.toggle-password').click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
@endsection
