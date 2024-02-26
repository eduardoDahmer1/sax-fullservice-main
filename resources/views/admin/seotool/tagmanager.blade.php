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
                                    <form id="geniusform" action="{{ route('admin-seotool-tagmanager-update') }}"
                                        method="POST" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        @include('includes.admin.form-both')
                                        <h4 class="heading text-center mb-5">
                                            {{ __('Google Tag Manager') }}
                                        </h4>

                                        <div class="row justify-content-center">

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">
                                                        {{ __('Head Script') }} *
                                                    </h4>
                                                    <div class="tawk-area">
                                                        <textarea name="tag_manager_head">{{ $tool->tag_manager_head }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="input-form">
                                                    <h4 class="heading">
                                                        {{ __('Body Script') }} *
                                                    </h4>
                                                    <div class="tawk-area">
                                                        <textarea name="tag_manager_body">{{ $tool->tag_manager_body }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row justify-content-center">

                                            <button class="addProductSubmit-btn"
                                                type="submit">{{ __('Save') }}</button>

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
