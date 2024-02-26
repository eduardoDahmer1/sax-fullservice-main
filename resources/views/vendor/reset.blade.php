@extends('layouts.vendor')
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Change Password') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('vendor-dashboard') }}">{{ __('Dashbord') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('vendor-profile') }}">{{ __('Change Password') }} </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content text-center">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form id="geniusform" action="{{ route('user-reset-submit') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @include('includes.admin.form-both')

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Current Password') }}: </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="password" id="current_passwd" name="cpass" class="input-field"
                                            value="" required="">
                                    </div>
                                    <span toggle="#current_passwd" class="fa fa-fw fa-eye field-icon toggle-password"
                                        style="margin-top: 10px;"></span>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('New Password') }}: </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="password" id="new_passwd" name="newpass" class="input-field"
                                            value="" required="">
                                    </div>
                                    <span toggle="#new_passwd" class="fa fa-fw fa-eye field-icon toggle-password"
                                        style="margin-top: 10px;"></span>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Re-Type New Password') }}: </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="password" id="retype_new_passwd" name="renewpass" class="input-field"
                                            value="" required="">
                                    </div>
                                    <span toggle="#retype_new_passwd" class="fa fa-fw fa-eye field-icon toggle-password"
                                        style="margin-top: 10px;"></span>
                                </div>

                                <div class="form-links">
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
