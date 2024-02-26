@extends('layouts.admin')

@section('styles')
    <style>
        .card.card-settings {
            border: 1px solid #1B263B !important;
        }

        .card.card-settings .card-header {
            background: none;
        }

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
                    <h4 class="heading">{{ __('Home Page') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-storeconf') }}">{{ __('Store') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-ps-customize') }}">{{ __('Home Page') }}</a>
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
        @include('includes.admin.partials.store-tabs')
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="social-links-area">
                            <div class="gocover"
                                style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form id="geniusform" action="{{ route('admin-ps-homeupdate') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row">
                                    <div class="col">
                                        <p>{{ __('Select which content blocks will be show using the sliders in each card.') }}<br>
                                            {{ __('Save your preferences clicking on the save button below.') }}
                                        </p>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row row-cols-1 row-cols-md-4">
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="slider">{{ __('Slider') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="slider" value="1"
                                                        {{ $admstore->pagesettings->slider == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">
                                                    {{ __('Images will be show with a timer in the page header') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="featured_category">{{ __('Featured Category') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="featured_category" value="1"
                                                        {{ $admstore->pagesettings->featured_category == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">
                                                    {{ __('Show the main categories marked as featured using a different block.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="featured">{{ __('Featured') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="featured" value="1"
                                                        {{ $admstore->pagesettings->featured == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">{{ __('Show the products marked as featured') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="small_banner">{{ __('Top Small Banner') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="small_banner" value="1"
                                                        {{ $admstore->pagesettings->small_banner == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">
                                                    {{ __('Show banners above the content, side by side, in a retangular
                                                                            format.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="best">{{ __('Best Seller') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="best" value="1"
                                                        {{ $admstore->pagesettings->best == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">{{ __('Show products marked as best sellers.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="flash_deal">{{ __('Flash Deal') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="flash_deal" value="1"
                                                        {{ $admstore->pagesettings->flash_deal == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">{{ __('Show products marked as flash deals') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="large_banner">{{ __('Large Banner') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="large_banner" value="1"
                                                        {{ $admstore->pagesettings->large_banner == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">
                                                    {{ __('Show only one banner among the content in a retangular format.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="top_rated">{{ __('Top Rated') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="top_rated" value="1"
                                                        {{ $admstore->pagesettings->top_rated == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">{{ __('Show products marked as top rated.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="bottom_small">{{ __('Bottom Small Banner') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="bottom_small" value="1"
                                                        {{ $admstore->pagesettings->bottom_small == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">
                                                    {{ __('Show banners below the content, side by side, in a retangular
                                                                            format.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="big">{{ __('Big Save') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="big" value="1"
                                                        {{ $admstore->pagesettings->big == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">{{ __('Show products marked as big save.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="hot_sale">{{ __('Hot, New, Trending & Sale') }}</label>
                                                <div>
                                                    <label class="switch">
                                                        <input type="checkbox" name="hot_sale" value="1"
                                                            {{ $admstore->pagesettings->hot_sale == 1 ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">
                                                    {{ __('Show a block listing products marked as either hot, new, trending or
                                                                            sales, with a small size.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="review_blog">{{ __('Review') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="reviews_store" value="1"
                                                        {{ $admstore->pagesettings->reviews_store == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">{{ __('Show reviews.') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="review_blog">{{ __('Blog') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="blog_posts" value="1"
                                                        {{ $admstore->pagesettings->blog_posts == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">{{ __('Show the last posts of the blog.') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="partners">{{ __('Logo Slider') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="partners" value="1"
                                                        {{ $admstore->pagesettings->partners == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">{{ __('Show logos, side by side, with a timer.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="service">{{ __('Service') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="service" value="1"
                                                        {{ $admstore->pagesettings->service == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">
                                                    {{ __('Show services, side by side, using a different block.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <p>{{ __('Some content can have a special configuration. Use the slider to select a specific
                                                            setting.') }}
                                        </p>
                                        <hr>
                                    </div>
                                </div>

                                <div class="row row-cols-1 row-cols-md-4">
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="random_banners">{{ __('Random Banners') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="random_banners" value="1"
                                                        {{ $admstore->pagesettings->random_banners == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">
                                                    {{ __('Banners and sliders will be show in a random order instead of the
                                                                            order specified in the record.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card card-settings h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <label for="random_products">{{ __('Random Products') }}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="random_products" value="1"
                                                        {{ $admstore->pagesettings->random_products == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="card-body p-3">
                                                <p class="card-text">{{ __('Products will be show in a random order.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="submit-btn">{{ __('Save') }}</button>
                                    </div>
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
