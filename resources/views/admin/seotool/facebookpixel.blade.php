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
                                    <form id="geniusform" action="{{ route('admin-seotool-fbpixel-update') }}"
                                        method="POST" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        @include('includes.admin.form-both')

                                        <div class="row justify-content-center">
                                            <!-- General FB Pixel-->
                                            <div class="col-xl-8">
                                                <div class="input-form">
                                                    <h4 class="heading">
                                                        {{ __('Facebook Pixel Script:') }} *
                                                    </h4>
                                                    <div class="tawk-area">
                                                        <textarea name="facebook_pixel">{{ $tool->facebook_pixel }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <button class="addProductSubmit-btn"
                                                type="submit">{{ __('Save') }}</button>
                                        </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
