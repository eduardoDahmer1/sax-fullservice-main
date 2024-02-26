@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Website Maintenance') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-maintenance') }}">{{ __('Website Maintenance') }}</a>
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
                            <form id="geniusform" action="{{ route('admin-gs-update') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row">

                                    <div class="col-xl-6">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Website Maintenance') }}
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_maintain == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-maintain', 1) }}"
                                                        {{ $admstore->is_maintain == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-maintain', 0) }}"
                                                        {{ $admstore->is_maintain == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-6">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Dark Mode') }}
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_dark_mode == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-darkmode', 1) }}"
                                                        {{ $admstore->is_dark_mode == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-darkmode', 0) }}"
                                                        {{ $admstore->is_dark_mode == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
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
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@endsection
