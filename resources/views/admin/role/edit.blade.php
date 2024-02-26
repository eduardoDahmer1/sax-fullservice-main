@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Edit Role') }} <a class="add-btn" href="{{ route('admin-role-index') }}">
                            <i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-staff-index') }}">{{ __('Staffs') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-role-index') }}">{{ __('Roles') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-role-edit', $data->id) }}">{{ __('Edit Role') }}</a>
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
                                style="background: url({{ $admstore->adminLoaderUrl }})
                            no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form id="geniusform" action="{{ route('admin-role-update', $data->id) }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            <p><small>* {{ __('indicates a required field') }}</small></p>
                                        </div>
                                    </div>
                                </div>

                                @include('includes.admin.form-both')

                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="input-form">

                                            @component('admin.components.input-localized', ['required' => true, 'from' => $data])
                                                @slot('name')
                                                    name
                                                @endslot
                                                @slot('placeholder')
                                                    {{ __('Name') }}
                                                @endslot
                                                @slot('value')
                                                    name
                                                @endslot
                                                {{ __('Name') }} *
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h5 class="text-center">{{ __('Permissions') }}</h5>
                                <hr>

                                <div class="row justify-content-center">

                                    <div class="col-lg-3">
                                        <div class="input-form input-form-center">
                                            <label class="control-label">{{ __('Stores') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" name="section[]" value="stores"
                                                    {{ $data->sectionCheck('stores') ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!--FECHAMENTO TAG COL-XL-2 -->

                                    <div class="col-lg-3">
                                        <div class="input-form input-form-center">
                                            <label class="control-label">{{ __('Catalog') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" name="section[]" value="catalog"
                                                    {{ $data->sectionCheck('catalog') ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!--FECHAMENTO TAG COL-XL-2 -->

                                    <div class="col-lg-3">
                                        <div class="input-form input-form-center">
                                            <label class="control-label">{{ __('Sell') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" name="section[]" value="sell"
                                                    {{ $data->sectionCheck('sell') ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!--FECHAMENTO TAG COL-XL-2 -->

                                    <div class="col-lg-3">
                                        <div class="input-form input-form-center">
                                            <label class="control-label">{{ __('Content') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" name="section[]" value="content"
                                                    {{ $data->sectionCheck('content') ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!--FECHAMENTO TAG COL-XL-2 -->

                                    <div class="col-lg-3">
                                        <div class="input-form input-form-center">
                                            <label class="control-label">{{ __('Config') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" name="section[]" value="config"
                                                    {{ $data->sectionCheck('config') ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!--FECHAMENTO TAG COL-XL-2 -->

                                    <div class="col-lg-3">
                                        <div class="input-form input-form-center">
                                            <label class="control-label">{{ __('Marketing') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" name="section[]" value="marketing"
                                                    {{ $data->sectionCheck('marketing') ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!--FECHAMENTO TAG COL-XL-2 -->

                                    @if (config('features.marketplace'))
                                        <div class="col-lg-3">
                                            <div class="input-form input-form-center">
                                                <label class="control-label">{{ __('Marketplace') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="section[]" value="marketplace"
                                                        {{ $data->sectionCheck('marketplace') ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <!--FECHAMENTO TAG COL-XL-2 -->
                                    @endif

                                    <div class="col-lg-3">
                                        <div class="input-form input-form-center">
                                            <label class="control-label">{{ __('System') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" name="section[]" value="system"
                                                    {{ $data->sectionCheck('system') ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!--FECHAMENTO TAG COL-XL-2 -->

                                    <div class="col-lg-3">
                                        <div class="input-form input-form-center">
                                            <label class="control-label">{{ __('Support') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" name="section[]" value="support"
                                                    {{ $data->sectionCheck('support') ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!--FECHAMENTO TAG COL-XL-2 -->

                                    @if (config('features.api'))
                                        <div class="col-lg-3">
                                            <div class="input-form input-form-center">
                                                <label class="control-label">{{ __('API') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="section[]" value="api"
                                                        {{ $data->sectionCheck('api') ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <!--FECHAMENTO TAG COL-XL-2 -->
                                    @endif


                                </div>
                                <!--FECHAMENTO TAG ROW-->

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
