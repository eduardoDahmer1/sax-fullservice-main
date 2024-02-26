@extends('layouts.admin')
@section('styles')
    <link href="{{ asset('assets/admin/css/product.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/jquery.Jcrop.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/Jcrop-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/cropper.min.css') }}" rel="stylesheet" />
@endsection
@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading"> {{ __('Edit Product') }}<a class="add-btn" href="{{ url()->previous() }}"><i
                                class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ 'Dashboard' }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Affiliate Products') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-import-index') }}">{{ __('All Products') }}</a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Edit') }}</a>
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
                            <form id="geniusform" action="{{ route('admin-import-update', $data->id) }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

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
                                            @component('admin.components.input-localized', ['required' => true, 'from' => $data])
                                                @slot('name')
                                                    name
                                                @endslot
                                                @slot('placeholder')
                                                    {{ __('Enter Product Name') }}
                                                @endslot
                                                @slot('value')
                                                    name
                                                @endslot
                                                {{ __('Product Name') }}*
                                            @endcomponent
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Product Affiliate Link') }}*
                                                <span>{{ __('(External Link)') }}</span>
                                            </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __(' Enter Product Link') }}" name="affiliate_link"
                                                required="" value="{{ $data->affiliate_link }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Category') }}*</h4>
                                            <select id="cat" name="category_id" required="">
                                                <option value="">{{ __('Select Category') }}</option>
                                                @foreach ($cats as $cat)
                                                    <option data-href="{{ route('admin-subcat-load', $cat->id) }}"
                                                        value="{{ $cat->id }}"
                                                        {{ $cat->id == $data->category_id ? 'selected' : '' }}>
                                                        {{ $cat->name }}</option>
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
                                            <input name="price" step="0.01" type="number" class="input-field"
                                                placeholder="{{ __(' e.g 20') }}"
                                                value="{{ round($data->price * $sign->value, 2) }}" required=""
                                                min="0">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Product Sku') }}* </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Enter Product Sku') }}" name="sku" required=""
                                                value="{{ $data->sku }}">
                                        </div>

                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Display in Stores') }}* </h4>
                                            @foreach ($stores as $store)
                                                <div class="row justify-content-left">
                                                    <div class="col-lg-12 d-flex justify-content-between">
                                                        <label class="control-label"
                                                            for="store{{ $store->id }}">{{ $store->title }}
                                                            | {{ $store->domain }}</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="stores[]"
                                                                id="store{{ $store->id }}" value="{{ $store->id }}"
                                                                {{ in_array($store->id, $currentStores) ? 'checked' : '' }}>
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
                                            <h4 class="heading">{{ __('Sub Category') }}</h4>
                                            <select id="subcat" name="subcategory_id">
                                                <option value="">{{ __('Select Sub Category') }}</option>
                                                @if ($data->subcategory_id == null)
                                                    @foreach ($data->category->subs as $sub)
                                                        <option data-href="{{ route('admin-childcat-load', $sub->id) }}"
                                                            value="{{ $sub->id }}">{{ $sub->name }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach ($data->category->subs as $sub)
                                                        <option data-href="{{ route('admin-childcat-load', $sub->id) }}"
                                                            value="{{ $sub->id }}"
                                                            {{ $sub->id == $data->subcategory_id ? 'selected' : '' }}>
                                                            {{ $sub->name }}</option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Child Category') }}</h4>
                                            <select id="childcat" name="childcategory_id"
                                                {{ $data->subcategory_id == null ? 'disabled' : '' }}>
                                                <option value="">{{ __('Select Child Category') }}</option>
                                                @if ($data->subcategory_id != null)
                                                    @if ($data->childcategory_id == null)
                                                        @foreach ($data->subcategory->childs as $child)
                                                            <option value="{{ $child->id }}">{{ $child->name }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        @foreach ($data->subcategory->childs as $child)
                                                            <option value="{{ $child->id }} "
                                                                {{ $child->id == $data->childcategory_id ? 'selected' : '' }}>
                                                                {{ $child->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Feature Image Source') }}*</h4>
                                            <select id="imageSource" name="image_source">
                                                <option value="file"
                                                    {{ !filter_var($data->photo, FILTER_VALIDATE_URL) ? 'selected' : '' }}>
                                                    {{ __('File') }}</option>
                                                <option value="link"
                                                    {{ filter_var($data->photo, FILTER_VALIDATE_URL) ? 'selected' : '' }}>
                                                    {{ __('Link') }}</option>
                                            </select>
                                        </div>

                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product Gallery Images') }}
                                            </h4>
                                            <a href="#" class="set-gallery-product" data-toggle="modal"
                                                data-target="#setgallery">
                                                <input type="hidden" value="{{ $data->id }}">
                                                <i class="icofont-plus"></i> {{ __('Set Gallery') }}
                                            </a>
                                        </div>

                                        <div class="checkbox-wrapper list list-personalizada">
                                            <input type="checkbox" name="product_condition_check" class="checkclick1"
                                                id="conditionCheck" value="1"
                                                {{ $data->product_condition != 0 ? 'checked' : '' }}>
                                            <label for="conditionCheck">{{ __('Allow Product Condition') }}</label>
                                        </div>

                                        <div class='{{ $data->product_condition == 0 ? 'showbox' : '' }}" input-form'>
                                            <h4 class="heading">{{ __('Product Condition') }}*</h4>
                                            <select name="product_condition">
                                                <option value="2"
                                                    {{ $data->product_condition == 2 ? 'selected' : '' }}>
                                                    {{ __('New') }}</option>
                                                <option value="1"
                                                    {{ $data->product_condition == 1 ? 'selected' : '' }}>
                                                    {{ __('Used') }}</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="col-xl-6">

                                        <div class="input-form" id="f-file"
                                            style="display:flex;flex-direction:column;align-items:center;"
                                            {!! filter_var($data->photo, FILTER_VALIDATE_URL) ? 'style="display:none"' : '' !!}>
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

                                        <div id="f-link" class="input-form" {!! !filter_var($data->photo, FILTER_VALIDATE_URL) ? 'style="display:none"' : '' !!}>
                                            <h4 class="heading">{{ __('Feature Image Link') }}*</h4>
                                            <input type="text" name="photolink" value="{{ $data->photo }}"
                                                class="input-field">
                                        </div>

                                    </div>


                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['from' => $data, 'type' => 'richtext'])
                                                @slot('name')
                                                    details
                                                @endslot
                                                @slot('value')
                                                    details
                                                @endslot
                                                {{ __('Product Description') }}
                                            @endcomponent
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['from' => $data, 'type' => 'richtext'])
                                                @slot('name')
                                                    policy
                                                @endslot
                                                @slot('value')
                                                    policy
                                                @endslot
                                                {{ __('Product Buy/Return Policy') }}
                                            @endcomponent
                                        </div>
                                    </div>

                                </div>
                                <!--FINAL DA ROW DE DADOS OPCIONAIS IMPORTANTES-->

                                <div class="title-section-form">
                                    <span>3</span>
                                    <h3>
                                        {{ __('Extra Data') }}
                                    </h3>
                                </div>

                                <div class="row">
                                    <!--COMEÇO DA ROW DE DADOS EXTRAS-->

                                    <div class="col-xl-6">
                                        <div class="form-list">
                                            <ul class="list list-personalizada">
                                                <li>
                                                    <input class="checkclick" name="shipping_time_check" type="checkbox"
                                                        id="check1" value="1"
                                                        {{ $data->ship != null ? 'checked' : '' }}>
                                                    <label
                                                        for="check1">{{ __('Allow Estimated Shipping Time') }}</label>
                                                </li>
                                            </ul>

                                            <div class='{{ $data->ship != null ? '' : 'showbox' }} input-form'>


                                                @component('admin.components.input-localized', ['from' => $data])
                                                    @slot('name')
                                                        ship
                                                    @endslot
                                                    @slot('placeholder')
                                                        {{ __('Estimated Shipping Time') }}
                                                    @endslot
                                                    @slot('value')
                                                        ship
                                                    @endslot
                                                    {{ __('Product Estimated Shipping Time') }}
                                                @endcomponent


                                            </div>
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-6-->

                                    <div class="col-xl-6">
                                        <ul class="list list-personalizada">
                                            <li>
                                                <input name="size_check" type="checkbox" id="size-check" value="1"
                                                    {{ !empty($data->size) ? 'checked' : '' }}>
                                                <label for="size-check">{{ __('Allow Product Sizes') }}</label>
                                            </li>
                                        </ul>

                                        <div class='{{ !empty($data->size) ? '' : 'showbox' }} input-form'
                                            id="size-display">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="product-size-details" id="size-section">
                                                        @if (!empty($data->size))
                                                            @foreach ($data->size as $key => $data1)
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
                                                                                placeholder="{{ __('Size Name') }}"
                                                                                value="{{ $data->size[$key] }}">
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
                                                                                placeholder="{{ __('Size Qty') }}"
                                                                                min="1"
                                                                                value="{{ $data->size_qty[$key] }}">
                                                                        </div>
                                                                        <div class="col-md-4 col-sm-6">
                                                                            <label>
                                                                                {{ __('Size Price') }} :
                                                                                <span>
                                                                                    {{ __('(This price will be added with base price)') }}
                                                                                </span>
                                                                            </label>
                                                                            <input type="number" step="0.01"
                                                                                name="size_price[]" class="input-field"
                                                                                placeholder="{{ __('Size Price') }}"
                                                                                min="0"
                                                                                value="{{ $data->size_price[$key] }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
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
                                                                            placeholder="{{ __('Size Name') }}">
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
                                                                            placeholder="{{ __('Size Qty') }}"
                                                                            value="1" min="1">
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-6">
                                                                        <label>
                                                                            {{ __('Size Price') }} :
                                                                            <span>
                                                                                {{ __('(This price will be added with base price)') }}
                                                                            </span>
                                                                        </label>
                                                                        <input type="number" step="0.01"
                                                                            name="size_price[]" class="input-field"
                                                                            placeholder="{{ __('Size Price') }}"
                                                                            value="0" min="0">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <a href="javascript:;" id="size-btn" class="add-more"><i
                                                            class="fas fa-plus"></i>{{ __('Add More Size') }} </a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!--FECHAMENTO COL-XL-6-->

                                    <div class="col-xl-6">
                                        <ul class="list list-personalizada">
                                            <li>
                                                <input class="checkclick" name="color_check" type="checkbox"
                                                    id="check3" value="1"
                                                    {{ !empty($data->color) ? 'checked' : '' }}>
                                                <label for="check3">{{ __('Allow Product Colors') }}</label>
                                            </li>
                                        </ul>
                                        <div class='{{ !empty($data->color) ? '' : 'showbox' }}'>

                                            <div class="row">

                                                @if (!empty($data->color))
                                                    <div class="col-12">
                                                        <div class="input-form">
                                                            <h4 class="heading">
                                                                {{ __('Product Colors') }}
                                                            </h4>
                                                            <p class="sub-heading">
                                                                {{ __('(Choose Your Favorite Colors)') }}
                                                            </p>
                                                            <div class="select-input-color" id="color-section">
                                                                @foreach ($data->color as $key => $data1)
                                                                    <div class="color-area">
                                                                        <span class="remove color-remove"><i
                                                                                class="fas fa-times"></i></span>
                                                                        <div class="input-group colorpicker-component cp">
                                                                            <input type="text" name="color[]"
                                                                                value="{{ $data->color[$key] }}"
                                                                                class="input-field cp" />
                                                                            <span class="input-group-addon"><i></i></span>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <a href="javascript:;" id="color-btn"
                                                                class="add-more mt-4 mb-3"><i
                                                                    class="fas fa-plus"></i>{{ __('Add More Color') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-12">
                                                        <div class="input-form">
                                                            <h4 class="heading">
                                                                {{ __('Product Colors') }}
                                                            </h4>
                                                            <p class="sub-heading">
                                                                {{ __('(Choose Your Favorite Colors)') }}
                                                            </p>
                                                            <div class="select-input-color" id="color-section">
                                                                <div class="color-area">
                                                                    <span class="remove color-remove"><i
                                                                            class="fas fa-times"></i></span>
                                                                    <div class="input-group colorpicker-component cp">
                                                                        <input type="text" name="color[]"
                                                                            value="#000000" class="input-field cp" />
                                                                        <span class="input-group-addon"><i></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <a href="javascript:;" id="color-btn"
                                                                class="add-more mt-4 mb-3"><i
                                                                    class="fas fa-plus"></i>{{ __('Add More Color') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>

                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-6-->

                                    <div class="col-xl-6">

                                        <div class="checkbox-wrapper list list-personalizada">
                                            <input type="checkbox" name="measure_check" class="checkclick1"
                                                id="allowProductMeasurement" value="1"
                                                {{ $data->measure == null ? '' : 'checked' }}>
                                            <label
                                                for="allowProductMeasurement">{{ __('Allow Product Measurement') }}</label>
                                        </div>

                                        <div class="{{ $data->measure == null ? 'showbox' : '' }} input-form">

                                            <h4 class="heading">{{ __('Product Measurement') }}</h4>
                                            <select id="product_measure">
                                                <option value="" {{ $data->measure == null ? 'selected' : '' }}>
                                                    {{ __('None') }}
                                                </option>
                                                <option value="Gram" {{ $data->measure == 'Gram' ? 'selected' : '' }}>
                                                    {{ __('Gram') }}</option>
                                                <option value="Kilogram"
                                                    {{ $data->measure == 'Kilogram' ? 'selected' : '' }}>
                                                    {{ __('Kilogram') }}</option>
                                                <option value="Litre" {{ $data->measure == 'Litre' ? 'selected' : '' }}>
                                                    {{ __('Litre') }}</option>
                                                <option value="Pound" {{ $data->measure == 'Pound' ? 'selected' : '' }}>
                                                    {{ __('Pound') }}</option>
                                                <option value="Custom"
                                                    {{ in_array($data->measure, explode(',', 'Gram,Kilogram,Litre,Pound')) ? '' : 'selected' }}>
                                                    {{ __('Custom') }}</option>
                                            </select>
                                            <div class="{{ in_array($data->measure, explode(',', 'Gram,Kilogram,Litre,Pound')) ? 'hidden' : '' }}"
                                                id="measure">
                                                <input name="measure" type="text" id="measurement"
                                                    class="input-field" placeholder="{{ __('Enter Unit') }}"
                                                    value="{{ $data->measure }}">
                                            </div>

                                        </div>

                                    </div>
                                    <!--FINAL ROW COL-XL-6-->

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product Previous Price') }}
                                                <span>{{ __('(Optional)') }}</span>
                                            </h4>
                                            <input name="previous_price" step="0.01" type="number"
                                                class="input-field" placeholder="{{ __(' e.g 20') }}"
                                                value="{{ round($data->previous_price * $sign->value, 2) }}"
                                                min="0">
                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-6-->

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Product Stock') }}
                                                <span>
                                                    {{ __('(Leave Empty will Show Always Available)') }}
                                                </span>
                                            </h4>
                                            <input name="stock" type="text" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" value="{{ $data->stock }}">

                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="title-section-form"></div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="featured-keyword-area input-form">
                                            <h4 class="heading">
                                                {{ __('Feature Tags') }}
                                            </h4>

                                            <div class="feature-tag-top-filds" id="feature-section">
                                                @php
                                                    $currentFeature = 0;
                                                @endphp

                                                @if (!empty($data->features))
                                                    @foreach ($data->features as $key => $data1)
                                                        @php
                                                            $currentFeature += 1;
                                                        @endphp

                                                        <div class="feature-area mb-3">
                                                            <span class="remove feature-remove"><i
                                                                    class="fas fa-times"></i></span>
                                                            <div class="row mb-0">
                                                                <div class="col-lg-6">
                                                                    <div class="panel panel-lang">
                                                                        <div class="panel-body">
                                                                            <div class="tab-content">
                                                                                <div role="tabpanel"
                                                                                    class="tab-pane active"
                                                                                    id="{{ $lang->locale }}-features{{ $currentFeature }}">
                                                                                    <input type="text"
                                                                                        name="{{ $lang->locale }}[features][]"
                                                                                        class="input-field"
                                                                                        placeholder="{{ __('Enter Your Keyword') }}"
                                                                                        value="{{ $data->features[$key] }}">
                                                                                </div>
                                                                                @foreach ($locales as $loc)
                                                                                    @if ($loc->locale === $lang->locale)
                                                                                        @continue
                                                                                    @endif
                                                                                    @php
                                                                                        $transFeature = explode(',', $data->translate($loc->locale)->features);
                                                                                    @endphp
                                                                                    <div role="tabpanel" class="tab-pane"
                                                                                        id="{{ $loc->locale }}-features{{ $currentFeature }}">
                                                                                        <input type="text"
                                                                                            name="{{ $loc->locale }}[features][]"
                                                                                            class="input-field"
                                                                                            placeholder="{{ __('Enter Your Keyword') }}"
                                                                                            value="{{ $transFeature[$key] ?? '' }}">
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6">
                                                                    <div class="input-group colorpicker-component cp">
                                                                        <input type="text" name="colors[]"
                                                                            value="{{ $data->colors[$key] }}"
                                                                            class="input-field cp" />
                                                                        <span class="input-group-addon"><i></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel panel-lang">
                                                                <div class="panel-footer">
                                                                    <ul class="nav nav-pills" role="tablist">
                                                                        <li role="presentation" class="active">
                                                                            <a href="#{{ $lang->locale }}-features{{ $currentFeature }}"
                                                                                class="active"
                                                                                aria-controls="{{ $lang->locale }}-features{{ $currentFeature }}"
                                                                                role="tab" data-toggle="tab">
                                                                                {{ $lang->language }}
                                                                            </a>
                                                                        </li>
                                                                        @foreach ($locales as $loc)
                                                                            @if ($loc->locale === $lang->locale)
                                                                                @continue
                                                                            @endif
                                                                            <li role="presentation">
                                                                                <a href="#{{ $loc->locale }}-features{{ $currentFeature }}"
                                                                                    aria-controls="{{ $loc->locale }}-features{{ $currentFeature }}"
                                                                                    role="tab" data-toggle="tab">
                                                                                    {{ $loc->language }}
                                                                                </a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="feature-area mb-3">
                                                        <span class="remove feature-remove"><i
                                                                class="fas fa-times"></i></span>
                                                        <div class="row mb-0">
                                                            <div class="col-lg-6">
                                                                <div class="panel panel-lang">
                                                                    <div class="panel-body">
                                                                        <div class="tab-content">
                                                                            <div role="tabpanel" class="tab-pane active"
                                                                                id="{{ $lang->locale }}-features{{ $currentFeature }}">
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
                                                                                    id="{{ $loc->locale }}-features{{ $currentFeature }}">
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
                                                                        <a href="#{{ $lang->locale }}-features{{ $currentFeature }}"
                                                                            class="active"
                                                                            aria-controls="{{ $lang->locale }}-features{{ $currentFeature }}"
                                                                            role="tab" data-toggle="tab">
                                                                            {{ $lang->language }}
                                                                        </a>
                                                                    </li>
                                                                    @foreach ($locales as $loc)
                                                                        @if ($loc->locale === $lang->locale)
                                                                            @continue
                                                                        @endif
                                                                        <li role="presentation">
                                                                            <a href="#{{ $loc->locale }}-features{{ $currentFeature }}"
                                                                                aria-controls="{{ $loc->locale }}-features{{ $currentFeature }}"
                                                                                role="tab" data-toggle="tab">
                                                                                {{ $loc->language }}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <a href="javascript:;" id="feature-btn" class="add-fild-btn"><i
                                                    class="icofont-plus"></i> {{ __('Add More Field') }}</a>
                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-6-->

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Youtube Video URL') }}
                                                <span>
                                                    {{ __('(Optional)') }}
                                                </span>
                                            </h4>
                                            <input name="youtube" type="text" class="input-field"
                                                placeholder="{{ __('Enter Youtube Video URL') }}"
                                                value="{{ $data->youtube }}">
                                        </div>



                                        <div class="checkbox-wrapper list list-personalizada">
                                            <input type="checkbox" name="seo_check" value="1" class="checkclick1"
                                                id="allowProductSEO" value="1"
                                                {{ $data->meta_tag != null || strip_tags($data->meta_description) != null ? 'checked' : '' }}>
                                            <label for="allowProductSEO">{{ __('Allow Product SEO') }}</label>
                                        </div>

                                        <div
                                            class="{{ $data->meta_tag == null && strip_tags($data->meta_description) == null
                                                ? "
                                                                                                                                                                                										showbox"
                                                : '' }}">

                                            <div class="input-form">
                                                @component('admin.components.input-localized', ['from' => $data, 'type' => 'tags'])
                                                    @slot('name')
                                                        meta_tag
                                                    @endslot
                                                    @slot('value')
                                                        meta_tag
                                                    @endslot
                                                    {{ __('Meta Tags') }}
                                                @endcomponent
                                            </div>

                                            <div class="input-form">
                                                @component('admin.components.input-localized', ['from' => $data, 'type' => 'textarea'])
                                                    @slot('name')
                                                        meta_description
                                                    @endslot
                                                    @slot('placeholder')
                                                        {{ __('Details') }}
                                                    @endslot
                                                    @slot('value')
                                                        meta_description
                                                    @endslot
                                                    {{ __('Meta Description') }}
                                                @endcomponent
                                            </div>
                                        </div>


                                    </div>
                                    <!--FINAL ROW COL-XL-6-->


                                </div>
                                <!--FINAL DA ROW DE DADOS EXTRAS-->

                                <input type="hidden" name="type" value="Physical">
                                <div class="row">
                                    <div class="col-xl-12 text-center">
                                        <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
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
                                    <form method="POST" enctype="multipart/form-data" id="form-gallery">
                                        {{ csrf_field() }}
                                        <input type="hidden" id="pid" name="product_id" value="">
                                        <input type="file" name="gallery[]" class="hidden" id="uploadgallery"
                                            accept="image/*" multiple>
                                        <label for="image-upload" id="prod_gallery"><i
                                                class="icofont-upload-alt"></i>{{ __('Upload File') }}</label>
                                    </form>
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
    <script>
        var current_feature = {{ $currentFeature }};
    </script>

    <script type="text/javascript">
        // Remove White Space
        function isEmpty(el) {
            return !$.trim(el.html())
        }
        // Remove White Space Ends

        // Feature Section

        $("#feature-btn").on('click', function() {

            current_feature += 1;

            $("#feature-section").append('' +
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

                $("#feature-section").append('' +
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

        // Gallery Section Update

        $(document).on("click", ".set-gallery", function() {
            var pid = $(this).find('input[type=hidden]').val();
            $('#pid').val(pid);
            $('.selected-image .row').html('');
            $.ajax({
                type: "GET",
                url: "{{ route('admin-gallery-show') }}",
                data: {
                    id: pid
                },
                success: function(data) {
                    if (data[0] == 0) {
                        $('.selected-image .row').addClass('justify-content-center');
                        $('.selected-image .row').html('<h3>{{ __('No Images Found.') }}</h3>');
                    } else {
                        $('.selected-image .row').removeClass('justify-content-center');
                        $('.selected-image .row h3').remove();
                        var arr = $.map(data[1], function(el) {
                            return el
                        });

                        for (var k in arr) {
                            $('.selected-image .row').append('<div class="col-sm-6">' +
                                '<div class="img gallery-img">' +
                                '<span class="remove-img"><i class="fas fa-times"></i>' +
                                '<input type="hidden" value="' + arr[k]['id'] + '">' +
                                '</span>' +
                                '<a href="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                arr[
                                    k]['photo'] + '" target="_blank">' +
                                '<img src="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                arr[k]['photo'] + '" alt="gallery image">' +
                                '</a>' +
                                '</div>' +
                                '</div>');
                        }
                    }

                }
            });
        });

        $(document).on('click', '.remove-img', function() {
            var id = $(this).find('input[type=hidden]').val();
            $(this).parent().parent().remove();
            $.ajax({
                type: "GET",
                url: "{{ route('admin-gallery-delete') }}",
                data: {
                    id: id
                }
            });
        });

        $(document).on('click', '#prod_gallery', function() {
            $('#uploadgallery').click();
        });

        $("#uploadgallery").change(function() {
            $("#form-gallery").submit();
        });

        $(document).on('submit', '#form-gallery', function() {
            $.ajax({
                url: "{{ route('admin-gallery-store') }}",
                method: "POST",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data != 0) {
                        $('.selected-image .row').removeClass('justify-content-center');
                        $('.selected-image .row h3').remove();
                        var arr = $.map(data, function(el) {
                            return el
                        });
                        for (var k in arr) {
                            $('.selected-image .row').append('<div class="col-sm-6">' +
                                '<div class="img gallery-img">' +
                                '<span class="remove-img"><i class="fas fa-times"></i>' +
                                '<input type="hidden" value="' + arr[k]['id'] + '">' +
                                '</span>' +
                                '<a href="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                arr[
                                    k]['photo'] + '" target="_blank">' +
                                '<img src="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                arr[k]['photo'] + '" alt="gallery image">' +
                                '</a>' +
                                '</div>' +
                                '</div>');
                        }
                    }

                }

            });
            return false;
        });

        // Gallery Section Update Ends
    </script>

    <script src="{{ asset('assets/admin/js/jquery.Jcrop.js') }}"></script>
    <script src="{{ asset('assets/admin/js/cropper.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.SimpleCropper.js') }}"></script>

    <script type="text/javascript">
        $('.cropme').simpleCropper();
        $('#crop-image').on('click', function() {
            $('.cropme').click();
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            let html =
                `<img src="{{ empty($data->photo) ? asset('assets/images/noimage.png') : (filter_var($data->photo, FILTER_VALIDATE_URL) ? $data->photo : asset('storage/images/products/' . $data->photo)) }}" alt="">`;
            $(".span4.cropme").html(html);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });

        $('.ok').on('click', function() {

            setTimeout(
                function() {

                    var img = $('#feature_photo').val();

                    $.ajax({
                        url: "{{ route('admin-prod-upload-update', $data->id) }}",
                        type: "POST",
                        data: {
                            "image": img
                        },
                        success: function(data) {
                            if (data.status) {
                                $('#feature_photo').val(data.file_name);
                            }
                            if ((data.errors)) {
                                for (var error in data.errors) {
                                    $.notify(data.errors[error], "danger");
                                }
                            }
                        }
                    });

                }, 1000);

        });
    </script>

    <script type="text/javascript">
        $('#imageSource').on('change', function() {
            var file = this.value;
            if (file == "file") {
                $('#f-file').show();
                $('#f-link').hide();
                $('#f-link').find('input').prop('required', false);
            }
            if (file == "link") {
                $('#f-file').hide();
                $('#f-link').show();
                $('#f-link').find('input').prop('required', true);
            }
        });
    </script>

    <script src="{{ asset('assets/admin/js/product.js') }}"></script>
@endsection
