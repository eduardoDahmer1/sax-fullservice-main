@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')
    <section class="user-dashbord">
        <div class="container">
            <div class="row">

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
                                        <form id="userform"
                                            action="{{ route('user-reset-password', ['token' => $token]) }}" method="POST"
                                            enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            @include('includes.admin.form-both')

                                            <input type="hidden" name="token" value="{{ $token }}">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="password" name="password" class="input-field"
                                                        placeholder="{{ __('New Password') }}" value=""
                                                        required="">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="password" name="renewpass" class="input-field"
                                                        placeholder="{{ __('Re-Type New Password') }}" value=""
                                                        required="">
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
