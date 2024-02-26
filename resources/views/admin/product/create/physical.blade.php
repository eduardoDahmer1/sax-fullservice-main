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
                    <h4 class="heading">{{ __('Physical Product') }} <a class="add-btn"
                            href="{{ route('admin-prod-index') }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-index') }}">{{ __('Products') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-physical-create') }}">{{ __('Add Product') }}</a>
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
                            <form id="geniusform" action="{{ route('admin-prod-store') }}" method="POST"
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

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Category') }}*</h4>
                                            <select id="cat" name="category_id" required="">
                                                <option value="">{{ __('Select Category') }}</option>
                                                @foreach ($cats as $cat)
                                                    <option data-href="{{ route('admin-subcat-load', $cat->id) }}"
                                                        value="{{ $cat->id }}">{{ $cat->name }}</option>
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
                                                    placeholder="{{ __('e.g 20') }}" step="0.01" required=""
                                                    min="0">
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
                                                            <input type="checkbox" name="show_price" id="showPrice" checked>
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
                                                value="{{ $temp_sku }}">
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
                                                value="{{ $temp_sku }}">
                                        </div>

                                    </div>
                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('GTIN Code') }}</h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Enter Product GTIN') }}" name="gtin">
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
                                                                value="{{ $store->id }}" checked>
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
                                                            <input type="checkbox" name="has_license" id="hasLicense">
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
                                            <select id="brand" name="brand_id">
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
                                            <h4 class="heading">{{ __('Part Number') }} <i
                                                    class="icofont-question-circle" data-toggle="tooltip"
                                                    style="display: inline-block " data-placement="top"
                                                    title="{{ __('Product model') }}"></i></h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Enter Product Part Number') }}" name="mpn">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Sub Category') }}</h4>
                                            <select id="subcat" name="subcategory_id" disabled="">
                                                <option value="">{{ __('Select Sub Category') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Child Category') }}</h4>
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
                                                {{ __('Product Gallery Images') }}
                                            </h4>
                                            <div class="row justify-content-center">
                                                <div class="col-9">
                                                    <x-admin.product.gallery />
                                                </div>
                                            </div>
                                            <a href="#" class="set-gallery-product" data-toggle="modal"
                                                data-target="#setgallery">
                                                <i class="icofont-plus"></i> {{ __('Set Gallery') }}
                                            </a>
                                        </div>

                                        <div class="checkbox-wrapper list list-personalizada">
                                            <input type="checkbox" name="product_condition_check" class="checkclick1"
                                                id="conditionCheck" value="1">
                                            <label for="conditionCheck">{{ __('Allow Product Condition') }}</label>
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
                                <!--FINAL DA ROW DE DADOS OPCIONAIS IMPORTANTES-->

                                <div class="title-section-form">
                                    <span>3</span>
                                    <h3>
                                        {{ __('Extra Data') }}
                                    </h3>
                                </div>

                                <div class="row">
                                    <!--COMEÇO DA ROW DE DADOS EXTRAS-->

                                    <div class="col-xl-12">
                                        <ul class="list list-personalizada">
                                            <li>
                                                <input class="checkclick" name="color_check" type="checkbox"
                                                    id="check3" value="1">
                                                <label for="check3">{{ __('Allow Product Colors') }}</label>
                                            </li>
                                        </ul>
                                        <div class="showbox">

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
                                                                        {{ __('Color Gallery') }}
                                                                        <span>
                                                                            {{ __('(These photos will be displayed when this
                                                                                                                                                                                                                            color is selected)') }}
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div id="color-section">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="select-input-color">
                                                                        <div class="color-area">
                                                                            <div
                                                                                class="input-group colorpicker-component cp">
                                                                                <input type="text" name="color[]"
                                                                                    value="#000000"
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
                                                                        value="0" min="0" required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="number" step="0.01"
                                                                        name="color_price[]" class="input-field"
                                                                        placeholder="{{ __('Color Price') }}"
                                                                        value="0" min="0" required>
                                                                </div>
                                                                @if (config('features.color_gallery'))
                                                                    <div class="col-md-3">
                                                                        <input type="file" name="color_gallery[0][]"
                                                                            id="uploadgallery_color" accept="image/*"
                                                                            multiple required>

                                                                    </div>
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
                                                        id="check_material" value="1">
                                                    <label
                                                        for="check_material">{{ __('Allow Product Materials') }}</label>
                                                </li>
                                            </ul>
                                            <div class="showbox">

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
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="select-input-color">
                                                                            <div class="color-area">
                                                                                <div class="input-group">
                                                                                    <input type="text"
                                                                                        name="material[]"
                                                                                        class="input-field"
                                                                                        placeholder="{{ __('Material Name') }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="number" name="material_qty[]"
                                                                            class="input-field"
                                                                            placeholder="{{ __('Material Qty') }}"
                                                                            value="0" min="0" required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="number" step="0.01"
                                                                            name="material_price[]" class="input-field"
                                                                            placeholder="{{ __('Material Price') }}"
                                                                            value="0" min="0" required>
                                                                    </div>
                                                                    <div class="col-md-3 delete-button">
                                                                        <input type="file" name="material_gallery[0][]"
                                                                            id="uploadgallery_material" accept="image/*"
                                                                            multiple required>
                                                                        <button type="button"
                                                                            class="btn btn-danger text-white color-remove-with-gallery"><i
                                                                                class="fa fa-times"></i></button>
                                                                    </div>
                                                                    {{-- <div class="col-md-1">
                                                                <button type="button"
                                                                    class="btn btn-danger text-white color-remove-with-gallery"><i
                                                                        class="fa fa-times"></i></button>
                                                            </div> --}}
                                                                </div>
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


                                    <div class="col-xl-6">
                                        <div class="form-list">
                                            <ul class="list list-personalizada">
                                                <li>
                                                    <input class="checkclick" name="shipping_time_check" type="checkbox"
                                                        id="check1" value="1">
                                                    <label
                                                        for="check1">{{ __('Allow Estimated Shipping Time') }}</label>
                                                </li>
                                            </ul>

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
                                    </div>
                                    <!--FECHAMENTO COL-XL-6-->

                                    <div class="col-xl-6">
                                        <ul class="list list-personalizada">
                                            <li>
                                                <input name="size_check" type="checkbox" id="size-check" value="1">
                                                <label for="size-check">{{ __('Allow Product Sizes') }}</label>
                                            </li>
                                        </ul>

                                        <div class="showbox input-form" id="size-display">
                                            <div class="row">
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
                                                    id="whole_check" value="1">
                                                <label for="whole_check">{{ __('Allow Product Whole Sell') }}</label>
                                            </li>
                                        </ul>

                                        <div class="showbox">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="featured-keyword-area input-form">
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
                                                placeholder="{{ __('e.g 20') }}" step="1" min="0">
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
                                                placeholder="{{ __('e.g 20') }}" step="0.01" min="0">
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
                                                placeholder="{{ __('e.g 20') }}" step="1" min="1">
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
                                                placeholder="{{ __('e.g 20') }}" step="1" min="1">
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
                                                placeholder="{{ __('e.g 20') }}" step="1" min="1">
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
                                                class="input-field" placeholder="{{ __('e.g 20') }}" min="0">
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
                                                placeholder="{{ __('e.g 20') }}">

                                        </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">{{__('Size Name')}}
                                                <span>
                                                    {{ __('(eg. S,M,L,XL,XXL,3XL,4XL)')}}
                                                </span>
                                            </h4>
                                            <input name="product_size" id="product_size" type="text" class="input-field"
                                            placeholder="{{ __('Size Name') }}">


                                        </div>
                                    </div>
                                    <div class="col-xl-3">

                                        <div class="checkbox-wrapper list list-personalizada">
                                            <input type="checkbox" name="measure_check" class="checkclick1"
                                                id="allowProductMeasurement" value="1">
                                            <label
                                                for="allowProductMeasurement">{{ __('Allow Product Measurement') }}</label>
                                        </div>

                                        <div class="showbox input-form">

                                            <h4 class="heading">{{ __('Product Measurement') }}</h4>
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
                                                    class="input-field" placeholder="{{ __('Enter Unit') }}">
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
                                            <h4 class="heading">
                                                {{ __('Youtube Video URL') }}
                                                <span>
                                                    {{ __('(Optional)') }}
                                                </span>
                                            </h4>
                                            <input name="youtube" type="text" class="input-field"
                                                placeholder="{{ __('Enter Youtube Video URL') }}">
                                        </div>



                                        <div class="checkbox-wrapper list list-personalizada">
                                            <input type="checkbox" name="seo_check" value="1" class="checkclick1"
                                                id="allowProductSEO" value="1">
                                            <label for="allowProductSEO">{{ __('Allow Product SEO') }} </label> <i
                                                class="icofont-question-circle" data-toggle="tooltip"
                                                style="display: inline-block " data-placement="top"
                                                title="{{ __('SEO (Search Engine Optimization) focuses on your website presence in search results on search engines like Google') }}"></i>
                                        </div>

                                        <div class="showbox">

                                            <div class="input-form">
                                                @component('admin.components.input-localized', ['type' => 'tags'])
                                                    @slot('name')
                                                        meta_tag
                                                    @endslot
                                                    {{ __('Meta Tags') }}
                                                @endcomponent
                                            </div>

                                            <div class="input-form">
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


                                    </div>
                                    <!--FINAL ROW COL-XL-6-->


                                </div>
                                <!--FINAL DA ROW DE DADOS EXTRAS-->
                               
                                <div class="title-section-form">
                                    <span>4</span>
                                    <h3>{{ __('Associate Colors') }}</h3>
                                </div>
                                <div class="max-width-div">
                                    <div class="product-wrapper">
                                        <div class="container-fluid">
                                            <div class="form-group">
                                                <label for="searchAssociatedColor">{{__('Look for the product')}}</label>
                                                <div class="d-flex">
                                                    <input id="searchAssociatedColor" class="form-control m-0" type="text" name="search" placeholder="{{__('Products name')}}">
                                                    <button id="buttonSearchAssociatedColor" class="btn btn-info" type="button">{{__('Search')}}</button>
                                                </div>
                                                <small id="emailHelp" class="form-text text-muted">{{__('Enter the name of the product you want to associate')}}</small>
                                            </div>
                                            <div id="boxAssociatedColor" class="row m-0"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="title-section-form">
                                    <span>5</span>
                                    <h3>{{ __('Associate Size') }}</h3>
                                </div>
                                <div class="max-width-div">
                                    <div class="product-wrapper">
                                        <div class="container-fluid">
                                            <div class="form-group">
                                                <label for="searchAssociatedSize">{{__('Look for the product')}}</label>
                                                <div class="d-flex">
                                                    <input id="searchAssociatedSize" class="form-control m-0" type="text" name="search" placeholder="{{__('Products name')}}">
                                                    <button id="buttonSearchAssociatedSize" class="btn btn-info" type="button">{{__('Search')}}</button>
                                                </div>
                                                <small id="emailHelp" class="form-text text-muted">{{__('Enter the name of the product you want to associate')}}</small>
                                            </div>
                                            <div id="boxAssociatedSize" class="row m-0"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="title-section-form">
                                    <span>6</span>
                                    <h3>{{ __('Associate Look') }}</h3>
                                </div>
                                <div class="max-width-div">
                                    <div class="product-wrapper">
                                        <div class="container-fluid">
                                            <div class="form-group">
                                                <label for="searchAssociatedLook">{{__('Look for the product')}}</label>
                                                    <div class="d-flex">
                                                        <input id="searchAssociatedLook" class="form-control m-0" type="text" name="search" placeholder="{{__('Products name')}}">
                                                        <button id="buttonSearchAssociatedLook" class="btn btn-info" type="button">{{__('Search')}}</button>
                                                    </div>
                                                    <small id="emailHelp" class="form-text text-muted">{{__('Enter the name of the product you want to associate')}}</small>
                                            </div>
                                            <div id="boxAssociatedLook" class="row m-0"></div>
                                        </div>
                                    </div>
                                </div>

                                @if (config('mercadolivre.is_active'))
                                    <div class="title-section-form">
                                        <span>4</span>
                                        <h3>
                                            {{ __('Mercado Livre') }}
                                        </h3>
                                    </div>

                                    <div class="row">
                                        <!-- ROW MERCADO LIVRE -->

                                        <div class="col-12">
                                            <div class="input-form">
                                                <h4 class="heading">{{ __('Mercado Livre Name') }} * <i
                                                        class="icofont-question-circle" data-toggle="tooltip"
                                                        style="display: inline-block " data-placement="top"
                                                        title="{{ __('Name to be shown at Mercado Livre Announcement') }}"></i>
                                                </h4>
                                                </h4>
                                                <input type="text" class="input-field"
                                                    placeholder="{{ __('Enter Mercado Livre Name') }}"
                                                    name="mercadolivre_name" required="" value="">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-form">
                                                <h4 class="heading">{{ __('Mercado Livre Description') }} * <i
                                                        class="icofont-question-circle" data-toggle="tooltip"
                                                        style="display: inline-block " data-placement="top"
                                                        title="{{ __('Description to be shown at Mercado Livre Announcement') }}"></i>
                                                </h4>
                                                </h4>
                                                <textarea class="input-field" name="mercadolivre_description"
                                                    placeholder="{{ __('Enter Mercado Livre Description') }}" cols="30" rows="10"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <input type="hidden" name="type" value="Physical">
                                <div class="row">
                                    <div class="col-xl-12 text-center">
                                        <button class="addProductSubmit-btn"
                                            type="submit">{{ __('Create Product') }}</button>
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
                                                        <div class="row mb-2">
                                                            <div class="col-3">
                                                                <input type="text" name="redplay_login[]"
                                                                    class="input-field m-0" placeholder="Login Redplay">
                                                            </div>
                                                            <div class="col-3">
                                                                <input type="text" name="redplay_password[]"
                                                                    class="input-field m-0" placeholder="Senha Redplay">
                                                            </div>
                                                            <div class="col-3">
                                                                <input type="text" name="redplay_code[]"
                                                                    class="input-field m-0" placeholder="Código Redplay">
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
                                                                <span class="badge badge-success ml-2">Disponível</span>
                                                            </div>

                                                        </div>
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

    <div class="modal fade" id="setgallery_color" tabindex="-1" role="dialog" aria-labelledby="setgallery_color"
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
                                    <label for="image-upload" id="prod_gallery_color"><i
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
                    <div class="gallery-images_color">
                        <div class="selected-image_color">
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

            $("#check3").change(function() {
                if ($(this).is(":checked")) {
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

                    $("#uploadgallery_color").prop("required", true);
                    $("#stock").attr("readonly", true);
                    $("#stock").addClass("disabled");
                } else {
                    $("#uploadgallery_color").prop("required", false);
                    $("#stock").attr("readonly", false);
                    $("#stock").removeClass("disabled");
                }
            });

            $("#check_material").change(function() {
                if ($(this).is(":checked")) {
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

                    $("#uploadgallery_material").prop("required", true);
                    $("#stock").attr("readonly", true);
                    $("#stock").addClass("disabled");
                } else {
                    $("#uploadgallery_material").prop("required", false);
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

        // Feature Section Ends

        // Gallery Section Insert

        $(document).on('click', '.remove-img', function() {
            var id = $(this).find('input[type=hidden]').val();
            $('#galval' + id).remove();
            $(this).parent().parent().remove();

            let carouselItem = $(`#gallery-${id}`)
            $('#carousel-gallery').find('.carousel-item').not('.active').first().addClass('active')
            carouselItem.remove()

            if ($('#carousel-gallery .carousel-item').length <= 0) {
                $('#carouselGallery').addClass('d-none')
            }

        });

        $(document).on('click', '.remove-img_color', function() {
            var id = $(this).find('input[type=hidden]').val();
            $('#galval_color' + id).remove();
            $(this).parent().parent().remove();
        });

        $(document).on('click', '.remove-img360', function() {
            var id = $(this).find('input[type=hidden]').val();
            $('#galval' + id).remove();
            $(this).parent().parent().remove();
        });

        $(document).on('click', '#prod_gallery', function() {
            $('#uploadgallery').click();
            $('.selected-image .row').html('');
            $('#geniusform').find('.removegal').val(0);
        });

        $(document).on('click', '#prod_gallery_color', function() {
            $('#uploadgallery_color').click();
            $('.selected-image_color .row').html('');
            $('#geniusform').find('.removegal').val(0);
            $('#carouselGallery').addClass('d-none')
            $('#carouselGallery .carousel-item').remove()
        });

        $(document).on('click', '#prod_gallery360', function() {
            $('#uploadgallery360').click();
            $('.selected-image360 .row').html('');
            $('#geniusform').find('.removegal').val(0);
        });

        var count = 1;
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
                '<input type="file" name="color_gallery[' + count +
                '][]" id="uploadgallery_color" accept="image/*" multiple required>' +
                '</div>' +
                '<div class="col-md-1">' +
                '<button type="button" class="btn btn-danger text-white color-remove-with-gallery"><i class="fa fa-times"></i></button>' +
                '</div>' +
                '</div>');
            count++;
            $('.cp').colorpicker();
        });

        var count_material = 1;
        $("#material-btn").on('click', function() {
            $("#material-section").append('' +
                '<div class="row">' +
                '<div class="col-md-3">' +
                '<div class="select-input-color">' +
                '<div class="color-area">' +
                '<div class="input-group">' +
                '<input type="text" name="material[' + count_material + ']" class="input-field"' +
                'placeholder="{{ __('Material Name') }}">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<input type="number" name="material_qty[' + count_material + ']" class="input-field"' +
                'placeholder="{{ __('Material Qty') }}" value="0" min="0" required>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<input type="number" step="0.01" name="material_price[' + count_material +
                ']" class="input-field"' +
                'placeholder="{{ __('Material Price') }}" value="0" min="0" required>' +
                '</div>' +
                '<div class="col-md-3 delete-button">' +
                '<input type="file" name="material_gallery[' + count_material +
                '][]" id="uploadgallery_material" accept="image/*" multiple required>' +
                '<button type="button" class="btn btn-danger text-white color-remove-with-gallery"><i class="fa fa-times"></i></button>' +
                '</div>' +
                '{{-- <div class="col-md-1">'+
                                '<button type="button" class="btn btn-danger text-white color-remove-with-gallery"><i class="fa fa-times"></i></button>'+
                            '</div> --}}' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');
            count_material++;
        });

        $(document).on('click', '.color-remove-with-gallery', function() {
            $(this.parentNode).parent().remove();
            if (isEmpty($('#color-section'))) {
                count = 1;
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
                    '<input type="file" name="color_gallery[' + count +
                    '][]" id="uploadgallery_color" accept="image/*" multiple required>' +
                    '</div>' +
                    '<div class="col-md-1">' +
                    '<button type="button" class="btn btn-danger text-white color-remove-with-gallery"><i class="fa fa-times"></i></button>' +
                    '</div>' +
                    '</div>');
                $('.cp').colorpicker();
            }
            if (isEmpty($('#material-section'))) {
                count_material = 1;
                $("#material-section").append('' +
                    '<div class="row">' +
                    '<div class="col-md-3">' +
                    '<div class="select-input-color">' +
                    '<div class="color-area">' +
                    '<div class="input-group">' +
                    '<input type="text" name="material[]" class="input-field"' +
                    'placeholder="{{ __('Material Name') }}">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<input type="number" name="material_qty[]" class="input-field"' +
                    'placeholder="{{ __('Material Qty') }}" value="0" min="0" required>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<input type="number" step="0.01" name="material_price[]" class="input-field"' +
                    'placeholder="{{ __('Material Price') }}" value="0" min="0" required>' +
                    '</div>' +
                    '<div class="col-md-3 delete-button">' +
                    '<input type="file" name="material_gallery[0][]" id="uploadgallery_material" accept="image/*" multiple required>' +
                    '<button type="button" class="btn btn-danger text-white color-remove-with-gallery"><i class="fa fa-times"></i></button>' +
                    '</div>' +
                    '</div>' +
                    '<a href="javascript:;" id="material-btn" class="add-more mt-4 mb-3"><i' +
                    'class="fas fa-plus"></i>{{ __('Add More Materials') }} </a>' +
                    '</div>' +
                    '</div>');
            }
        });

        $('#uploadgallery').change(function() {
            var total_file = document.getElementById('uploadgallery').files.length;
            for (var i = 0; i < total_file; i++) {
                $('#carousel-gallery').append('<div class="carousel-item" id="gallery-'+i
                    +'"><img class="d-block w-100" src="'
                    +URL.createObjectURL(event.target.files[i])+'"></div>'
                );
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

            if ($('#carousel-gallery .carousel-item').length > 0) {
                $('#carouselGallery').removeClass('d-none')
                $('#carousel-gallery .carousel-item').removeClass('active')
                $('#carousel-gallery').find('.carousel-item').not('.active').first().addClass('active')
            }

        });

        $('#uploadgallery360').change(function() {
            var total_file = document.getElementById('uploadgallery360').files.length;
            for (var i = 0; i < total_file; i++) {
                $('.selected-image360 .row').append('<div class="col-sm-6">' +
                    '<div class="img gallery-img">' +
                    '<span class="remove-img360"><i class="fas fa-times"></i>' +
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

        const boxAssociatedColor = $('#boxAssociatedColor')
        const boxAssociatedSize = $('#boxAssociatedSize')
        const boxAssociatedLook = $('#boxAssociatedLook')

        const associeteColorCheck = []
        const associeteSizeCheck = []
        const associeteLookCheck = []

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
                let checked = arrayChecks.includes(element.id) ? 'checked' : ''
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
                div.classList.add('col-md-6')
                let content = `
                <div class="box-options-assoc">
                    <input type="checkbox" id="produto_${element.id}_${sufixo}" name="${inputName}" value="${element.id}" ${checked}>
                    <label for="produto_${element.id}_${sufixo}">
                        <img src="/storage/images/thumbnails/${element.thumbnail}" 
                            class="img-circle mr-1"
                            width="40px">
                        <div>
                            <h6 class="m-0">${element.name}</h6>
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

    <script type="text/javascript">
        $('.cropme').simpleCropper();
        $('#crop-image').on('click', function() {
            $('.cropme').click();
        });
    </script>

    <script src="{{ asset('assets/admin/js/product.js') }}"></script>
@endsection
