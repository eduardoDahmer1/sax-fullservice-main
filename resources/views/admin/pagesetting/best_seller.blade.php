@extends('layouts.admin')
@section('styles')
    <style>
        .mr-breadcrumb .links .action-list li {
            display: block;
        }

        .mr-breadcrumb .links .action-list ul {
            overflow-y: auto;
            max-height: 240px;
        }

        .mr-breadcrumb .links .action-list .go-dropdown-toggle {
            padding-left: 20px;
            padding-right: 30px;
        }
    </style>
@endsection
@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Best Seller') }} <i class="icofont-question-circle" data-toggle="tooltip"
                            data-placement="top" title="{{ __('Side banner that stay in the best-sellers section.') }}"
                            style="font-size: 15px;"></i></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-ps-best-seller') }}">{{ __('Banners') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-ps-best-seller') }}">{{ __('Best Seller') }} </a>
                        </li>
                        @if (config('features.multistore'))
                            <li>
                                <div class="action-list godropdown">
                                    <select id="store_filter" class="process select go-dropdown-toggle">
                                        @foreach ($stores as $store)
                                            <option
                                                value="{{ route('admin-stores-isconfig', ['id' => $store['id'], 'redirect' => true]) }}"
                                                {{ $store['id'] == $admstore->pagesettings->store_id ? 'selected' : '' }}>
                                                {{ $store['domain'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        @include('includes.admin.partials.banner-tabs')
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form id="geniusform" action="{{ route('admin-ps-update') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')
                                <div class="row">

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading"> {{ __('Top Banner Image') }} *
                                                <span>{{ __('(Preferred SIze: 285 X 410 Pixel)') }}</span>
                                            </h4>
                                            <div class="img-upload">
                                                <div id="image-preview" class="img-preview"
                                                    style="background: url({{ $admstore->pagesettings->best_seller_banner ? asset('storage/images/banners/' . $admstore->pagesettings->best_seller_banner) : asset('assets/images/noimage.png') }});">
                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                    <input type="file" name="best_seller_banner" class="img-upload"
                                                        id="image-upload">
                                                    @if ($admstore->pagesettings->best_seller_banner)
                                                        <button type="button" class="btn btn-danger m-2 remove-banner"
                                                            tipo="best_seller_banner">x</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Link') }} <i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block "
                                                    data-placement="top"
                                                    title="{{ __('Link that will open when the object get clicked') }}"></i>
                                            </h4>
                                            <input type="text" class="input-field" name="best_seller_banner_link"
                                                placeholder="{{ __('Link') }}"
                                                value="{{ $admstore->pagesettings->best_seller_banner_link }}">
                                        </div>

                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading"> {{ __('Bottom Banner Image') }} *
                                                <span>{{ __('(Preferred SIze: 285 X 410 Pixel)') }}</span>
                                            </h4>
                                            <div class="img-upload">
                                                <div id="image-preview" class="img-preview"
                                                    style="background: url({{ $admstore->pagesettings->best_seller_banner1 ? asset('storage/images/banners/' . $admstore->pagesettings->best_seller_banner1) : asset('assets/images/noimage.png') }});">
                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                    <input type="file" name="best_seller_banner1" class="img-upload"
                                                        id="image-upload">
                                                    @if ($admstore->pagesettings->best_seller_banner1)
                                                        <button type="button" class="btn btn-danger m-2 remove-banner"
                                                            tipo="best_seller_banner1">x</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Link') }} <i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block "
                                                    data-placement="top"
                                                    title="{{ __('Link that will open when the object get clicked') }}"></i>
                                            </h4>
                                            <input type="text" class="input-field" name="best_seller_banner_link1"
                                                placeholder="{{ __('Link') }}"
                                                value="{{ $admstore->pagesettings->best_seller_banner_link1 }}">
                                        </div>

                                    </div>


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
@section('scripts')
    <script>
        $('document').ready(function() {
            $("#store_filter").niceSelect('update');
        });

        $("#store_filter").on('change', function() {
            window.location.href = $(this).val();
        });
    </script>
@endsection
