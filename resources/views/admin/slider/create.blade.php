@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Add Slider') }} <a class="add-btn" href="{{ route('admin-sl-index') }}"><i
                                class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-sl-index') }}">{{ __('Sliders') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-sl-create') }}">{{ __('Add Slider') }}</a>
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
                            <form id="geniusform" action="{{ route('admin-sl-create') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            <p><small>* {{ __('indicates a required field') }}</small></p>
                                        </div>
                                    </div>
                                </div>
                                @include('includes.admin.form-both')
                                <div class="row">

                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['required' => true])
                                                @slot('name')
                                                    name
                                                @endslot
                                                @slot('placeholder')
                                                    {{ __('Enter Name') }}
                                                @endslot
                                                {{ __('Name') }}*
                                            @endcomponent
                                        </div>
                                    </div>

                                    {{-- <div class="col-xl-6"> --}}
                                        {{-- Title Section --}}

                                        {{-- <div class="panel panel-default slider-panel">
                                            <div class="panel-heading text-center">
                                                <h3>{{ __('Title') }}</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group"> --}}
                                                    {{-- <div class="col-sm-12">
                                                        <div class="panel panel-lang">
                                                            <div class="panel-body">
                                                                <div class="tab-content">
                                                                    <div class="tab-pane active" role="tabpanel"
                                                                        id="{{ $lang->locale }}-title_text">
                                                                        <label class="control-label"
                                                                            for="title_text_in_{{ $lang->locale }}">{{ __('Text') }}</label>
                                                                        <textarea class="form-control" name="{{ $lang->locale }}[title_text]" id="title_text_in_{{ $lang->locale }}"
                                                                            rows="5" placeholder="{{ __('Enter Title Text') }}"></textarea>
                                                                    </div>
                                                                    @foreach ($locales as $loc)
                                                                        @if ($loc->locale === $lang->locale)
                                                                            @continue
                                                                        @endif
                                                                        <div class="tab-pane" role="tabpanel"
                                                                            id="{{ $loc->locale }}-title_text">
                                                                            <label class="control-label"
                                                                                for="title_text_in_{{ $loc->locale }}">{{ __('Text') }}</label>
                                                                            <textarea class="form-control" name="{{ $loc->locale }}[title_text]" id="title_text_in_{{ $loc->locale }}"
                                                                                rows="5" placeholder="{{ __('Enter Title Text') }}"></textarea>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="panel-footer">
                                                                <ul class="nav nav-pills" role="tablist">
                                                                    <li role="presentation" class="active">
                                                                        <a href="#{{ $lang->locale }}-title_text"
                                                                            class="active"
                                                                            aria-controls="{{ $lang->locale }}-title_text"
                                                                            role="tab" data-toggle="tab">
                                                                            {{ $lang->language }}
                                                                        </a>
                                                                    </li>
                                                                    @foreach ($locales as $loc)
                                                                        @if ($loc->locale === $lang->locale)
                                                                            @continue
                                                                        @endif
                                                                        <li role="presentation">
                                                                            <a href="#{{ $loc->locale }}-title_text"
                                                                                aria-controls="{{ $loc->locale }}-title_text"
                                                                                role="tab" data-toggle="tab">
                                                                                {{ $loc->language }}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                {{-- </div> --}}

                                                {{-- <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <label class="control-label"
                                                                    for="title_size">{{ __('Font
                                                                                                                                    Size') }}</label>
                                                                <input class="form-control" type="number" name="title_size"
                                                                    value="" min="1" placeholder="Em pixels">
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="control-label"
                                                                    for="title_color">{{ __('Font
                                                                                                                                    Color') }}</label>
                                                                <div class="input-group colorpicker-component cp">
                                                                    <input type="text" name="title_color" value="#000000"
                                                                        class="form-control cp" />
                                                                    <span class="input-group-addon"><i></i></span>
                                                                </div>

                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="control-label"
                                                                    for="title_anime">{{ __('Animation') }}</label>
                                                                <select class="form-control" id="title_anime"
                                                                    name="title_anime">
                                                                    <option value="fadeIn">fadeIn</option>
                                                                    <option value="fadeInDown">fadeInDown</option>
                                                                    <option value="fadeInLeft">fadeInLeft</option>
                                                                    <option value="fadeInRight">fadeInRight</option>
                                                                    <option value="fadeInUp">fadeInUp</option>
                                                                    <option value="flip">flip</option>
                                                                    <option value="flipInX">flipInX</option>
                                                                    <option value="flipInY">flipInY</option>
                                                                    <option value="slideInUp">slideInUp</option>
                                                                    <option value="slideInDown">slideInDown</option>
                                                                    <option value="slideInLeft">slideInLeft</option>
                                                                    <option value="slideInRight">slideInRight</option>
                                                                    <option value="rollIn">rollIn</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Title Section Ends
                                    </div> --}}
                                    <!--FECHAMENTO TAG COL-XL-6-->

                                    {{-- <div class="col-xl-6">

                                        {{-- Sub Title Section
                                        <div class="panel panel-default slider-panel">
                                            <div class="panel-heading text-center">
                                                <h3>{{ __('Sub Title') }}</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="panel panel-lang">
                                                            <div class="panel-body">
                                                                <div class="tab-content">
                                                                    <div class="tab-pane active" role="tabpanel"
                                                                        id="{{ $lang->locale }}-subtitle_text">
                                                                        <label class="control-label"
                                                                            for="subtitle_text_in_{{ $lang->locale }}">{{ __('Text') }}</label>
                                                                        <textarea class="form-control" name="{{ $lang->locale }}[subtitle_text]" id="subtitle_text_in_{{ $lang->locale }}"
                                                                            rows="5" placeholder="{{ __('Enter Title Text') }}"></textarea>
                                                                    </div>
                                                                    @foreach ($locales as $loc)
                                                                        @if ($loc->locale === $lang->locale)
                                                                            @continue
                                                                        @endif
                                                                        <div class="tab-pane" role="tabpanel"
                                                                            id="{{ $loc->locale }}-subtitle_text">
                                                                            <label class="control-label"
                                                                                for="subtitle_text_in_{{ $loc->locale }}">{{ __('Text') }}</label>
                                                                            <textarea class="form-control" name="{{ $loc->locale }}[subtitle_text]" id="subtitle_text_in_{{ $loc->locale }}"
                                                                                rows="5" placeholder="{{ __('Enter Title Text') }}"></textarea>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="panel-footer">
                                                                <ul class="nav nav-pills" role="tablist">
                                                                    <li role="presentation" class="active">
                                                                        <a href="#{{ $lang->locale }}-subtitle_text"
                                                                            class="active"
                                                                            aria-controls="{{ $lang->locale }}-subtitle_text"
                                                                            role="tab" data-toggle="tab">
                                                                            {{ $lang->language }}
                                                                        </a>
                                                                    </li>
                                                                    @foreach ($locales as $loc)
                                                                        @if ($loc->locale === $lang->locale)
                                                                            @continue
                                                                        @endif
                                                                        <li role="presentation">
                                                                            <a href="#{{ $loc->locale }}-subtitle_text"
                                                                                aria-controls="{{ $loc->locale }}-subtitle_text"
                                                                                role="tab" data-toggle="tab">
                                                                                {{ $loc->language }}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}

                                                {{-- <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <label class="control-label"
                                                                    for="subtitle_size">{{ __('Font
                                                                                                                                    Size') }}</label>
                                                                <input class="form-control" type="number"
                                                                    name="subtitle_size" value="" min="1"
                                                                    placeholder="Em pixels">
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="control-label"
                                                                    for="subtitle_color">{{ __('Font Color') }}</label>
                                                                <div class="input-group colorpicker-component cp">
                                                                    <input type="text" name="subtitle_color"
                                                                        value="#000000" class="form-control cp" />
                                                                    <span class="input-group-addon"><i></i></span>
                                                                </div>

                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="control-label"
                                                                    for="subtitle_anime">{{ __('Animation') }}</label>
                                                                <select class="form-control" id="subtitle_anime"
                                                                    name="subtitle_anime">
                                                                    <option value="fadeIn">fadeIn</option>
                                                                    <option value="fadeInDown">fadeInDown</option>
                                                                    <option value="fadeInLeft">fadeInLeft</option>
                                                                    <option value="fadeInRight">fadeInRight</option>
                                                                    <option value="fadeInUp">fadeInUp</option>
                                                                    <option value="flip">flip</option>
                                                                    <option value="flipInX">flipInX</option>
                                                                    <option value="flipInY">flipInY</option>
                                                                    <option value="slideInUp">slideInUp</option>
                                                                    <option value="slideInDown">slideInDown</option>
                                                                    <option value="slideInLeft">slideInLeft</option>
                                                                    <option value="slideInRight">slideInRight</option>
                                                                    <option value="rollIn">rollIn</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Sub Title Section Ends 
                                    </div>--}}
                                    <!--FECHAMENTO TAG COL-XL-6-->

                                    {{-- <div class="col-xl-6">


                                        <div class="panel panel-default slider-panel">
                                            <div class="panel-heading text-center">
                                                <h3>{{ __('Description') }}</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="panel panel-lang">
                                                            <div class="panel-body">
                                                                <div class="tab-content">
                                                                    <div class="tab-pane active" role="tabpanel"
                                                                        id="{{ $lang->locale }}-details_text">
                                                                        <label class="control-label"
                                                                            for="details_text_in_{{ $lang->locale }}">{{ __('Text') }}</label>
                                                                        <textarea class="form-control" name="{{ $lang->locale }}[details_text]" id="details_text_in_{{ $lang->locale }}"
                                                                            rows="5" placeholder="{{ __('Enter Title Text') }}"></textarea>
                                                                    </div>
                                                                    @foreach ($locales as $loc)
                                                                        @if ($loc->locale === $lang->locale)
                                                                            @continue
                                                                        @endif
                                                                        <div class="tab-pane" role="tabpanel"
                                                                            id="{{ $loc->locale }}-details_text">
                                                                            <label class="control-label"
                                                                                for="details_text_in_{{ $loc->locale }}">{{ __('Text') }}</label>
                                                                            <textarea class="form-control" name="{{ $loc->locale }}[details_text]" id="details_text_in_{{ $loc->locale }}"
                                                                                rows="5" placeholder="{{ __('Enter Title Text') }}"></textarea>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="panel-footer">
                                                                <ul class="nav nav-pills" role="tablist">
                                                                    <li role="presentation" class="active">
                                                                        <a href="#{{ $lang->locale }}-details_text"
                                                                            class="active"
                                                                            aria-controls="{{ $lang->locale }}-details_text"
                                                                            role="tab" data-toggle="tab">
                                                                            {{ $lang->language }}
                                                                        </a>
                                                                    </li>
                                                                    @foreach ($locales as $loc)
                                                                        @if ($loc->locale === $lang->locale)
                                                                            @continue
                                                                        @endif
                                                                        <li role="presentation">
                                                                            <a href="#{{ $loc->locale }}-details_text"
                                                                                aria-controls="{{ $loc->locale }}-details_text"
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

                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <label class="control-label"
                                                                    for="details_size">{{ __('Font
                                                                                                                                    Size') }}
                                                                </label>
                                                                <input class="form-control" type="number"
                                                                    name="details_size" value="" min="1"
                                                                    placeholder="Em pixels">
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="control-label"
                                                                    for="details_color">{{ __('Font
                                                                                                                                    Color') }}
                                                                </label>
                                                                <div class="input-group colorpicker-component cp">
                                                                    <input type="text" name="details_color"
                                                                        value="#000000" class="form-control cp" />
                                                                    <span class="input-group-addon"><i></i></span>
                                                                </div>

                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="control-label"
                                                                    for="details_anime">{{ __('Animation') }} </label>
                                                                <select class="form-control" id="details_anime"
                                                                    name="details_anime">
                                                                    <option value="fadeIn">fadeIn</option>
                                                                    <option value="fadeInDown">fadeInDown</option>
                                                                    <option value="fadeInLeft">fadeInLeft</option>
                                                                    <option value="fadeInRight">fadeInRight</option>
                                                                    <option value="fadeInUp">fadeInUp</option>
                                                                    <option value="flip">flip</option>
                                                                    <option value="flipInX">flipInX</option>
                                                                    <option value="flipInY">flipInY</option>
                                                                    <option value="slideInUp">slideInUp</option>
                                                                    <option value="slideInDown">slideInDown</option>
                                                                    <option value="slideInLeft">slideInLeft</option>
                                                                    <option value="slideInRight">slideInRight</option>
                                                                    <option value="rollIn">rollIn</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Description Section Ends

                                    </div> --}}

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Current Featured Image') }} *</h4>
                                            <div class="img-upload full-width-img">
                                                <div id="image-preview" class="img-preview"
                                                    style="background: url('{{ asset('assets/admin/images/upload.png') }}');">
                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                    <input type="file" name="photo" class="img-upload"
                                                        id="image-upload">
                                                </div>
                                                <p class="text">
                                                    {{ __('Prefered Size: (1920x560) or Rectangular Sized Image') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Link') }}<i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block "
                                                    data-placement="top"
                                                    title="{{ __('Link that will open when the object get clicked') }}"></i>
                                            </h4>
                                            <input type="text" class="input-field" name="link"
                                                placeholder="{{ __('Link') }}" value="">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Text Position') }}*</h4>
                                            <select name="position" required="">
                                                <option value="">{{ __('Select Position') }}</option>
                                                <option value="slide-one">{{ __('Left') }}</option>
                                                <option value="slide-two">{{ __('Center') }}</option>
                                                <option value="slide-three">{{ __('Right') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Display in Stores') }} </h4>
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

                                </div>
                                <!--FECHAMENTO TAG ROW-->

                                <div class="row justify-content-center">

                                    <button class="addProductSubmit-btn"
                                        type="submit">{{ __('Create Slider') }}</button>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
