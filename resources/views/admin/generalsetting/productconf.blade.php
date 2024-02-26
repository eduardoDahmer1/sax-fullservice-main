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
                    <h4 class="heading">{{ __('Products') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-storeconf') }}">{{ __('Store') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-productconf') }}">{{ __('Products') }}</a>
                        </li>
                        @if (config('features.multistore'))
                            <li>
                                <div class="action-list godropdown">
                                    <select id="store_filter" class="process select go-dropdown-toggle">
                                        @foreach ($stores as $store)
                                            <option
                                                value="{{ route('admin-stores-isconfig', ['id' => $store['id'], 'redirect' => true]) }}"
                                                {{ $store['id'] == $admstore->id ? 'selected' : '' }}>{{ $store['domain'] }}
                                            </option>
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
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form action="{{ route('admin-gs-update') }}" id="geniusform" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row">

                                    <div class="col-xl-4">

                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Product Percentage (%)') }}
                                                <span>({{ __('Percentage to apply over the original product price') }})</span>
                                            </h4>
                                            <input name="product_percent" type="number" class="input-field"
                                                placeholder="{{ __('e.g 10') }}" step="1" min="0"
                                                value="{{ $admstore->product_percent }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Default Product Weight') }}
                                                <span>(Kg.)</span>
                                            </h4>
                                            <input name="correios_weight" type="number" class="input-field"
                                                placeholder="{{ __('e.g 0.3') }}" step="0.1" min="0"
                                                value="{{ $admstore->correios_weight }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Default Product Width') }}
                                                <span>(Cm.)</span>
                                            </h4>
                                            <input name="correios_width" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="0"
                                                value="{{ $admstore->correios_width }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Default Product Height') }}
                                                <span>(Cm.)</span>
                                            </h4>
                                            <input name="correios_height" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="0"
                                                value="{{ $admstore->correios_height }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Default Product Length') }}
                                                <span>(Cm.)</span>
                                            </h4>
                                            <input name="correios_length" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="0"
                                                value="{{ $admstore->correios_length }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="title-section-form"></div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Display Add to Cart and Buy Buttons at Home Page') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_cart_and_buy_available == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-iscartandbuyavailable', 1) }}"
                                                        {{ $admstore->is_cart_and_buy_available == 1 ? 'selected' : '' }}>
                                                        {{ __('Yes') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-iscartandbuyavailable', 0) }}"
                                                        {{ $admstore->is_cart_and_buy_available == 0 ? 'selected' : '' }}>
                                                        {{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Display Products Without Stock at Highlight Cards') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->show_products_without_stock == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-showproductswithoutstock', 1) }}"
                                                        {{ $admstore->show_products_without_stock == 1 ? 'selected' : '' }}>
                                                        {{ __('Yes') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-showproductswithoutstock', 0) }}"
                                                        {{ $admstore->show_products_without_stock == 0 ? 'selected' : '' }}>
                                                        {{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Display Product Prices') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->show_product_prices == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-productprices', 1) }}"
                                                        {{ $admstore->show_product_prices == 1 ? 'selected' : '' }}>
                                                        {{ __('Yes') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-productprices', 0) }}"
                                                        {{ $admstore->show_product_prices == 0 ? 'selected' : '' }}>
                                                        {{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Display Stock Number') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->show_stock == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-stock', 1) }}"
                                                        {{ $admstore->show_stock == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-stock', 0) }}"
                                                        {{ $admstore->show_stock == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Display Reference Code') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->reference_code == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-referencecode', 1) }}"
                                                        {{ $admstore->reference_code == 1 ? 'selected' : '' }}>
                                                        {{ __('Yes') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-referencecode', 0) }}"
                                                        {{ $admstore->reference_code == 0 ? 'selected' : '' }}>
                                                        {{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Product Comment') }} :
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_comment == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-iscomment', 1) }}"
                                                        {{ $admstore->is_comment == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-iscomment', 0) }}"
                                                        {{ $admstore->is_comment == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Product Rating') }} :
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_rating == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-israting', 1) }}"
                                                        {{ $admstore->is_rating == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-israting', 0) }}"
                                                        {{ $admstore->is_rating == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Product Report') }} :
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_report == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-isreport', 1) }}"
                                                        {{ $admstore->is_report == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-isreport', 0) }}"
                                                        {{ $admstore->is_report == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Category Attributes') }}:
                                                {{ __('Clickable') }} <span><i class="icofont-question-circle"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="{{ __('Attributes which can be selected. Used for customizing or selecting certain product feature.') }}"></i></span>
                                                {{ __('or') }}
                                                {{ __('Filterable') }} <span><i class="icofont-question-circle"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="{{ __('Attributes which are static features about certain product. Used as a search filter and feature showcase.') }}"></i></span>
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->attribute_clickable == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-attributeclickable', 1) }}"
                                                        {{ $admstore->attribute_clickable == 1 ? 'selected' : '' }}>
                                                        {{ __('Clickable') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-attributeclickable', 0) }}"
                                                        {{ $admstore->attribute_clickable == 0 ? 'selected' : '' }}>
                                                        {{ __('Filterable') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Attributes As Cards') }} :
                                                <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{ __('Show Product Attributes as Cards related to it.') }}"></i></span>
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_attr_cards == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-isattrcards', 1) }}"
                                                        {{ $admstore->is_attr_cards == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-isattrcards', 0) }}"
                                                        {{ $admstore->is_attr_cards == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Product Photo at Invoice') }} :
                                                <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{ __('Show Product Photo in the table at Sell Invoice.') }}"></i></span>
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_invoice_photo == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-isinvoicephoto', 1) }}"
                                                        {{ $admstore->is_invoice_photo == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-isinvoicephoto', 0) }}"
                                                        {{ $admstore->is_invoice_photo == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Display Products Cards Without Stock In Black And White') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->show_products_without_stock_baw == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-showproductswithoutstockbaw', 1) }}"
                                                        {{ $admstore->show_products_without_stock_baw == 1 ? 'selected' : '' }}>
                                                        {{ __('Yes') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-showproductswithoutstockbaw', 0) }}"
                                                        {{ $admstore->show_products_without_stock_baw == 0 ? 'selected' : '' }}>
                                                        {{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Back in Stock') }}:
                                                <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{ __('Allow users to subscribe and receive e-mail when a certain product is back in stock.') }}"></i></span>
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_back_in_stock == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-isbackinstock', 1) }}"
                                                        {{ $admstore->is_back_in_stock == 1 ? 'selected' : '' }}>
                                                        {{ __('Yes') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-isbackinstock', 0) }}"
                                                        {{ $admstore->is_back_in_stock == 0 ? 'selected' : '' }}>
                                                        {{ __('No') }}</option>
                                                </select>
                                            </div>
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
