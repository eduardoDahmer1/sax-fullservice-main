@extends('layouts.admin')

@section('styles')
    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .delete-button {
            justify-content: end;
            display: flex;
            align-items: baseline;
        }
        .max-width-div{
            max-height: 300px;
            overflow: auto;
        }
        .product-wrapper {
            padding: 10px;
         }
    </style>

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
                    <h4 class="heading"> {{ __('Edit Product') }}<a class="add-btn" href="javascript:history.back();"><i
                                class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-index') }}">{{ __('Products') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-edit', $data->id) }}">{{ __('Edit') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @if (config('mercadolivre.is_active'))
            @include('includes.admin.partials.product-tabs')
        @endif
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">

                            <div class="gocover"
                                style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form id="geniusform" action="{{ route('admin-prod-update', $data->id) }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="title-section-form">
                                    <span>1</span>
                                    <h3>
                                        {{ __('Mandatory Data') }}
                                    </h3>
                                </div>

                                @if (!empty($data->external_name))
                                    <div class="row alert alert-info">
                                        <div class="col-xl-12">
                                            <div class="input-form">
                                                <h4 class="heading">{{ __('External Name') }}</h4>
                                                <p class="sub-heading">{{ __('This name comes from an external source') }}
                                                </p>
                                                <p>
                                                    {{ $data->external_name }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row border-sep">

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

                                    <div class="col-xl-4">
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
                                    @if (!config('features.marketplace'))
                                        <div class="col-xl-4">
                                            <div class="input-form">
                                                <div class="d-flex">
                                                    <h4 class="heading">
                                                        {{ __('Product Current Price') }}*
                                                        <span>
                                                            ({{ __('In') }} {{ $sign->name }})
                                                        </span>
                                                    </h4>
                                                </div>
                                                <input name="price" type="number" class="input-field"
                                                    placeholder="e.g 20" step="0.01" min="0"
                                                    value="{{ round($data->price * $sign->value, 2) }}" required="">
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="input-form">
                                                <h4 class="heading">{{ __('Show Price') }}* </h4>
                                                <div class="row justify-content-left">
                                                    <div class="col-lg-12 d-flex justify-content-between">
                                                        <label class="control-label"
                                                            for="showPrice">{{ __('Show this Product price') }}</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="show_price" id="showPrice"
                                                                {{ $data->show_price == 1 ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="price" value="0">
                                    @endif

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
                                            <h4 class="heading">{{ __('GTIN Code') }}</h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Enter Product GTIN') }}" name="gtin" value="{{ $data->gtin }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Product Sku') }}* <i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block "
                                                    data-placement="top"
                                                    title="{{ __('SKU (Stock Keeping Unit) helps you to track your stock, measure sales by product and category') }}"></i>
                                            </h4>
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
                                                                id="store{{ $store->id }}"
                                                                value="{{ $store->id }}"
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

                                    @if (config('features.redplay_digital_product'))
                                        <input type="hidden" name="redplay_license">
                                        <div class="col-xl-6">
                                            <div class="input-form">
                                                <h4 class="heading">Licença Redplay* </h4>
                                                <div class="row justify-content-left">
                                                    <div class="col-lg-12 d-flex justify-content-between">
                                                        <label class="control-label" for="hasLicense">Marque esta opção
                                                            para
                                                            habilitar a configuração de licença Redplay.</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="has_license" id="hasLicense"
                                                                {{ $data->licenses()->count() > 0 ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6" id="divProductLicenses" style="display: none;">
                                            <div class="input-form">
                                                <h4 class="heading text-center">Licença Redplay</h4>
                                                <div class="row justify-content-center">
                                                    <div class="col-lg-12 d-flex justify-content-center">
                                                        <a href="#" class="btn btn-info" data-toggle="modal"
                                                            data-target="#setlicenseconfig">
                                                            <i class="icofont-wrench"></i> Configurar
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


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
                                            <select id="brand" name="brand_id">
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
                                            <h4 class="heading">{{ __('Part Number') }} <i
                                                    class="icofont-question-circle" data-toggle="tooltip"
                                                    style="display: inline-block " data-placement="top"
                                                    title="{{ __('Product model') }}"></i></h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Enter Product Part Number') }}" name="mpn"
                                                value="{{ $data->mpn }}">
                                        </div>
                                    </div>

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
                                                    <div class="span4 cropme text-center img-form-product" id="landscape">
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
                                            <div class="row justify-content-center">
                                                <div class="col-9">
                                                    <x-admin.product.gallery :galleries="$data->galleries"/>
                                                </div>
                                            </div>
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
                                <!--FINAL DA ROW DE DADOS OPCIONAIS IMPORTANTES-->

                                <div class="title-section-form">
                                    <span>3</span>
                                    <h3>
                                        {{ __('Extra Data') }}
                                    </h3>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <ul class="list list-personalizada">
                                            <li>
                                                <input class="checkclick" name="color_check" type="checkbox"
                                                    id="check3" value="1"
                                                    {{ empty($data->color) ? '' : 'checked' }}>
                                                <label for="check3">{{ __('Allow Product Colors') }}</label>
                                            </li>
                                        </ul>
                                        <div class='{{ !empty($data->color) ? '' : 'showbox' }}'>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="input-form product-size-details">
                                                        <div class="row">
                                                            <div class="col-md-2">
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
                                                            @if (config('features.color_gallery'))
                                                                <div class="col-md-3">
                                                                    <label>
                                                                        {{ __('Gallery') }}
                                                                        <span>
                                                                            {{ __('These photos will be shown when this color is selected.') }}
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div id="color-section">
                                                            @foreach ((array) $data->color as $key => $data1)
                                                                {{-- @if (!isset($data->material[0]))
                                                        {{dd($data->color_qty)}}
                                                        @endif --}}
                                                                <div class="row">
                                                                    <div class="col-md-2">
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
                                                                            value="{{ isset($data->color_qty[$key]) ? $data->color_qty[$key] : '0' }}"
                                                                            min="0" required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="number" step="0.01"
                                                                            name="color_price[]" class="input-field"
                                                                            placeholder="{{ __('Color Price') }}"
                                                                            value="{{ isset($data->color_price[$key]) ? $data->color_price[$key] : '0' }}"
                                                                            min="0" required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        @if (config('features.color_gallery'))
                                                                            @php
                                                                                $gallery = isset($data->color_gallery) ? explode(',', $data->color_gallery) : null;
                                                                            @endphp
                                                                            @if ($data->color_gallery)
                                                                                <input type="file"
                                                                                    name="color_gallery[{{ $key }}][]"
                                                                                    id="uploadgallery_color"
                                                                                    accept="image/*" multiple>
                                                                                <input type="hidden"
                                                                                    name="color_gallery_current[{{ $key }}]"
                                                                                    value="{{ isset($gallery) ? $gallery[$key] : null }}">
                                                                            @else
                                                                                <input type="file"
                                                                                    name="color_gallery[{{ $key }}][]"
                                                                                    id="uploadgallery_color"
                                                                                    accept="image/*" multiple required>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    @if (config('features.color_gallery'))
                                                                        <div class="col-md-1">
                                                                            <button type="button"
                                                                                class="btn btn-danger text-white color-remove-with-gallery"><i
                                                                                    class="fa fa-times"></i></button>
                                                                        </div>
                                                                    @else
                                                                        <div class="col-md-1">
                                                                            <button type="button"
                                                                                class="btn btn-danger text-white color-remove"><i
                                                                                    class="fa fa-times"></i></button>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        <a href="javascript:;"
                                                            id="{{ config(' features.color_gallery') ? 'color-btn-with-gallery' : 'color-btn' }}"
                                                            class="add-more mt-4 mb-3"><i
                                                                class="fas fa-plus"></i>{{ __('Add
                                                                                                                                                                                                                                                        More Color') }}
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <!--FECHAMENTO COL-XL-6-->
                                    @if (config('features.material_gallery'))
                                        <div class="col-xl-12">
                                            <ul class="list list-personalizada">
                                                <li>
                                                    <input class="checkclick" name="material_check" type="checkbox"
                                                        id="check_material" value="1"
                                                        {{ empty($data->material) ? '' : 'checked' }}>
                                                    <label
                                                        for="check_material">{{ __('Allow Product Materials') }}</label>
                                                </li>
                                            </ul>
                                            <div class="{{ !empty($data->material) ? '' : ' showbox' }}">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="input-form product-size-details">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>
                                                                        {{ __('Product Material') }}
                                                                        <span>
                                                                            {{ __('(Set a Product Material)') }}
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>
                                                                        {{ __('Material Qty') }}
                                                                        <span>
                                                                            {{ __('(Number of quantity of this Material)') }}
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>
                                                                        {{ __('Material Price') }}
                                                                        <span>
                                                                            {{ __('(This price will be added with base price)') }}
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>
                                                                        {{ __('Material Gallery') }}
                                                                        <span>
                                                                            {{ __('(These photos will be displayed when this
                                                                                                                                                                                                                                                                                                        material is selected)') }}
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div id="material-section">
                                                                @foreach ((array) $data->material as $key => $material)
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <div class="select-input-color">
                                                                                <div class="color-area">
                                                                                    <div class="input-group">
                                                                                        <input type="text"
                                                                                            name="material[]"
                                                                                            class="input-field"
                                                                                            placeholder="{{ __('Material Name') }}"
                                                                                            value="{{ $material }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="number" name="material_qty[]"
                                                                                class="input-field"
                                                                                placeholder="{{ __('material Qty') }}"
                                                                                value="{{ isset($data->material_qty[$key])
                                                                                    ? $data->material_qty[$key]
                                                                                    : "
                                                                                                                                                                                                                                                                                                                    0" }}"
                                                                                min="0" required>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="number" step="0.01"
                                                                                name="material_price[]"
                                                                                class="input-field"
                                                                                placeholder="{{ __('material Price') }}"
                                                                                value="{{ isset($data->material_price[$key])
                                                                                    ? $data->material_price[$key]
                                                                                    : "
                                                                                                                                                                                                                                                                                                                    0" }}"
                                                                                min="0" required>
                                                                        </div>
                                                                        <div class="col-md-3 delete-button">
                                                                            @php
                                                                                $gallery = isset($data->material_gallery) ? explode(',', $data->material_gallery) : null;
                                                                            @endphp
                                                                            @if ($data->material_gallery && array_key_exists($key, $gallery))
                                                                                <input type="file"
                                                                                    name="material_gallery[{{ $key }}][]"
                                                                                    id="uploadgallery_material"
                                                                                    accept="image/*" multiple>
                                                                                <input type="hidden"
                                                                                    name="material_gallery_current[{{ $key }}]"
                                                                                    value="{{ isset($gallery) ? $gallery[$key] : null }}">
                                                                            @else
                                                                                <input type="file"
                                                                                    name="material_gallery[{{ $key }}][]"
                                                                                    id="uploadgallery_material"
                                                                                    accept="image/*" multiple>
                                                                            @endif
                                                                            <button type="button"
                                                                                class="btn btn-danger text-white color-remove-with-gallery"><i
                                                                                    class="fa fa-times"></i></button>
                                                                        </div>
                                                                        {{-- <div class="col-md-2">
                                                                <button type="button"
                                                                    class="btn btn-danger text-white color-remove-with-gallery"><i
                                                                        class="fa fa-times"></i></button>
                                                            </div> --}}
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                            <a href="javascript:;" id="material-btn"
                                                                class="add-more mt-4 mb-3"><i
                                                                    class="fas fa-plus"></i>{{ __('Add
                                                                                                                                                                                                                                                                    More Materials') }}
                                                            </a>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                        <!--FECHAMENTO COL-XL-6-->
                                    @endif
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
                                        <ul class="list list-personalizada">
                                            <li>
                                                <input class="checkclick" name="whole_check" type="checkbox"
                                                    id="whole_check" value="1"
                                                    {{ !empty($data->whole_sell_qty) ? 'checked' : '' }}>
                                                <label for="whole_check">{{ __('Allow Product Whole Sell') }}</label>
                                            </li>
                                        </ul>

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
                                                <span>(un.)</span>
                                            </h4>
                                            <input name="max_quantity" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="0"
                                                value="{{ $data->max_quantity }}">
                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-6-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product Weight') }}
                                                <span>(kg.)</span>
                                            </h4>
                                            <input name="weight" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="0.01" min="0"
                                                value="{{ $data->weight }}">
                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-6-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product width') }}
                                                <span>(cm.)</span>
                                            </h4>

                                            <input name="width" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="0"
                                                value="{{ $data->width }}">
                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-6-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product height') }}
                                                <span>(cm.)</span>
                                            </h4>
                                            <input name="height" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="0"
                                                value="{{ $data->height }}">
                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-6-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product length') }}
                                                <span>(cm.)</span>
                                            </h4>
                                            <input name="length" type="number" class="input-field"
                                                placeholder="{{ __('e.g 20') }}" step="1" min="0"
                                                value="{{ $data->length }}">
                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-6-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Product Previous Price') }}
                                                <span>{{ __('(Optional)') }}</span>
                                            </h4>
                                            <input name="previous_price" step="0.01" type="number"
                                                class="input-field" placeholder="{{ __('e.g 20') }}" min="0"
                                                value="{{ round($data->previous_price * $sign->value, 2) }}">
                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-6-->

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Product Stock') }}
                                                <span>
                                                    {{ __('(Leave Empty will Show Always Available)') }}
                                                </span>
                                            </h4>
                                            <input name="stock" id="stock" type="text" class="input-field"
                                                placeholder="" value="{{ $data->stock }}">
                                        </div>
                                    </div>
                                   <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Size Name') }}
                                                <span>
                                                    {{ __('(eg. S,M,L,XL,XXL,3XL,4XL)')}}
                                                </span>
                                            </h4>
                                            <input name="product_size" id="product_size" type="text" class="input-field"
                                            placeholder="{{ __('Size Name') }}" value="{{$data->product_size}}">


                                        </div>
                                    </div>
                                    <!--FINAL ROW COL-XL-3-->

                                    <div class="col-xl-3">

                                        <div class="checkbox-wrapper list list-personalizada">
                                            <input type="checkbox" name="measure_check" class="checkclick1"
                                                id="allowProductMeasurement" value="1"
                                                {{ $data->measure == null ? '' : 'checked' }}>
                                            <label
                                                for="allowProductMeasurement">{{ __('Allow Product Measurement') }}</label>
                                        </div>

                                        <div class="input-form {{ $data->measure == null ? 'showbox' : '' }}">

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
                                                    {{ __('Litre') }}
                                                </option>
                                                <option value="Pound" {{ $data->measure == 'Pound' ? 'selected' : '' }}>
                                                    {{ __('Pound') }}
                                                </option>
                                                <option value="Custom"
                                                    {{ in_array($data->measure, explode(',', 'Gram,Kilogram,Litre,Pound')) ? '' : 'selected' }}>
                                                    {{ __('Custom') }}</option>
                                            </select>
                                            <div class="hidden" id="measure">
                                                <input name="measure" type="text" id="measurement"
                                                    class="input-field" placeholder="Enter Unit"
                                                    value="{{ $data->measure }}">
                                            </div>

                                        </div>

                                    </div>
                                    <!--FINAL ROW COL-XL-6-->

                                    <div class="col-xl-12">
                                        <div class="title-section-form"></div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="featured-keyword-area input-form">
                                            <h4 class="heading">
                                                {{ __('Feature Tags') }}
                                                <span>
                                                    {{ __('(You can create up to 2 Product Tags)') }}
                                                </span>
                                                <i class="icofont-question-circle" data-toggle="tooltip"
                                                    style="display: inline-block " data-placement="top"
                                                    title="{{ __('Tags are showed in the product card and can help you to track your products') }}"></i>
                                            </h4>

                                            <div class="feature-tag-top-filds" id="feature-section">
                                                @php
                                                    $currentFeature = 0;
                                                    $featureCount = !empty($data['features']) ? count($data['features']) : 0;
                                                @endphp

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
                                                                                placeholder="{{ __('Enter Your Keyword') }}"
                                                                                value="{{ isset($data['features'][0]) ? $data['features'][0] : '' }}">
                                                                        </div>
                                                                        @foreach ($locales as $loc)
                                                                            @if ($loc->locale === $lang->locale)
                                                                                @continue
                                                                            @endif
                                                                            @if (isset($data->translate($loc->locale)['features']))
                                                                                @php
                                                                                    $transFeature = explode(',', $data->translate($loc->locale)['features']);
                                                                                @endphp
                                                                            @endif
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
                                                                        <div role="tabpanel" class="tab-pane active"
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
                                                                            @if (isset($data->translate($loc->locale)['features']))
                                                                                @php
                                                                                    $transFeature = explode(',', $data->translate($loc->locale)['features']);
                                                                                @endphp
                                                                            @endif
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
                                            <label for="allowProductSEO">{{ __('Allow Product SEO') }}</label><i
                                                class="icofont-question-circle" data-toggle="tooltip"
                                                style="display: inline-block " data-placement="top"
                                                title="{{ __('SEO (Search Engine Optimization) focuses on your website presence in search results on search engines like Google') }}"></i>
                                        </div>

                                        <div
                                            class="{{ $data->meta_tag == null && strip_tags($data->meta_description) == null
                                                ? "
                                                                                                                                                                            showbox"
                                                : '' }}"">

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
                                
                                    <!-- Outros campos do formulário de edição -->

                                    <div class="title-section-form">
                                        <span>4</span>
                                        <h3>{{ __('Associate Colors') }}</h3>
                                    </div>
                                    <div class="product-wrapper">
                                        <div class="container-fluid">
                                            <div class="form-group">
                                                <label for="searchAssociatedColor">{{__('Look for the product')}}</label>
                                                <div class="d-flex">
                                                    <input id="searchAssociatedColor" class="form-control m-0" type="text" name="search" placeholder="{{__('Products name')}}">
                                                    <button id="buttonSearchAssociatedColor" class="btn btn-info" type="button">{{__("Search")}}</button>
                                                </div>
                                                <small id="emailHelp" class="form-text text-muted">{{__('Enter the name of the product you want to associate')}}</small>
                                            </div>
                                            <div class="row m-0">
                                                @foreach ($data->associatedProductsByColor as $associatedProduct)
                                                    <div class="col-md-6">
                                                        <div class="box-options-assoc">
                                                            <input type="checkbox" id="produto_{{ $associatedProduct->id }}_color" name="associated_colors[]" value="{{ $associatedProduct->id }}" checked>
                                                            <label for="produto_{{ $associatedProduct->id }}_color">
                                                                <img src="{{filter_var($associatedProduct->thumbnail, FILTER_VALIDATE_URL) ? $associatedProduct->thumbnail :
                                                                    asset('storage/images/thumbnails/'.$associatedProduct->thumbnail)}}" 
                                                                    class="img-circle mr-1"
                                                                    width="40px">
                                                                <div>
                                                                    <h6 class="m-0">{{ $associatedProduct->name }}</h6>
                                                                    <p class="text-muted">
                                                                        <small class="d-flex align-items-center">
                                                                            {{__('Color')}}:
                                                                            <span style="background-color:{{ $associatedProduct->color[0]?? '#fff' }};margin-left:5px;height:15px;width:15px;border-radius:100%;display:inline-block;"></span>
                                                                        </small>
                                                                    </p>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div id="boxAssociatedColor" class="row m-0"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="title-section-form">
                                        <span>5</span>
                                        <h3>{{ __('Associate Size') }}</h3>
                                    </div>
                                    <div class="product-wrapper">
                                        <div class="container-fluid">
                                            <div class="form-group">
                                                <label for="searchAssociatedSize">{{__('Look for the product')}}</label>
                                                <div class="d-flex">
                                                    <input id="searchAssociatedSize" class="form-control m-0" type="text" name="search" placeholder="{{__('Products name')}}">
                                                    <button id="buttonSearchAssociatedSize" class="btn btn-info" type="button">{{__("Search")}}</button>
                                                </div>
                                                <small id="emailHelp" class="form-text text-muted">{{__('Enter the name of the product you want to associate')}}</small>
                                            </div>
                                            <div class="row m-0">
                                                @foreach ($data->associatedProductsBySize as $associatedProduct)
                                                    <div class="col-md-6">
                                                        <div class="box-options-assoc">
                                                            <input type="checkbox" id="produto_{{ $associatedProduct->id }}_size" name="associated_sizes[]" value="{{ $associatedProduct->id }}" checked>
                                                            <label for="produto_{{ $associatedProduct->id }}_size">
                                                                <img src="{{filter_var($associatedProduct->thumbnail, FILTER_VALIDATE_URL) ? $associatedProduct->thumbnail :
                                                                    asset('storage/images/thumbnails/'.$associatedProduct->thumbnail)}}" 
                                                                    class="img-circle mr-1"
                                                                    width="40px">
                                                                <div>
                                                                    <h6 class="m-0">{{ $associatedProduct->name }}</h6>
                                                                    <p class="text-muted"><small>{{__('Size')}}: {{ $associatedProduct->product_size }}</small></p>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div id="boxAssociatedSize" class="row m-0"></div>
                                        </div>
                                    </div>

                                    <div class="title-section-form">
                                        <span>6</span>
                                        <h3>{{ __('Associate Look') }}</h3>
                                    </div>
                                    <div class="product-wrapper">
                                        <div class="container-fluid">
                                            <div class="form-group">
                                                <label for="searchAssociatedLook">{{__('Look for the product')}}</label>
                                                <div class="d-flex">
                                                    <input id="searchAssociatedLook" class="form-control m-0" type="text" name="search" placeholder="{{__('Products name')}}">
                                                    <button id="buttonSearchAssociatedLook" class="btn btn-info" type="button">{{__("Search")}}</button>
                                                </div>
                                                <small id="emailHelp" class="form-text text-muted">{{__('Enter the name of the product you want to associate')}}</small>
                                            </div>
                                            <div class="row m-0">
                                                @foreach ($data->associatedProductsByLook as $associatedProduct)
                                                    <div class="col-md-6">
                                                        <div class="box-options-assoc">
                                                            <input type="checkbox" id="produto_{{ $associatedProduct->id }}_look" name="associated_looks[]" value="{{ $associatedProduct->id }}" checked>
                                                            <label for="produto_{{ $associatedProduct->id }}_look">
                                                                <img src="{{filter_var($associatedProduct->thumbnail, FILTER_VALIDATE_URL) ? $associatedProduct->thumbnail :
                                                                    asset('storage/images/thumbnails/'.$associatedProduct->thumbnail)}}" 
                                                                    class="img-circle mr-1"
                                                                    width="40px">
                                                                <div>
                                                                    <h6 class="m-0">{{ $associatedProduct->name }}</h6>
                                                                    <p class="text-muted"><small>{{__('Size')}}: {{ $associatedProduct->product_size }}</small></p>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div id="boxAssociatedLook" class="row m-0"></div>
                                        </div>
                                    </div>
                                    
                                    
                                <div class="row pt-4">
                                    <div class="col-xl-12 text-center">
                                        <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </div>

                                @if (config('features.redplay_digital_product'))
                                    <div class="modal fade" id="setlicenseconfig" tabindex="-1" role="dialog"
                                        aria-labelledby="setlicenseconfig" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalCenterTitle">Licença Redplay
                                                        para
                                                        este Produto<small><br>
                                                            Os dados preenchidos aqui serão enviados por e-mail ao cliente
                                                            ao
                                                            término da compra e aprovação do pagamento. <br>
                                                            Sempre que um código for enviado a determinado cliente, o mesmo
                                                            imediatamente será dado como INDISPONÍVEL.
                                                        </small></h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="top-area" id="redplayInputArea">
                                                        @forelse($data->licenses as $redplayLicense)
                                                            <div class="row mb-2">
                                                                <div class="col-3">
                                                                    <input
                                                                        {{ !$redplayLicense->available ? 'readonly' : '' }}
                                                                        type="text" name="redplay_login[]"
                                                                        class="input-field m-0"
                                                                        placeholder="Login Redplay"
                                                                        value="{{ $redplayLicense->login }}">
                                                                </div>
                                                                <div class="col-3">
                                                                    <input
                                                                        {{ !$redplayLicense->available ? 'readonly' : '' }}
                                                                        type="text" name="redplay_password[]"
                                                                        class="input-field m-0"
                                                                        placeholder="Senha Redplay"
                                                                        value="{{ $redplayLicense->password }}">
                                                                </div>
                                                                <div class="col-3">
                                                                    <input
                                                                        {{ !$redplayLicense->available ? 'readonly' : '' }}
                                                                        type="text" name="redplay_code[]"
                                                                        class="input-field m-0"
                                                                        placeholder="Código Redplay"
                                                                        value="{{ $redplayLicense->code }}">
                                                                </div>
                                                                <div
                                                                    class="col-1 d-flex align-items-center justify-content-center">
                                                                    <button type="button"
                                                                        class="btn btn-info bg-dark border border-dark btnAddRedplay">
                                                                        <span aria-hidden="true"><i
                                                                                class="fa fa-plus"></i></span>
                                                                    </button>
                                                                    @if (!$loop->first && $redplayLicense->available)
                                                                        <button type="button"
                                                                            class="btn btn-danger border border-red btnRemoveRedplay ml-2">
                                                                            <span aria-hidden="true"><i
                                                                                    class="fa fa-trash"></i></span>
                                                                        </button>
                                                                    @endif
                                                                </div>

                                                                <div
                                                                    class="col-1 d-flex align-items-center justify-content-center">
                                                                    <span
                                                                        class="badge badge-{{ $redplayLicense->available
                                                                            ? "
                                                                                                                                                                                                                                                                                    success"
                                                                            : 'danger' }} ml-2">{{ $redplayLicense->available ? 'Disponível' : 'Usado' }}</span>
                                                                </div>

                                                            </div>
                                                        @empty
                                                            <div class="row mb-2">
                                                                <div class="col-3">
                                                                    <input type="text" name="redplay_login[]"
                                                                        class="input-field m-0"
                                                                        placeholder="Login Redplay">
                                                                </div>
                                                                <div class="col-3">
                                                                    <input type="text" name="redplay_password[]"
                                                                        class="input-field m-0"
                                                                        placeholder="Senha Redplay">
                                                                </div>
                                                                <div class="col-3">
                                                                    <input type="text" name="redplay_code[]"
                                                                        class="input-field m-0"
                                                                        placeholder="Código Redplay">
                                                                </div>
                                                                <div
                                                                    class="col-1 d-flex align-items-center justify-content-center">
                                                                    <button type="button"
                                                                        class="btn btn-info bg-dark border border-dark btnAddRedplay">
                                                                        <span aria-hidden="true"><i
                                                                                class="fa fa-plus"></i></span>
                                                                    </button>
                                                                </div>

                                                                <div
                                                                    class="col-1 d-flex align-items-center justify-content-center">
                                                                    <span
                                                                        class="badge badge-success ml-2">Disponível</span>
                                                                </div>

                                                            </div>
                                                        @endforelse
                                                    </div>
                                                    <div class="row mt-5">
                                                        <div class="col-12 text-center">
                                                            <button class="btn btn-success" data-dismiss="modal"
                                                                id="btnSaveLicenses">Salvar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
                    @if (!empty($ftp_gallery))
                        <div class="gallery-images">
                            <div class="">
                                <div class="row">
                                    @foreach ($ftp_gallery as $ftp_image)
                                        @if ($ftp_image != $data->photo)
                                            <div class="col-sm-6">
                                                <div class="img gallery-img">
                                                    <a href="{{ $ftp_image }}">
                                                        <img src="{{ $ftp_image }}" alt="gallery image">
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
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

            // color check
            if ($("#check3").is(":checked")) {
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

                $("#stock").val(color_stock_total);
                $("#stock").attr("readonly", true);
                $("#stock").addClass("disabled");
            }

            if ($("#check_material").is(":checked")) {
                var material_input = $("input[name='material_qty[]']");
                var material_stock_total = 0;
                for (i = 0; i < material_input.length; i++) {
                    material_stock_total = parseInt(material_stock_total) + parseInt(material_input[i].value);
                }
                if (isNaN(material_stock_total)) {
                    $("#stock").val(null);
                } else {
                    $("#stock").val(material_stock_total);
                }

                $("#stock").val(material_stock_total);
                $("#stock").attr("readonly", true);
                $("#stock").addClass("disabled");
            }

            $("input[name='material_qty[]']").on('keyup', function() {
                var material_input = $("input[name='material_qty[]']");
                var material_stock_total = 0;
                for (i = 0; i < material_input.length; i++) {
                    material_stock_total = parseInt(material_stock_total) + parseInt(material_input[i]
                        .value);
                }
                if (isNaN(material_stock_total)) {
                    $("#stock").val(null);
                } else {
                    $("#stock").val(material_stock_total);
                }

            });

            // material check
            if ($("#check_material").is(":checked")) {
                var material_input = $("input[name='material_qty[]']");
                var material_stock_total = 0;
                for (i = 0; i < material_input.length; i++) {
                    material_stock_total = parseInt(material_stock_total) + parseInt(material_input[i].value);
                }
                if (isNaN(material_stock_total)) {
                    $("#stock").val(null);
                } else {
                    $("#stock").val(material_stock_total);
                }

                $("#stock").val(material_stock_total);
                $("#stock").attr("readonly", true);
                $("#stock").addClass("disabled");
            }

            // material check
            $("#check_material").change(function() {
                if ($(this).is(":checked")) {
                    $("#uploadgallery_material").prop("required", true);
                    var material_input = $("input[name='material_qty[]']");
                    var material_stock_total = 0;
                    for (i = 0; i < material_input.length; i++) {
                        material_stock_total = parseInt(material_stock_total) + parseInt(material_input[i]
                            .value);
                    }
                    if (isNaN(material_stock_total)) {
                        $("#stock").val(null);
                    } else {
                        $("#stock").val(material_stock_total);
                    }

                    $("#stock").attr("readonly", true);
                    $("#stock").addClass("disabled");
                } else {
                    $("#uploadgallery_material").prop("required", false);
                    $("#stock").attr("readonly", false);
                    $("#stock").removeClass("disabled");
                }
            });

            // color check
            $("#check3").change(function() {
                if ($(this).is(":checked")) {
                    $("#uploadgallery_color").prop("required", true);
                    var color_input = $("input[name='color_qty[]']");
                    color_input.val($("#stock").val());
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
                    $("#uploadgallery_color").prop("required", false);
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

            if ($("#size-check").is(":checked")) {
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

                $("#stock").val(size_stock_total);
                $("#stock").attr("readonly", true);
                $("#stock").addClass("disabled");
            }
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

    <script>
        // Gallery Section Update
        $(document).on('click', '.set-gallery-product', function() {
            var pid = $(this).find('input[type=hidden]').val();
            $('#pid').val(pid);
            $('.selected-image .row').html('');
            $.ajax({
                type: 'GET',
                url: '{{ route('admin-gallery-show') }}',
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
                                '<input type="hidden" value="' + arr[k]["id"] + '">' +
                                '</span>' +
                                '<a href="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                arr[
                                    k][
                                    "photo"
                                ] + '" target="_blank">' +
                                '<img src="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                arr[k][
                                    "photo"
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

            let carouselItem = $(`#gallery-${id}`)
            $('#carousel-gallery').find('.carousel-item').not('.active').first().addClass('active')
            carouselItem.remove();

            if ($('#carousel-gallery .carousel-item').length <= 0) {
                $('#carouselGallery').addClass('d-none');
            }

            $.ajax({
                type: 'GET',
                url: '{{ route('admin-gallery-delete') }}',
                data: {
                    id: id
                }
            });
        });
        $(document).on('click', '#prod_gallery', function() {
            $('#uploadgallery').click();
        });
        $(document).on('click', '#prod_gallery360', function() {
            $('#uploadgallery360').click();
        });
        var count_color = "{{ count(explode(',', $data->color_gallery)) }}";
        var count_material = "{{ count(explode(',', $data->material_gallery)) }}";
        var is_color_gallery = "{{ $data->color_gallery }}" ? "" : "required";
        var is_material_gallery = "{{ $data->material_gallery }}" ? "" : "required";
        $("#color-btn-with-gallery").on('click', function() {
            $("#color-section").append('' +
                '<div class="row">' +
                '<div class="col-md-2">' +
                '<div class="select-input-color">' +
                '<div class="color-area">' +
                '<div class="input-group colorpicker-component cp">' +
                '<input type="text" name="color[]" value="#000000"' +
                'class="input-field cp" />' +
                '<span class="input-group-addon"><i></i></span>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<input type="number" name="color_qty[]" class="input-field"' +
                'placeholder="" value="1" min="1">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<input type="number" step="0.01" name="color_price[]" class="input-field"' +
                'placeholder="" value="0" min="0">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<input type="file" name="color_gallery[' + count_color +
                '][]" id="uploadgallery_color" accept="image/*" multiple required>' +
                '</div>' +
                '<div class="col-md-1">' +
                '<button type="button" class="btn btn-danger text-white color-remove-with-gallery"><i class="fa fa-times"></i></button>' +
                '</div>' +
                '</div>');
            count_color++;
            $('.cp').colorpicker();
        });

        $("#material-btn").on('click', function() {
            $("#material-section").append('' +
                '<div class="row">' +
                '<div class="col-md-3">' +
                '<div class="select-input-color">' +
                '<div class="color-area">' +
                '<div class="input-group">' +
                '<input type="text" name="material[]" class="input-field"' +
                'placeholder="{{ __('Material Name') }}" value="">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<input type="number" name="material_qty[]" class="input-field"' +
                'placeholder="" value="1" min="1">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<input type="number" step="0.01" name="material_price[]" class="input-field"' +
                'placeholder="" value="0" min="0">' +
                '</div>' +
                '<div class="col-md-3 delete-button">' +
                '<input type="file" name="material_gallery[' + count_material +
                '][]" id="uploadgallery_material" accept="image/*" multiple required>' +
                '<button type="button" class="btn btn-danger text-white color-remove-with-gallery"><i class="fa fa-times"></i></button>' +
                '</div>' +
                '</div>');
            count_material++;
        });

        $(document).on('click', '.color-remove-with-gallery', function() {
            $(this.parentNode).parent().remove();
            if (isEmpty($('#color-section'))) {
                count_color = 1;
                $("#color-section").append('' +
                    '<div class="row">' +
                    '<div class="col-md-2">' +
                    '<div class="select-input-color">' +
                    '<div class="color-area">' +
                    '<div class="input-group colorpicker-component cp">' +
                    '<input type="text" name="color[]" value="#000000"' +
                    'class="input-field cp" />' +
                    '<span class="input-group-addon"><i></i></span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<input type="number" name="color_qty[]" class="input-field"' +
                    'placeholder="" value="1" min="1">' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<input type="number" step="0.01" name="color_price[]" class="input-field"' +
                    'placeholder="" value="0" min="0">' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<input type="file" name="color_gallery[' + count_color +
                    '][]" id="uploadgallery_color" accept="image/*" multiple ' + is_color_gallery + '>' +
                    '</div>' +
                    '<div class="col-md-1">' +
                    '<button type="button" class="btn btn-danger text-white color-remove-with-gallery"><i class="fa fa-times"></i></button>' +
                    '</div>' +
                    '</div>');
                $('.cp').colorpicker();
            }
            count_material--;
            if (isEmpty($('#material-section'))) {
                count_material = 1;
                $("#material-section").append('' +
                    '<div class="row">' +
                    '<div class="col-md-3">' +
                    '<div class="select-input-color">' +
                    '<div class="color-area">' +
                    '<div class="input-group">' +
                    '<input type="text" name="material[]" class="input-field"' +
                    'placeholder="{{ __('Material Name') }}" value="">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<input type="number" name="material_qty[]" class="input-field"' +
                    'placeholder="" value="1" min="1">' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<input type="number" step="0.01" name="material_price[]" class="input-field"' +
                    'placeholder="" value="0" min="0">' +
                    '</div>' +
                    '<div class="col-md-3 delete-button">' +
                    '<input type="file" name="material_gallery[' + count_material +
                    '][]" id="uploadgallery_material" accept="image/*" multiple required>' +
                    '<button type="button" class="btn btn-danger text-white color-remove-with-gallery"><i class="fa fa-times"></i></button>' +
                    '</div>' +
                    '</div>');
            }
        });

        $('#uploadgallery').change(function() {
            $('#form-gallery').submit();
        });
        $('#uploadgallery360').change(function() {
            $('#form-gallery360').submit();
        });
        $(document).on('submit', '#form-gallery', function() {
            $.ajax({
                url: '{{ route('admin-gallery-store') }}',
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data != 0) {
                        $('#uploadgallery').val(null);
                        $('.selected-image .row').removeClass('justify-content-center');
                        $('.selected-image .row h3').remove();
                        var arr = $.map(data, function(el) {
                            return el
                        });
                        for (var k in arr) {
                            $('#carousel-gallery').append('<div class="carousel-item" id="gallery-'
                                +arr[k]["id"]
                                +'"><img class="d-block w-100" src="'
                                +'{{ asset('storage/images/galleries') }}'
                                +'/'+arr[k]["photo"]+'"></div>'
                            );

                            $('.selected-image .row').append('<div class="col-sm-6">' +
                                '<div class="img gallery-img">' +
                                '<span class="remove-img"><i class="fas fa-times"></i>' +
                                '<input type="hidden" value="' + arr[k]["id"] + '">' +
                                '</span>' +
                                '<a href="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                arr[
                                    k][
                                    "photo"
                                ] + '" target="_blank">' +
                                '<img src="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                arr[k][
                                    "photo"
                                ] + '" alt="gallery image">' +
                                '</a>' +
                                '</div>' +
                                '</div>');
                        }
                        
                        if ($('#carousel-gallery .carousel-item').length > 0) {
                            $('#carouselGallery').removeClass('d-none')
                            $('#carousel-gallery .carousel-item').removeClass('active')
                            $('#carousel-gallery').find('.carousel-item').not('.active').first().addClass('active')
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

    <script>
        $('.cropme').simpleCropper();
        $('#crop-image').on('click', function() {
            $('.cropme').click();
        });
    </script>

    <script>
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
                        url: '{{ route('admin-prod-upload-update', $data->id) }}',
                        type: 'POST',
                        data: {
                            'image': img
                        },
                        success: function(data) {
                            if (data.status) {
                                $('#feature_photo').val(data.file_name);
                            }
                            if ((data.errors)) {
                                for (var error in data.errors) {
                                    $.notify(data.errors[error], 'danger');
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

        const boxAssociatedColor = $('#boxAssociatedColor')
        const boxAssociatedSize = $('#boxAssociatedSize')
        const boxAssociatedLook = $('#boxAssociatedLook')

        const associeteColorCheck = {!! json_encode($associatedColors) !!}
        const associeteSizeCheck = {!! json_encode($associatedSizes) !!}
        const associeteLookCheck = {!! json_encode($associatedLooks) !!}

        let loading = document.createElement("h5")
        loading.classList.add('text-center','col-12', 'py-2')
        loading.innerHTML = 'Carregando...'


        async function handleGetProducts(search, i, c, b, a) {
            $(b).empty()
            b.append(loading)
            const response = await fetch(`/api/products?q=${search}`);
            const json = await response.json();
            $(b).empty()
            await monteHtml(json, i, c, b, a)
        }
        
        async function monteHtml(data, inputName, sufixo, box, arrayChecks){
            data.data.forEach(element => {
                let div = document.createElement("div")
                let sizeOurColor;
                if(sufixo == "size") {
                    sizeOurColor = `<p class="text-muted"><small>{{__('Size')}}: ${element.product_size?? '{{__("No size")}}'}</small></p>`
                } else {
                    sizeOurColor = `
                    <p class="text-muted">
                        <small class="d-flex align-items-center">
                            {{__('Color')}}:
                            <span style="background-color:${element.color[0]};margin-left:5px;height:15px;width:15px;border-radius:100%;display:inline-block;"></span>
                        </small>
                    </p>
                    `
                }

                let checked = arrayChecks.includes(element.id) ? 'checked' : ''
                div.classList.add('col-md-6')
                let content = `
                <div class="box-options-assoc">
                    <input type="checkbox" id="produto_${element.id}_${sufixo}" name="${inputName}" value="${element.id}" ${checked}>
                    <label for="produto_${element.id}_${sufixo}">
                        <img src="/storage/images/thumbnails/${element.thumbnail}" 
                            class="img-circle mr-1"
                            width="40px">
                        <div>
                            <h6 class="m-0">${element.es.name}</h6>
                            ${sizeOurColor}
                        </div>
                    </label>
                </div>
                `
                div.innerHTML = content
                if (!checked) {
                    box.append(div)
                }
            });
        }

        $('#buttonSearchAssociatedColor').click( event => {
            let searchColor = document.querySelector('#searchAssociatedColor').value
            handleGetProducts(searchColor, 'associated_colors[]', 'color', boxAssociatedColor, associeteColorCheck, false)
        });

        $('#buttonSearchAssociatedSize').click( event => {
            let searchSize = document.querySelector('#searchAssociatedSize').value
            handleGetProducts(searchSize, 'associated_sizes[]', 'size', boxAssociatedSize, associeteSizeCheck, false)
        });

        $('#buttonSearchAssociatedLook').click( event => {
            let searchLook = document.querySelector('#searchAssociatedLook').value
            handleGetProducts(searchLook, 'associated_looks[]', 'look', boxAssociatedLook, associeteLookCheck, false)
        });

    </script>

    <script src="{{ asset('assets/admin/js/product.js') }}"></script>
@endsection
