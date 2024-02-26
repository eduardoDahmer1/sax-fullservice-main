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
                                    <form action="{{ route('admin-gs-update') }}" id="geniusform" method="POST"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}

                                        @include('includes.admin.form-both')

                                        <div class="row">
                                            <div class="col-xl-8">
                                                <div class="input-form">
                                                    <h4 class="heading">
                                                        {{ __('Cronjob URL') }} *
                                                        <span>{{ __('(Copy This URL and paste this to cron job.)') }} <a
                                                                target="_blank"
                                                                href="https://www.youtube.com/watch?v=Hw0fbM7E80Q">{{ __('Check Tutorial') }}</a></span>
                                                    </h4>
                                                    <textarea class="input-field" readonly="">{{ url('/vendor/subscription/check') }}</textarea>
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
    </div>
@endsection
