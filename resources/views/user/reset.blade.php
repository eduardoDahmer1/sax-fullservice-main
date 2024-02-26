@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')
    <section class="user-dashbord">
        <div class="container">
            <div class="row">
                @include('includes.user-dashboard-sidebar')
                <div class="col-lg-8">
                    <div class="user-profile-details">
                        <div class="account-info">
                            <div class="header-area">
                                <h4 class="title">
                                    {{ __('Reset Password') }}
                                </h4>
                            </div>
                            <div class="edit-info-area">

                                <div class="body">
                                    <div class="edit-info-area-form">
                                        <div class="gocover"
                                            style="background: url({{ asset('storage/images/' . $gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                                        </div>
                                        <form id="userform" action="{{ route('user-reset-submit') }}" method="POST"
                                            enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            @include('includes.admin.form-both')
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="password" id="current_passwd" name="cpass"
                                                        class="input-field" placeholder="{{ __('Current Password') }}"
                                                        value="" required="">
                                                    <span toggle="#current_passwd"
                                                        class="fa fa-fw fa-eye field-icon toggle-password"
                                                        style="margin-top: -45px;"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="password" id="new_passwd" name="newpass"
                                                        class="input-field" placeholder="{{ __('New Password') }}"
                                                        value="" required="">
                                                    <span toggle="#new_passwd"
                                                        class="fa fa-fw fa-eye field-icon toggle-password"
                                                        style="margin-top: -45px;"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="password" id="retype_new_passwd" name="renewpass"
                                                        class="input-field" placeholder="{{ __('Re-Type New Password') }}"
                                                        value="" required="">
                                                    <span toggle="#retype_new_passwd"
                                                        class="fa fa-fw fa-eye field-icon toggle-password"
                                                        style="margin-top: -45px;"></span>
                                                </div>
                                            </div>

                                            <div class="form-links">
                                                <button class="submit-btn" type="submit">{{ __('Submit') }}</button>
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
    </section>
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
