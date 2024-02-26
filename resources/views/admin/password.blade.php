@extends('layouts.admin')
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Change Password') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.password') }}">{{ __('Change Password') }} </a>
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
                                style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form id="geniusform" action="{{ route('admin.password.update') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row flex-column justify-content-center align-items-center">

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Current Password') }} *</h4>
                                            <input type="password" id="current_password" class="input-field" name="cpass"
                                                placeholder="{{ __('Enter Current Password') }}" required=""
                                                value="">
                                            <span toggle="#current_password"
                                                class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('New Password') }} *</h4>
                                            <input type="password" id="new_password" class="input-field" name="newpass"
                                                placeholder="{{ __('Enter New Password') }}" required="" value="">
                                            <span toggle="#new_password"
                                                class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Re-Type New Password') }} *</h4>
                                            <input type="password" id="retype_password" class="input-field" name="renewpass"
                                                placeholder="{{ __('Re-Type New Password') }}" required=""
                                                value="">
                                            <span toggle="#retype_password"
                                                class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        </div>
                                    </div>

                                </div>

                                <div class="row justify-content-center">

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
