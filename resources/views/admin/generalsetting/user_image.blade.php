@extends('layouts.admin')
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Customer Default Image') }} </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Customer Default Image') }} </a>
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
                                    <div class="col-xl-12">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading"> {{ __('Customer Image') }} *</h4>
                                            <small>{{ __('(This image will be displayed if users do not upload profile photo)') }}</small><br>
                                            <small
                                                style="padding-bottom:10px;">{{ __('(Preferred Size: 600 X 600 Pixel)') }}</small>
                                            <div class="img-upload">
                                                <div id="image-preview" class="img-preview"
                                                    style="background: url({{ $admstore->userImageUrl }});">
                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                    <input type="file" name="user_image" class="img-upload"
                                                        id="image-upload">
                                                </div>

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
