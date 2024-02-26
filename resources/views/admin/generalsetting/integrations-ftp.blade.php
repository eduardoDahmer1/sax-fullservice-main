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

                                        <div class="row justify-content-center">
                                            <div class="col-md-12">
                                                <div class="input-form">
                                                    <h4 class="heading">
                                                        {{ __('FTP Images Folder') }}
                                                    </h4>
                                                    <input type="text" class="input-field" placeholder="products_images/"
                                                        name="ftp_folder" value="{{ $admstore->ftp_folder }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-md-4">
                                                <button class="addProductSubmit-btn btnUpdateFtp"
                                                    type="submit">{{ __('Save') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                    @if ($gs->ftp_folder)
                                        <div class="row justify-content-center">
                                            <div class="col-md-12">
                                                <div class="input-form">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="heading">
                                                                {{ __('Generate/Refresh Thumbnails') }}
                                                            </h4>
                                                        </div>
                                                    </div>
                                                    <div class="submit-loader">
                                                        <img src="{{ $admstore->adminLoaderUrl }}" alt="">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button class="add-btn" id="generateProductThumbnails"
                                                                href="{{ route('admin-prod-generatethumbnails') }}">{{ __('Product Thumbnails') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
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
            $(".btnUpdateFtp").on('click', function() {
                setTimeout(function() {
                    location.reload();
                }, 500);
            });
        });
    </script>
@endsection
