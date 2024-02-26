@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Meta Keywords') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-seotool-keywords') }}">{{ __('Meta Keywords') }}</a>
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
                            <form id="geniusform" action="{{ route('admin-seotool-analytics-update') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['from' => $tool, 'type' => 'textarea'])
                                                @slot('name')
                                                    meta_keys
                                                @endslot
                                                @slot('value')
                                                    meta_keys
                                                @endslot
                                                {{ __('Meta Keywords') }} <i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block " data-placement="top"
                                                    title="{{ __('Meta keywords are keywords that will help your page to appear on search engines like Google') }}"></i>
                                            @endcomponent
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['from' => $tool, 'type' => 'textarea'])
                                                @slot('name')
                                                    meta_description
                                                @endslot
                                                @slot('value')
                                                    meta_description
                                                @endslot
                                                {{ __('Meta Description') }}<i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block " data-placement="top"
                                                    title="{{ __('Meta description is the description that will appear on the search results') }}"></i>
                                            @endcomponent
                                        </div>
                                    </div>

                                </div>


                                <div class="row justify-content-center">

                                    <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>

                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
