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
                    <h4 class="heading"> {{ __('Edit Product') }} <a class="add-btn"
                            href="{{ route('vendor-prod-index') }}"><i class="fas fa-arrow-left"></i>
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
                            <a href="{{ route('vendor-prod-edit', $data->id) }}">{{ __('Edit') }}</a>
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
                            <form id="geniusform" action="{{ route('vendor-prod-update', $data->id) }}" method="POST"
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
                                            <h4 class="heading">{{ __('Category') }}*</h4>
                                            <select id="cat" name="category_id" required>
                                                <option>{{ __('Select Category') }}</option>

                                                @foreach ($cats as $cat)
                                                    <option data-href="{{ route('vendor-subcat-load', $cat->id) }}"
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
                                            <input name="price" step="0.1" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}"
                                                value="{{ round($data->price * $sign->value, 2) }}" required=""
                                                min="0">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Ref Code') }}* </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Enter Product Code') }}" name="ref_code" required=""
                                                value="{{ $data->ref_code }}">
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
                                                                {{ in_array($store->id, $currentStores) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        {{-- Attributes of category starts --}}
                                        <div id="catAttributes">
                                            @if (!empty($catAttributes))
                                                @foreach ($catAttributes as $catAttribute)
                                                    <div class="input-form">
                                                        <div class="row">
                                                            <div class="col-xl-12">
                                                                <h3 class="heading">{{ __('Category attribute') }}</h3>
                                                                <h4 class="heading"
                                                                    style="font-weight:bold;padding-top:1rem;">
                                                                    {{ $catAttribute->name }}</h4>
                                                            </div>
                                                            <div class="col-xl-12">
                                                                @foreach ($catAttribute->attribute_options as $catOption)
                                                                    @php
                                                                        $catOptChecked = is_array($selectedAttrs) && array_key_exists($catAttribute->input_name, $selectedAttrs) && is_array($selectedAttrs[$catAttribute->input_name]['values']) && in_array($catOption->id, $selectedAttrs[$catAttribute->input_name]['values']);

                                                                        $catOptPrice = !empty($selectedAttrs[$catAttribute->input_name]['prices'][$loop->index]) && $catOptChecked ? $selectedAttrs[$catAttribute->input_name]['prices'][$loop->index] : '';
                                                                    @endphp

                                                                    <div class="option-row"
                                                                        style="display:flex;justify-content:start;align-items:center;">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                id="{{ $catAttribute->input_name }}{{ $catOption->id }}"
                                                                                name="attr_{{ $catAttribute->input_name }}[]"
                                                                                value="{{ $catOption->id }}"
                                                                                class="custom-control-input attr-checkbox"
                                                                                {{ $catOptChecked ? 'checked' : '' }}>
                                                                            <label class="custom-control-label"
                                                                                for="{{ $catAttribute->input_name }}{{ $catOption->id }}">
                                                                                {{ $catOption->name }}
                                                                            </label>
                                                                        </div>

                                                                        <div class="{{ $catAttribute->price_status == 0 ? ' d-none' : '' }}"
                                                                            style="display:flex;justify-content:center;align-items:center;min-width:220px;">
                                                                            <span style="padding:0 1rem;">+</span>
                                                                            <div class="price-container">
                                                                                <span
                                                                                    class="price-curr">{{ $sign->sign }}</span>
                                                                                <input type="number" step="0.01"
                                                                                    class="input-field price-input"
                                                                                    id="{{ $catAttribute->input_name }}{{ $catOption->id }}_price"
                                                                                    data-name="attr_{{ $catAttribute->input_name }}_price[]"
                                                                                    placeholder="0.00 (Additional Price)"
                                                                                    value="{{ $catOptPrice }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        {{-- Attributes of category ends --}}

                                        {{-- Attributes of subcategory starts --}}
                                        <div id="subcatAttributes">
                                            @if (!empty($subAttributes))
                                                @foreach ($subAttributes as $subcatAttribute)
                                                    <div class="input-form">
                                                        <div class="row">
                                                            <div class="col-xl-12">
                                                                <h3 class="heading">{{ __('Subcategory attribute') }}</h3>
                                                                <h4 class="heading"
                                                                    style="font-weight:bold;padding-top:1rem;">
                                                                    {{ $subcatAttribute->name }}</h4>
                                                            </div>
                                                            <div class="col-xl-12">
                                                                @foreach ($subcatAttribute->attribute_options as $subcatOption)
                                                                    @php
                                                                        $subcatOptChecked = is_array($selectedAttrs) && array_key_exists($subcatAttribute->input_name, $selectedAttrs) && is_array($selectedAttrs[$subcatAttribute->input_name]['values']) && in_array($subcatOption->id, $selectedAttrs[$subcatAttribute->input_name]['values']);

                                                                        $subcatOptPrice = !empty($selectedAttrs[$subcatAttribute->input_name]['prices'][$loop->index]) && $subcatOptChecked ? $selectedAttrs[$subcatAttribute->input_name]['prices'][$loop->index] : '';
                                                                    @endphp

                                                                    <div class="option-row"
                                                                        style="display:flex;justify-content:start;align-items:center;">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                id="{{ $subcatAttribute->input_name }}{{ $subcatOption->id }}"
                                                                                name="attr_{{ $subcatAttribute->input_name }}[]"
                                                                                value="{{ $subcatOption->id }}"
                                                                                class="custom-control-input attr-checkbox"
                                                                                {{ $subcatOptChecked ? 'checked' : '' }}>
                                                                            <label class="custom-control-label"
                                                                                for="{{ $subcatAttribute->input_name }}{{ $subcatOption->id }}">
                                                                                {{ $subcatOption->name }}
                                                                            </label>
                                                                        </div>

                                                                        <div class="{{ $subcatAttribute->price_status == 0 ? ' d-none' : '' }}"
                                                                            style="display:flex;justify-content:center;align-items:center;min-width:220px;">
                                                                            <span style="padding:0 1rem;">+</span>
                                                                            <div class="price-container">
                                                                                <span
                                                                                    class="price-curr">{{ $sign->sign }}</span>
                                                                                <input type="number" step="0.01"
                                                                                    class="input-field price-input"
                                                                                    id="{{ $subcatAttribute->input_name }}{{ $subcatOption->id }}_price"
                                                                                    data-name="attr_{{ $subcatAttribute->input_name }}_price[]"
                                                                                    placeholder="0.00 (Additional Price)"
                                                                                    value="{{ $subcatOptPrice }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        {{-- Attributes of subcategory ends --}}

                                        {{-- Attributes of child category starts --}}
                                        <div id="childcatAttributes">
                                            @if (!empty($childAttributes))
                                                @foreach ($childAttributes as $childcatAttribute)
                                                    <div class="input-form">
                                                        <div class="row">
                                                            <div class="col-xl-12">
                                                                <h3 class="heading">{{ __('Child category attribute') }}
                                                                </h3>
                                                                <h4 class="heading"
                                                                    style="font-weight:bold;padding-top:1rem;">
                                                                    {{ $childcatAttribute->name }}</h4>
                                                            </div>
                                                            <div class="col-xl-12">
                                                                @foreach ($childcatAttribute->attribute_options as $childcatOption)
                                                                    @php
                                                                        $childcatOptChecked = is_array($selectedAttrs) && array_key_exists($childcatAttribute->input_name, $selectedAttrs) && is_array($selectedAttrs[$childcatAttribute->input_name]['values']) && in_array($childcatOption->id, $selectedAttrs[$childcatAttribute->input_name]['values']);

                                                                        $childcatOptPrice = !empty($selectedAttrs[$childcatAttribute->input_name]['prices'][$loop->index]) && $childcatOptChecked ? $selectedAttrs[$childcatAttribute->input_name]['prices'][$loop->index] : '';
                                                                    @endphp

                                                                    <div class="option-row"
                                                                        style="display:flex;justify-content:start;align-items:center;">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                id="{{ $childcatAttribute->input_name }}{{ $childcatOption->id }}"
                                                                                name="attr_{{ $childcatAttribute->input_name }}[]"
                                                                                value="{{ $childcatOption->id }}"
                                                                                class="custom-control-input attr-checkbox"
                                                                                {{ $childcatOptChecked ? 'checked' : '' }}>
                                                                            <label class="custom-control-label"
                                                                                for="{{ $childcatAttribute->input_name }}{{ $childcatOption->id }}">
                                                                                {{ $childcatOption->name }}
                                                                            </label>
                                                                        </div>

                                                                        <div class="{{ $childcatAttribute->price_status == 0 ? ' d-none' : '' }}"
                                                                            style="display:flex;justify-content:center;align-items:center;min-width:220px;">
                                                                            <span style="padding:0 1rem;">+</span>
                                                                            <div class="price-container">
                                                                                <span
                                                                                    class="price-curr">{{ $sign->sign }}</span>
                                                                                <input type="number" step="0.01"
                                                                                    class="input-field price-input"
                                                                                    id="{{ $childcatAttribute->input_name }}{{ $childcatOption->id }}_price"
                                                                                    data-name="attr_{{ $childcatAttribute->input_name }}_price[]"
                                                                                    placeholder="0.00 (Additional Price)"
                                                                                    value="{{ $childcatOptPrice }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        {{-- Attributes of child category ends --}}

                                    </div>

                                </div>
                                <!--FINAL DA ROW DE DADOS OBRIGATÓRIOS-->

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
                                                        value="{{ $brand->id }}"
                                                        {{ $brand->id == $data->brand_id ? 'selected' : '' }}>
                                                        {{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Model Number') }} </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Enter Product Model Number') }}" name="mpn"
                                                required value="{{ $data->mpn }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Sub Category') }}*</h4>
                                            <select id="subcat" name="subcategory_id">
                                                <option value="">{{ __('Select Sub Category') }}</option>
                                                @if ($data->subcategory_id == null)
                                                    @foreach ($data->category->subs as $sub)
                                                        <option data-href="{{ route('vendor-childcat-load', $sub->id) }}"
                                                            value="{{ $sub->id }}">{{ $sub->name }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach ($data->category->subs as $sub)
                                                        <option data-href="{{ route('vendor-childcat-load', $sub->id) }}"
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
                                            <h4 class="heading">{{ __('Child Category') }}*</h4>
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
                                        <div class="input-form"
                                            style="display:flex;flex-direction:column;align-items:center;">
                                            <h4 class="heading">{{ __('Feature Image') }} </h4>
                                            <div class="row">
                                                <div class="panel panel-body">
                                                    @if (strpos($data->photo, 'images/noimage.png') == false)
                                                        <div class="buttons">
                                                            <div class="deleteImage"
                                                                onclick="deleteImage({{ $data->id }})"></div>
                                                        </div>
                                                    @endif
                                                    <div class="span4 cropme text-center" id="landscape"
                                                        style="width: 400px; height: 400px; border: 1px dashed black;">
                                                    </div>
                                                </div>
                                            </div>

                                            <a href="javascript:;" id="crop-image" class="d-inline-block mybtn1">
                                                <i class="icofont-upload-alt"></i> {{ __('Upload Image Here') }}
                                            </a>

                                        </div>
                                    </div>

                                    <input type="hidden" id="feature_photo" name="photo"
                                        value="{{ $data->photo }}" accept="image/*">

                                    <div class="col-xl-6">

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

                                        <div class='input-form {{ $data->ship != null ? '' : 'showbox' }}'>
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
                                                <input class="checkclick1" name="shipping_time_check" type="checkbox"
                                                    id="check1" value="1"
                                                    {{ $data->ship != null ? 'checked' : '' }}>
                                                <label for="check1">{{ __('Allow Estimated Shipping Time') }}</label>
                                            </ul>

                                            <div class='input-form {{ $data->ship != null ? '' : 'showbox' }}'>

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

                                        <div class='input-form {{ $data->size != null ? '' : 'showbox' }}'
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
                                                                                value="{{ $data->size_qty[$key] }}"
                                                                                value="1" min="1" required>
                                                                        </div>
                                                                        <div class="col-md-4 col-sm-6">
                                                                            <label>
                                                                                {{ __('Size Price') }} :
                                                                                <span>
                                                                                    {{ __('(This price will be added with base
                                                                                                                                                            price)') }}
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
                                                                            value="1" min="1" required>
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-6">
                                                                        <label>
                                                                            {{ __('Size Price') }} :
                                                                            <span>
                                                                                {{ __('(This price will be added with base
                                                                                                                                                        price)') }}
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
                                        <div class="form-list">
                                            <ul class="list list-personalizada">
                                                <li>
                                                    <input class="checkclick" name="color_check" type="checkbox"
                                                        id="check3" value="1"
                                                        {{ empty($data->color) ? '' : 'checked' }}>
                                                    <label for="check3">{{ __('Allow Product Colors') }}</label>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class='{{ !empty($data->color) ? '' : 'showbox' }}'>

                                            <div class="row">
                                                <div class="col-12">
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
                                                            @foreach ((array) $data->color as $key => $data1)
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="select-input-color">
                                                                            <div class="color-area">
                                                                                <div
                                                                                    class="input-group colorpicker-component cp">
                                                                                    <input type="text" name="color[]"
                                                                                        value="{{ !empty($data1)
                                                                                            ? $data1
                                                                                            : "
                                                                                                                                                                        #000000" }}"
                                                                                        class="input-field cp" />
                                                                                    <span
                                                                                        class="input-group-addon"><i></i></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="number" name="color_qty[]"
                                                                            class="input-field"
                                                                            placeholder="{{ __('Color Qty') }}"
                                                                            value="{{ isset($data->color_qty[$key])
                                                                                ? $data->color_qty[$key]
                                                                                : "
                                                                                                                                                0" }}"
                                                                            min="0" required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="number" step="0.01"
                                                                            name="color_price[]" class="input-field"
                                                                            placeholder="{{ __('Color Price') }}"
                                                                            value="{{ isset($data->color_price[$key])
                                                                                ? $data->color_price[$key]
                                                                                : "
                                                                                                                                                0" }}"
                                                                            min="0" required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <button type="button"
                                                                            class="btn btn-danger text-white color-remove"><i
                                                                                class="fa fa-times"></i></button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        <a href="javascript:;" id="color-btn"
                                                            class="add-more mt-4 mb-3"><i
                                                                class="fas fa-plus"></i>{{ __('Add More Color') }} </a>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-6-->

                                    <div class="col-xl-6">
                                        <div class="form-list">
                                            <ul class="list list-personalizada">
                                                <li>
                                                    <input class="checkclick" name="whole_check" type="checkbox"
                                                        id="whole_check" value="1"
                                                        {{ !empty($data->whole_sell_qty) ? 'checked' : '' }}>
                                                    <label for="whole_check">{{ __('Allow Product Whole Sell') }}</label>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class='{{ !empty($data->whole_sell_qty) ? '' : 'showbox' }}'>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="featured-keyword-area input-form">
                                                        <div class="feature-tag-top-filds" id="whole-section">
                                                            @if (!empty($data->whole_sell_qty))
                                                                @foreach ($data->whole_sell_qty as $key => $data1)
                                                                    <div class="feature-area">
                                                                        <span class="remove whole-remove"><i
                                                                                class="fas fa-times"></i></span>
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <input type="number"
                                                                                    name="whole_sell_qty[]"
                                                                                    class="input-field"
                                                                                    placeholder="{{ __('Enter Quantity') }}"
                                                                                    min="0"
                                                                                    value="{{ $data->whole_sell_qty[$key] }}"
                                                                                    required="">
                                                                            </div>

                                                                            <div class="col-lg-6">
                                                                                <input type="number"
                                                                                    name="whole_sell_discount[]"
                                                                                    class="input-field"
                                                                                    placeholder="{{ __('Enter Discount Percentage') }}"
                                                                                    min="0"
                                                                                    value="{{ $data->whole_sell_discount[$key] }}"
                                                                                    required="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
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
                                                                            <input type="number"
                                                                                name="whole_sell_discount[]"
                                                                                class="input-field"
                                                                                placeholder="{{ __('Enter Discount Percentage') }}"
                                                                                min="0" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <a href="javascript:;" id="whole-btn" class="add-more"><i
                                                                class="icofont-plus"></i> {{ __('Add More Field') }}</a>
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
                                                placeholder="{{ __('e.g 20') }}" step="1" min="0"
                                                value="{{ $data->max_quantity }}">
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
                                                placeholder="{{ __('e.g 20') }}" step="0.01" min="0"
                                                value="{{ $data->weight }}">
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-3-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product Width') }}
                                                <span>
                                                    (Cm.)
                                                </span>
                                            </h4>
                                            <input name="width" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="0"
                                                value="{{ $data->width }}">
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-3-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product Height') }}
                                                <span>
                                                    (Cm.)
                                                </span>
                                            </h4>
                                            <input name="height" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="0"
                                                value="{{ $data->height }}">
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-3-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product Length') }}
                                                <span>
                                                    (Cm.)
                                                </span>
                                            </h4>
                                            <input name="length" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="0"
                                                value="{{ $data->length }}">
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-3-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Product Previous Price') }}*
                                                <span>
                                                    {{ __('(Optional)') }}
                                                </span>
                                            </h4>
                                            <input name="previous_price" step="0.1" type="number"
                                                class="input-field" placeholder="{{ __('e.g 20') }}"
                                                value="{{ round($data->previous_price * $sign->value, 2) }}"
                                                min="0">
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-3-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Product Stock') }}*
                                                <span>
                                                    {{ __('(Leave Empty will Show Always Available)') }}
                                                </span>
                                            </h4>
                                            <input name="stock" type="text" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" value="{{ $data->stock }}">
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-3-->

                                    <div class="col-xl-3">
                                        <div class="checkbox-wrapper list list-personalizada">
                                            <input type="checkbox" name="measure_check" class="checkclick1"
                                                id="allowProductMeasurement" value="1"
                                                {{ $data->measure == null ? '' : 'checked' }}>
                                            <label
                                                for="allowProductMeasurement">{{ __('Allow Product Measurement') }}</label>
                                        </div>

                                        <div class="{{ $data->measure == null ? 'showbox' : '' }} input-form">
                                            <h4 class="heading">{{ __('Product Measurement') }}*</h4>
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
                                        </div>
                                        <div class="hidden" id="measure">
                                            <input name="measure" type="text" id="measurement" class="input-field"
                                                placeholder="{{ __('Enter Unit') }}" value="{{ $data->measure }}">
                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-3-->

                                    <div class="col-xl-12">
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
                                                        @php
                                                            $currentFeature = 0;
                                                            $featureCount = !empty($data['features']) ? count($data['features']) : 0;
                                                        @endphp

                                                        @php//dd($data['features'])
                                                        @endphp

                                                        <div class="feature-area mb-3">
                                                            <div class="row mb-0">
                                                                <div class="col-lg-6">
                                                                    <div class="panel panel-lang">
                                                                        <div class="panel-body">
                                                                            <div class="tab-content">
                                                                                <div role="tabpanel"
                                                                                    class="tab-pane active"
                                                                                    id="{{ $lang->locale }}-features0">
                                                                                    <input type="text"
                                                                                        name="{{ $lang->locale }}[features][]"
                                                                                        class="input-field"
                                                                                        placeholder="{{ __('Enter Your Keyword') }}"
                                                                                        value="{{ isset($data['features'][0]) ? $data['features'][0] : '' }}">
                                                                                </div>
                                                                                @foreach ($locales as $loc)
                                                                                    @if ($loc->locale === $lang->locale)
                                                                                        @continue
                                                                                    @endif
                                                                                    @php
                                                                                        $transFeature = explode(',', $data->translate($loc->locale)['features']);
                                                                                    @endphp
                                                                                    <div role="tabpanel" class="tab-pane"
                                                                                        id="{{ $loc->locale }}-features0">
                                                                                        <input type="text"
                                                                                            name="{{ $loc->locale }}[features][]"
                                                                                            class="input-field"
                                                                                            placeholder="{{ __('Enter Your Keyword') }}"
                                                                                            value="{{ $transFeature[0] ?? '' }}">
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6">
                                                                    <div class="input-group colorpicker-component cp">
                                                                        <input type="text" name="colors[]"
                                                                            value="{{ isset($data->colors[0])
                                                                                ? $data->colors[0]
                                                                                : "
                                                                                                                                                    #000000" }}"
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
                                                        <div class="feature-area mb-3">
                                                            <div class="row mb-0">
                                                                <div class="col-lg-6">
                                                                    <div class="panel panel-lang">
                                                                        <div class="panel-body">
                                                                            <div class="tab-content">
                                                                                <div role="tabpanel"
                                                                                    class="tab-pane active"
                                                                                    id="{{ $lang->locale }}-features1">
                                                                                    <input type="text"
                                                                                        name="{{ $lang->locale }}[features][]"
                                                                                        class="input-field"
                                                                                        placeholder="{{ __('Enter Your Keyword') }}"
                                                                                        value="{{ isset($data['features'][1]) ? $data['features'][1] : '' }}">
                                                                                </div>
                                                                                @foreach ($locales as $loc)
                                                                                    @if ($loc->locale === $lang->locale)
                                                                                        @continue
                                                                                    @endif
                                                                                    @php
                                                                                        $transFeature = explode(',', $data->translate($loc->locale)['features']);
                                                                                    @endphp
                                                                                    <div role="tabpanel" class="tab-pane"
                                                                                        id="{{ $loc->locale }}-features1">
                                                                                        <input type="text"
                                                                                            name="{{ $loc->locale }}[features][]"
                                                                                            class="input-field"
                                                                                            placeholder="{{ __('Enter Your Keyword') }}"
                                                                                            value="{{ $transFeature[1] ?? '' }}">
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6">
                                                                    <div class="input-group colorpicker-component cp">
                                                                        <input type="text" name="colors[]"
                                                                            value="{{ isset($data->colors[1])
                                                                                ? $data->colors[1]
                                                                                : "
                                                                                                                                                    #000000" }}"
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
                                                    <h4 class="heading">{{ __('Youtube Video URL') }}*
                                                        <span>{{ __('(Optional)') }}</span>
                                                    </h4>
                                                    <input name="youtube" type="text" class="input-field"
                                                        placeholder="{{ __('Youtube Video URL') }}"
                                                        value="{{ $data->youtube }}">
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="checkbox-wrapper list list-personalizada">
                                                            <input type="checkbox" name="seo_check" value="1"
                                                                class="checkclick" id="allowProductSEO"
                                                                {{ $data->meta_tag != null || strip_tags($data->meta_description) != null ? 'checked' : '' }}>
                                                            <label
                                                                for="allowProductSEO">{{ __('Allow Product SEO') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="{{ $data->meta_tag == null && strip_tags($data->meta_description) == null
                                                        ? "
                                                                                                    showbox"
                                                        : '' }}">

                                                    @component('admin.components.input-localized', ['from' => $data, 'type' => 'tags'])
                                                        @slot('name')
                                                            meta_tag
                                                        @endslot
                                                        @slot('value')
                                                            meta_tag
                                                        @endslot
                                                        {{ __('Meta Tags') }}
                                                    @endcomponent

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



                                        <div class="col-xl-12 text-center">
                                            <button class="addProductSubmit-btn"
                                                type="submit">{{ __('Save') }}</button>
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
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                                )</div>
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
        // Remove White Space
        function isEmpty(el) {
            return !$.trim(el.html())
        }
        // Remove White Space Ends
    </script>

    <script>
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
                        + '">' +
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
    </script>

    <script type="text/javascript">
        // Gallery Section Update
        $(document).on("click", ".set-gallery-product", function() {
            var pid = $(this).find('input[type=hidden]').val();
            $('#pid').val(pid);
            $('.selected-image .row').html('');
            $.ajax({
                type: "GET",
                url: "{{ route('vendor-gallery-show') }}",
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
                                '<a href="' + '{{ asset('storage/images/galleries') . '/' }}' + arr[
                                    k][
                                    'photo'
                                ] + '" target="_blank">' +
                                '<img src="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                arr[k][
                                    'photo'
                                ] + '" alt="gallery image">' +
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
                url: "{{ route('vendor-gallery-delete') }}",
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
                url: "{{ route('vendor-gallery-store') }}",
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
                                '<a href="' + '{{ asset('storage/images/galleries') . '/' }}' + arr[
                                    k][
                                    'photo'
                                ] + '" target="_blank">' +
                                '<img src="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                arr[k][
                                    'photo'
                                ] + '" alt="gallery image">' +
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

    <script src="{{ asset('assets/admin/js/jquery.SimpleCropper.js') }}"></script>
    <script src="{{ asset('assets/admin/js/cropper.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery-cropper.js') }}"></script>

    <script type="text/javascript">
        $('.cropme').simpleCropper();
        $('#crop-image').on('click', function() {
            $('.cropme').click();
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            let html =
                `<img src="{{ filter_var($data->photo, FILTER_VALIDATE_URL) ? $data->photo : asset('storage/images/products/' . $data->photo) }}" alt="">`;
            $('.span4.cropme').html(html);
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
                        url: "{{ route('vendor-prod-upload-update', $data->id) }}",
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

    <script>
        $('#imageSource').on('change', function() {
            var file = this.value;
            if (file == 'file') {
                $('#f-file').show();
                $('#f-link').hide();
            }
            if (file == 'link') {
                $('#f-file').hide();
                $('#f-link').show();
            }
        });

        function deleteImage(id) {
            $.ajax({
                url: '{{ route('admin-prod-delete-img') }}',
                type: 'POST',
                data: {
                    'id': id
                },
                success: function(data) {
                    if (data.status) {
                        $.notify(data.message, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 300);
                    }
                    if ((data.errors)) {
                        for (var error in data.errors) {
                            $.notify(data.errors[error], 'danger');
                        }
                    }
                }
            });
        }
    </script>

    <script src="{{ asset('assets/admin/js/product.js') }}"></script>
@endsection
