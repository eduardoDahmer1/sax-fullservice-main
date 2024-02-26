@extends('layouts.vendor')
@section('styles')
    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>

    <link href="{{ asset('assets/vendor/css/product.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/jquery.Jcrop.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/Jcrop-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/cropper.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Physical Product') }} <a class="add-btn"
                            href="{{ route('vendor-prod-types') }}"><i class="fas fa-arrow-left"></i>
                            {{ __('Back') }}</a>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('vendor-dashboard') }}">{{ __('Dashboard') }}</a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Products') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('vendor-prod-index') }}">{{ __('All Products') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('vendor-prod-types') }}">{{ __('Add New Product') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('vendor-prod-physical-create') }}">{{ __('Physical Product') }}</a>
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
                                style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form id="geniusform" action="{{ route('vendor-prod-store') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.vendor.form-both')

                                <div class="title-section-form">
                                    <span>1</span>
                                    <h3>
                                        {{ __('Mandatory Data') }}
                                    </h3>
                                </div>

                                <div class="row border-sep">
                                    <!--COMEÇO DA ROW DE DADOS OBRIGATORIOS-->
                                    <div class="col-12">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['required' => true])
                                                @slot('name')
                                                    name
                                                @endslot
                                                @slot('placeholder')
                                                    {{ __('Enter Product Name') }}
                                                @endslot
                                                {{ __('Product Name') }}*
                                            @endcomponent

                                            @php
                                                $temp_sku = Illuminate\Support\Str::random(3) . substr(time(), 6, 8) . Illuminate\Support\Str::random(3);
                                            @endphp
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Category') }}*</h4>
                                            <select id="cat" name="category_id" required="">
                                                <option value="">{{ __('Select Category') }}</option>
                                                @foreach ($cats as $cat)
                                                    <option data-href="{{ route('vendor-subcat-load', $cat->id) }}"
                                                        value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <div class="d-flex">
                                                <h4 class="heading">
                                                    {{ __('Product Current Price') }}*
                                                    <span>
                                                        ({{ __('In') }} {{ $sign->name }})
                                                    </span>
                                                </h4>
                                            </div>
                                            <input name="price" step="0.1" type="number" class="input-field"
                                                placeholder="{{ __(' e.g 20') }}" required="" min="0">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Ref Code') }}* </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Enter Product Code') }}" name="ref_code" required=""
                                                value="{{ $temp_sku }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Product Sku') }}* </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Enter Product Sku') }}" name="sku" required=""
                                                value="{{ $temp_sku }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Display in Stores') }}* </h4>
                                            @foreach ($storesList as $store)
                                                <div class="row justify-content-left">
                                                    <div class="col-lg-12 d-flex justify-content-between">
                                                        <label class="control-label"
                                                            for="store{{ $store->id }}">{{ $store->title }}
                                                            |
                                                            {{ $store->domain }}</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="stores[]"
                                                                id="store{{ $store->id }}" value="{{ $store->id }}"
                                                                checked>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div id="catAttributes"></div>
                                        <div id="subcatAttributes"></div>
                                        <div id="childcatAttributes"></div>
                                    </div>

                                </div>
                                <!--FINAL DA ROW DE DADOS OBRIGATORIOS-->

                                <div class="title-section-form">
                                    <span>2</span>
                                    <h3>
                                        {{ __('Important Data') }}
                                    </h3>
                                </div>

                                <div class="row border-sep">
                                    <!--COMEÇO DA ROW DE DADOS OPCIONAIS IMPORTANTES-->

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Brand') }}</h4>
                                            <select id="brand" name="brand_id" required>
                                                <option value="">{{ __('Select Brand') }}</option>
                                                @foreach ($brands as $brand)
                                                    <option data-href="{{ route('admin-brand-load', $brand->id) }}"
                                                        value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Model Number') }} </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Enter Product Model Number') }}" name="mpn"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Sub Category') }}*</h4>
                                            <select id="subcat" name="subcategory_id" disabled="">
                                                <option value="">{{ __('Select Sub Category') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Child Category') }}*</h4>
                                            <select id="childcat" name="childcategory_id" disabled="">
                                                <option value="">{{ __('Select Child Category') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form"
                                            style="display:flex;flex-direction:column;align-items:center;">
                                            <h4 class="heading">{{ __('Feature Image') }} </h4>
                                            <div class="row">
                                                <div class="panel panel-body">
                                                    <div class="span4 cropme text-center img-form-product" id="landscape">
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="javascript:;" id="crop-image" class="d-inline-block mybtn1">
                                                <i class="icofont-upload-alt"></i> {{ __('Upload Image Here') }}
                                            </a>
                                        </div>
                                    </div>

                                    <input type="hidden" id="feature_photo" name="photo" value="">
                                    <input type="file" name="gallery[]" class="hidden" id="uploadgallery"
                                        accept="image/*" multiple>

                                    <div class="col-xl-6">

                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product Gallery Images') }} *
                                            </h4>
                                            <a href="#" class="set-gallery" data-toggle="modal"
                                                data-target="#setgallery">
                                                <i class="icofont-plus"></i> {{ __('Set Gallery') }}
                                            </a>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-7">
                                                <div class="checkbox-wrapper">
                                                    <input type="checkbox" name="product_condition_check"
                                                        class="checkclick" id="conditionCheck" value="1">
                                                    <label
                                                        for="conditionCheck">{{ __('Allow Product Condition') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="showbox input-form">
                                            <h4 class="heading">{{ __('Product Condition') }}*</h4>
                                            <select name="product_condition">
                                                <option value="2">{{ __('New') }}</option>
                                                <option value="1">{{ __('Used') }}</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['type' => 'richtext'])
                                                @slot('name')
                                                    details
                                                @endslot
                                                {{ __('Product Description') }}
                                            @endcomponent
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['type' => 'richtext'])
                                                @slot('name')
                                                    policy
                                                @endslot
                                                {{ __('Product Buy/Return Policy') }}
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>

                                <div class="title-section-form">
                                    <span>3</span>
                                    <h3>
                                        {{ __('Extra Data') }}
                                    </h3>
                                </div>

                                <div class="row">
                                    <!--COMEÇO DA ROW DE DADOS EXTRAS-->

                                    <div class="col-xl-6">
                                        <div class="col-lg-4">
                                            <div class="left-area">

                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="form-list">
                                                <ul class="list list-personalizada">
                                                    <li>
                                                        <input class="checkclick1" name="shipping_time_check"
                                                            type="checkbox" id="check1" value="1">
                                                        <label
                                                            for="check1">{{ __('Allow Estimated Shipping Time') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="showbox input-form">

                                            @component('admin.components.input-localized')
                                                @slot('name')
                                                    ship
                                                @endslot
                                                @slot('placeholder')
                                                    {{ __('Estimated Shipping Time') }}
                                                @endslot
                                                {{ __('Product Estimated Shipping Time') }}
                                            @endcomponent

                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="col-lg-4">
                                            <div class="left-area">
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="form-list">
                                                <ul class="list list-personalizada">
                                                    <li>
                                                        <input name="size_check" type="checkbox" id="size-check"
                                                            value="1">
                                                        <label for="size-check">{{ __('Allow Product Sizes') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="showbox input-form" id="size-display">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                </div>
                                                <div class="col-xl-12">
                                                    <div class="product-size-details" id="size-section">
                                                        <div class="size-area">
                                                            <span class="remove size-remove"><i
                                                                    class="fas fa-times"></i></span>
                                                            <div class="row">
                                                                <div class="col-md-4 col-sm-6">
                                                                    <label>
                                                                        {{ __('Size Name') }} :
                                                                        <span>
                                                                            {{ __('(eg. S,M,L,XL,XXL,3XL,4XL)') }}
                                                                        </span>
                                                                    </label>
                                                                    <input type="text" name="size[]"
                                                                        class="input-field"
                                                                        placeholder="{{ __(' Size Name') }}">
                                                                </div>
                                                                <div class="col-md-4 col-sm-6">
                                                                    <label>
                                                                        {{ __('Size Qty') }} :
                                                                        <span>
                                                                            {{ __('(Number of quantity of this size)') }}
                                                                        </span>
                                                                    </label>
                                                                    <input type="number" name="size_qty[]"
                                                                        class="input-field"
                                                                        placeholder="{{ __(' Size Qty') }}"
                                                                        value="1" min="1">
                                                                </div>
                                                                <div class="col-md-4 col-sm-6">
                                                                    <label>
                                                                        {{ __('Size Price') }} :
                                                                        <span>
                                                                            {{ __("(This price will be added with base
                                                                                                                                                        																		price)") }}
                                                                        </span>
                                                                    </label>
                                                                    <input type="number" step="0.01"
                                                                        name="size_price[]" class="input-field"
                                                                        placeholder="{{ __('Size Price') }}"
                                                                        value="0" min="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <a href="javascript:;" id="size-btn" class="add-more"><i
                                                            class="fas fa-plus"></i>{{ __('Add More Size') }} </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-6-->

                                    <div class="col-xl-6">
                                        <div class="col-lg-4">
                                            <div class="left-area">
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="form-list">
                                                <ul class="list list-personalizada">
                                                    <li>
                                                        <input class="checkclick1" name="color_check" type="checkbox"
                                                            id="check3" value="1">
                                                        <label for="check3">{{ __('Allow Product Colors') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="showbox">
                                            <div class="input-form product-size-details">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>
                                                            {{ __('Product Colors') }}
                                                            <span>
                                                                {{ __('(Choose Your Favorite Colors)') }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>
                                                            {{ __('Color Qty') }}
                                                            <span>
                                                                {{ __('(Number of quantity of this Color)') }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>
                                                            {{ __('Color Price') }}
                                                            <span>
                                                                {{ __('(This price will be added with base price)') }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div id="color-section">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="select-input-color">
                                                                <div class="color-area">
                                                                    <div class="input-group colorpicker-component cp">
                                                                        <input type="text" name="color[]"
                                                                            value="#000000" class="input-field cp" />
                                                                        <span class="input-group-addon"><i></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" name="color_qty[]" class="input-field"
                                                                placeholder="{{ __(' Color Qty') }}" value="0"
                                                                min="0" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" step="0.01" name="color_price[]"
                                                                class="input-field"
                                                                placeholder="{{ __(' Color Price') }}" value="0"
                                                                min="0" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="button"
                                                                class="btn btn-danger text-white color-remove"><i
                                                                    class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="javascript:;" id="color-btn" class="add-more mt-4 mb-3"><i
                                                        class="fas fa-plus"></i>{{ __('Add More Color') }} </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-6-->

                                    <div class="col-xl-6">
                                        <div class="col-lg-4">
                                            <div class="left-area">
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="form-list">
                                                <ul class="list list-personalizada">
                                                    <li>
                                                        <input class="checkclick1" name="whole_check" type="checkbox"
                                                            id="whole_check" value="1">
                                                        <label
                                                            for="whole_check">{{ __('Allow Product Whole Sell') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="showbox input-form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="featured-keyword-area">
                                                        <div class="feature-tag-top-filds" id="whole-section">
                                                            <div class="feature-area">
                                                                <span class="remove whole-remove"><i
                                                                        class="fas fa-times"></i></span>
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <input type="number" name="whole_sell_qty[]"
                                                                            class="input-field"
                                                                            placeholder="{{ __('Enter Quantity') }}"
                                                                            min="0">
                                                                    </div>

                                                                    <div class="col-lg-6">
                                                                        <input type="number" name="whole_sell_discount[]"
                                                                            class="input-field"
                                                                            placeholder="{{ __('Enter Discount Percentage') }}"
                                                                            min="0" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <a href="javascript:;" id="whole-btn" class="add-fild-btn"><i
                                                                    class="icofont-plus"></i>
                                                                {{ __('Add More Field') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-6-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Maximum Quantity per Sale') }}
                                                <span>
                                                    (Un.)
                                                </span>
                                            </h4>
                                            <input name="max_quantity" type="number" class="input-field"
                                                placeholder="{{ __(' e.g 20') }}" step="1" min="0">
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-3-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product Weight') }}
                                                <span>
                                                    (Kg.)
                                                </span>
                                            </h4>
                                            <input name="weight" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="0.01" min="0">
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-3-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product width') }}
                                                <span>
                                                    (cm.)
                                                </span>
                                            </h4>
                                            <input name="width" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="1">
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-3-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product height') }}
                                                <span>
                                                    (cm.)
                                                </span>
                                            </h4>
                                            <input name="height" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="1">
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-3-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product length') }}
                                                <span>(cm.)</span>
                                            </h4>
                                            <input name="length" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="1">
                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-3-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Product Previous Price') }}*
                                                <span>
                                                    {{ __('(Optional)') }}
                                                </span>
                                            </h4>
                                            <input name="previous_price" step="0.1" type="number"
                                                class="input-field" placeholder="{{ __(' e.g 20') }}" min="0">
                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-3-->

                                    <div class="col-xl-3" id="stckprod">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Product Stock') }}*
                                                <span>
                                                    {{ __('(Leave Empty will Show Always Available)') }}
                                                </span>
                                            </h4>
                                            <input name="stock" type="text" class="input-field"
                                                placeholder="{{ __('e.g 20') }}">
                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-3-->

                                    <div class="col-xl-3">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="checkbox-wrapper list list-personalizada">
                                                    <input type="checkbox" name="measure_check" class="checkclick"
                                                        id="allowProductMeasurement" value="1">
                                                    <label
                                                        for="allowProductMeasurement">{{ __('Allow Product Measurement') }}</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="showbox input-form">
                                            <h4 class="heading">{{ __('Product Measurement') }}*</h4>
                                            <select id="product_measure">
                                                <option value="">{{ __('None') }}</option>
                                                <option value="Gram">{{ __('Gram') }}</option>
                                                <option value="Kilogram">{{ __('Kilogram') }}</option>
                                                <option value="Litre">{{ __('Litre') }}</option>
                                                <option value="Pound">{{ __('Pound') }}</option>
                                                <option value="Custom">{{ __('Custom') }}</option>
                                            </select>
                                            <div class="hidden" id="measure">
                                                <input name="measure" type="text" id="measurement"
                                                    class="input-field" placeholder="{{ __(' Enter Unit') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--FINAL ROW COL-XL-3-->

                                <div class="col-xl-12">
                                    <div class="title-section-form"></div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="featured-keyword-area input-form">
                                            <h4 class="heading">
                                                {{ __('Feature Tags') }}
                                                <span>
                                                    {{ __('(You can create up to 2 Product Tags)') }}
                                                </span>
                                            </h4>

                                            <div class="feature-tag-top-filds" id="feature-section">
                                                <div class="feature-area mb-3">
                                                    <div class="row mb-0">
                                                        <div class="col-lg-6">
                                                            <div class="panel panel-lang">
                                                                <div class="panel-body">
                                                                    <div class="tab-content">
                                                                        <div role="tabpanel" class="tab-pane active"
                                                                            id="{{ $lang->locale }}-features0">
                                                                            <input type="text"
                                                                                name="{{ $lang->locale }}[features][]"
                                                                                class="input-field"
                                                                                placeholder="{{ __('Enter Your Keyword') }}">
                                                                        </div>
                                                                        @foreach ($locales as $loc)
                                                                            @if ($loc->locale === $lang->locale)
                                                                                @continue
                                                                            @endif
                                                                            <div role="tabpanel" class="tab-pane"
                                                                                id="{{ $loc->locale }}-features0">
                                                                                <input type="text"
                                                                                    name="{{ $loc->locale }}[features][]"
                                                                                    class="input-field"
                                                                                    placeholder="{{ __('Enter Your Keyword') }}">
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="input-group colorpicker-component cp">
                                                                <input type="text" name="colors[]" value="#000000"
                                                                    class="input-field cp" />
                                                                <span class="input-group-addon"><i></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-lang">
                                                        <div class="panel-footer">
                                                            <ul class="nav nav-pills" role="tablist">
                                                                <li role="presentation" class="active">
                                                                    <a href="#{{ $lang->locale }}-features0"
                                                                        class="active"
                                                                        aria-controls="{{ $lang->locale }}-features0"
                                                                        role="tab" data-toggle="tab">
                                                                        {{ $lang->language }}
                                                                    </a>
                                                                </li>
                                                                @foreach ($locales as $loc)
                                                                    @if ($loc->locale === $lang->locale)
                                                                        @continue
                                                                    @endif
                                                                    <li role="presentation">
                                                                        <a href="#{{ $loc->locale }}-features0"
                                                                            aria-controls="{{ $loc->locale }}-features0"
                                                                            role="tab" data-toggle="tab">
                                                                            {{ $loc->language }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="feature-tag-top-filds" id="feature-section">
                                                <div class="feature-area mb-3">
                                                    <div class="row mb-0">
                                                        <div class="col-lg-6">
                                                            <div class="panel panel-lang">
                                                                <div class="panel-body">
                                                                    <div class="tab-content">
                                                                        <div role="tabpanel" class="tab-pane active"
                                                                            id="{{ $lang->locale }}-features1">
                                                                            <input type="text"
                                                                                name="{{ $lang->locale }}[features][]"
                                                                                class="input-field"
                                                                                placeholder="{{ __('Enter Your Keyword') }}">
                                                                        </div>
                                                                        @foreach ($locales as $loc)
                                                                            @if ($loc->locale === $lang->locale)
                                                                                @continue
                                                                            @endif
                                                                            <div role="tabpanel" class="tab-pane"
                                                                                id="{{ $loc->locale }}-features1">
                                                                                <input type="text"
                                                                                    name="{{ $loc->locale }}[features][]"
                                                                                    class="input-field"
                                                                                    placeholder="{{ __('Enter Your Keyword') }}">
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="input-group colorpicker-component cp">
                                                                <input type="text" name="colors[]" value="#000000"
                                                                    class="input-field cp" />
                                                                <span class="input-group-addon"><i></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-lang">
                                                        <div class="panel-footer">
                                                            <ul class="nav nav-pills" role="tablist">
                                                                <li role="presentation" class="active">
                                                                    <a href="#{{ $lang->locale }}-features1"
                                                                        class="active"
                                                                        aria-controls="{{ $lang->locale }}-features1"
                                                                        role="tab" data-toggle="tab">
                                                                        {{ $lang->language }}
                                                                    </a>
                                                                </li>
                                                                @foreach ($locales as $loc)
                                                                    @if ($loc->locale === $lang->locale)
                                                                        @continue
                                                                    @endif
                                                                    <li role="presentation">
                                                                        <a href="#{{ $loc->locale }}-features1"
                                                                            aria-controls="{{ $loc->locale }}-features1"
                                                                            role="tab" data-toggle="tab">
                                                                            {{ $loc->language }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-6-->

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Youtube Video URL') }}
                                                <span>{{ __('(Optional)') }}</span>
                                            </h4>
                                            <input name="youtube" type="text" class="input-field"
                                                placeholder="{{ __('Youtube Video URL') }}">
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="checkbox-wrapper list list-personalizada">
                                                    <input type="checkbox" name="seo_check" value="1"
                                                        class="checkclick" id="allowProductSEO" value="1">
                                                    <label for="allowProductSEO">{{ __('Allow Product SEO') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="showbox">
                                            @component('admin.components.input-localized', ['type' => 'tags'])
                                                @slot('name')
                                                    meta_tag
                                                @endslot
                                                {{ __('Meta Tags') }}
                                            @endcomponent

                                            @component('admin.components.input-localized', ['type' => 'textarea'])
                                                @slot('name')
                                                    meta_description
                                                @endslot
                                                @slot('placeholder')
                                                    {{ __('Details') }}
                                                @endslot
                                                {{ __('Meta Description') }}
                                            @endcomponent

                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-6-->
                                </div>

                                <input type="hidden" name="type" value="Physical">
                                <div class="row">
                                    <div class="col-xl-12 text-center">
                                        <button class="addProductSubmit-btn"
                                            type="submit">{{ __('Create Product') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Image Gallery') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="top-area">
                        <div class="row">
                            <div class="col-sm-6 text-right">
                                <div class="upload-img-btn">
                                    <label for="image-upload" id="prod_gallery"><i
                                            class="icofont-upload-alt"></i>{{ __('Upload File') }}</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <a href="javascript:;" class="upload-done" data-dismiss="modal"> <i
                                        class="fas fa-check"></i> {{ __('Done') }}</a>
                            </div>
                            <div class="col-sm-12 text-center">(
                                <small>{{ __('You can upload multiple Images.') }}</small>
                                )
                            </div>
                        </div>
                    </div>
                    <div class="gallery-images">
                        <div class="selected-image">
                            <div class="row">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/jquery.SimpleCropper.js') }}"></script>
    <script src="{{ asset('assets/admin/js/cropper.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.SimpleCropper.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("input[name='color_qty[]']").on('keyup', function() {
                var color_input = $("input[name='color_qty[]']");
                var color_stock_total = 0;
                for (i = 0; i < color_input.length; i++) {
                    color_stock_total = parseInt(color_stock_total) + parseInt(color_input[i].value);
                }
                if (isNaN(color_stock_total)) {
                    $("#stock").val(null);
                } else {
                    $("#stock").val(color_stock_total);
                }

            });
            $("#check3").change(function() {
                if ($(this).is(":checked")) {
                    var color_input = $("input[name='color_qty[]']");
                    var color_stock_total = 0;
                    for (i = 0; i < color_input.length; i++) {
                        color_stock_total = parseInt(color_stock_total) + parseInt(color_input[i].value);
                    }
                    if (isNaN(color_stock_total)) {
                        $("#stock").val(null);
                    } else {
                        $("#stock").val(color_stock_total);
                    }

                    $("#stock").attr("readonly", true);
                    $("#stock").addClass("disabled");
                } else {
                    $("#stock").attr("readonly", false);
                    $("#stock").removeClass("disabled");
                }
            });
            $("input[name='size_qty[]']").on('keyup', function() {
                var size_input = $("input[name='size_qty[]']");
                var size_stock_total = 0;
                for (i = 0; i < size_input.length; i++) {
                    size_stock_total = parseInt(size_stock_total) + parseInt(size_input[i].value);
                }
                if (isNaN(size_stock_total)) {
                    $("#stock").val(null);
                } else {
                    $("#stock").val(size_stock_total);
                }

            });
            $("#size-check").change(function() {
                if ($(this).is(":checked")) {
                    var size_input = $("input[name='size_qty[]']");
                    var size_stock_total = 0;
                    for (i = 0; i < size_input.length; i++) {
                        size_stock_total = parseInt(size_stock_total) + parseInt(size_input[i].value);
                    }
                    if (isNaN(size_stock_total)) {
                        $("#stock").val(null);
                    } else {
                        $("#stock").val(size_stock_total);
                    }

                    $("#stock").attr("readonly", true);
                    $("#stock").addClass("disabled");
                } else {
                    $("#stock").attr("readonly", false);
                    $("#stock").removeClass("disabled");
                }
            });
        });
    </script>

    <script type="text/javascript">
        // Remove White Space
        function isEmpty(el) {
            return !$.trim(el.html())
        }
        // Remove White Space Ends
        // Feature Section
        $('#feature-btn').on('click', function() {
            current_feature += 1;
            $('#feature-section').append('' +
                '<div class="feature-area mb-3">' +
                '<span class="remove feature-remove"><i class="fas fa-times"></i></span>' +
                '<div class="row mb-0">' +
                '<div class="col-lg-6">' +
                '<div class="panel panel-lang">' +
                '<div class="panel-body">' +
                '<div class="tab-content">' +
                '<div role="tabpanel" class="tab-pane active" id="{{ $lang->locale }}-features' +
                current_feature + '">' +
                '<input type="text" name="{{ $lang->locale }}[features][]" class="input-field" placeholder="{{ __('Enter Your Keyword') }}">' +
                '</div>' +
                @foreach ($locales as $loc)
                    @if ($loc->locale === $lang->locale)
                        @continue
                    @endif
                    '<div role="tabpanel" class="tab-pane" id="{{ $loc->locale }}-features' + current_feature
                        +
                        '">' +
                        '<input type="text" name="{{ $loc->locale }}[features][]" class="input-field" placeholder="{{ __('Enter Your Keyword') }}">' +
                        '</div>' +
                @endforeach
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                '<div class="input-group colorpicker-component cp">' +
                '<input type="text" name="colors[]" value="#000000" class="input-field cp"/>' +
                '<span class="input-group-addon"><i></i></span>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="panel panel-lang">' +
                '<div class="panel-footer">' +
                '<ul class="nav nav-pills" role="tablist">' +
                '<li role="presentation" class="active">' +
                '<a href="#{{ $lang->locale }}-features' + current_feature +
                '" class="active" aria-controls="{{ $lang->locale }}-features' + current_feature + '"' +
                'role="tab" data-toggle="tab">' +
                '{{ $lang->language }}' +
                '</a>' +
                '</li>' +
                @foreach ($locales as $loc)
                    @if ($loc->locale === $lang->locale)
                        @continue
                    @endif
                    '<li role="presentation">' +
                    '<a href="#{{ $loc->locale }}-features' + current_feature +
                        '" aria-controls="{{ $loc->locale }}-features' + current_feature + '"' +
                        'role="tab" data-toggle="tab">' +
                        '{{ $loc->language }}' +
                        '</a>' +
                        '</li>' +
                @endforeach
                '</ul>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '');
            $('.cp').colorpicker();
        });
        $(document).on('click', '.feature-remove', function() {
            $(this.parentNode).remove();
            if (isEmpty($('#feature-section'))) {
                $('#feature-section').append('' +
                    '<div class="feature-area mb-3">' +
                    '<span class="remove feature-remove"><i class="fas fa-times"></i></span>' +
                    '<div class="row mb-0">' +
                    '<div class="col-lg-6">' +
                    '<div class="panel panel-lang">' +
                    '<div class="panel-body">' +
                    '<div class="tab-content">' +
                    '<div role="tabpanel" class="tab-pane active" id="{{ $lang->locale }}-features' +
                    current_feature + '">' +
                    '<input type="text" name="{{ $lang->locale }}[features][]" class="input-field" placeholder="{{ __('Enter Your Keyword') }}">' +
                    '</div>' +
                    @foreach ($locales as $loc)
                        @if ($loc->locale === $lang->locale)
                            @continue
                        @endif
                        '<div role="tabpanel" class="tab-pane" id="{{ $loc->locale }}-features' +
                        current_feature + '">' +
                            '<input type="text" name="{{ $loc->locale }}[features][]" class="input-field" placeholder="{{ __('Enter Your Keyword') }}">' +
                            '</div>' +
                    @endforeach
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-lg-6">' +
                    '<div class="input-group colorpicker-component cp">' +
                    '<input type="text" name="colors[]" value="#000000" class="input-field cp"/>' +
                    '<span class="input-group-addon"><i></i></span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="panel panel-lang">' +
                    '<div class="panel-footer">' +
                    '<ul class="nav nav-pills" role="tablist">' +
                    '<li role="presentation" class="active">' +
                    '<a href="#{{ $lang->locale }}-features' + current_feature +
                    '" class="active" aria-controls="{{ $lang->locale }}-features' + current_feature + '"' +
                    'role="tab" data-toggle="tab">' +
                    '{{ $lang->language }}' +
                    '</a>' +
                    '</li>' +
                    @foreach ($locales as $loc)
                        @if ($loc->locale === $lang->locale)
                            @continue
                        @endif
                        '<li role="presentation">' +
                        '<a href="#{{ $loc->locale }}-features' + current_feature +
                            '" aria-controls="{{ $loc->locale }}-features' + current_feature + '"' +
                            'role="tab" data-toggle="tab">' +
                            '{{ $loc->language }}' +
                            '</a>' +
                            '</li>' +
                    @endforeach
                    '</ul>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '');
                $('.cp').colorpicker();
            }
        });
        // Feature Section Ends
        // Gallery Section Insert
        $(document).on('click', '.remove-img', function() {
            var id = $(this).find('input[type=hidden]').val();
            $('#galval' + id).remove();
            $(this).parent().parent().remove();
        });
        $(document).on('click', '#prod_gallery', function() {
            $('#uploadgallery').click();
            $('.selected-image .row').html('');
            $('#geniusform').find('.removegal').val(0);
        });
        $("#uploadgallery").change(function() {
            var total_file = document.getElementById("uploadgallery").files.length;
            for (var i = 0; i < total_file; i++) {
                $('.selected-image .row').append('<div class="col-sm-6">' +
                    '<div class="img gallery-img">' +
                    '<span class="remove-img"><i class="fas fa-times"></i>' +
                    '<input type="hidden" value="' + i + '">' +
                    '</span>' +
                    '<a href="' + URL.createObjectURL(event.target.files[i]) + '" target="_blank">' +
                    '<img src="' + URL.createObjectURL(event.target.files[i]) + '" alt="gallery image">' +
                    '</a>' +
                    '</div>' +
                    '</div> '
                );
                $('#geniusform').append('<input type="hidden" name="galval[]" id="galval' + i +
                    '" class="removegal" value="' + i + '">')
            }
        });
        // Gallery Section Insert Ends
    </script>

    <script type="text/javascript">
        $('.cropme').simpleCropper();
        $('#crop-image').on('click', function() {
            $('.cropme').click();
        });
    </script>

    <script src="{{ asset('assets/admin/js/product.js') }}"></script>
@endsection
