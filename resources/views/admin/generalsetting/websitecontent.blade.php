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
                    <h4 class="heading">{{ __('Colors') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-logo') }}">{{ __('Theme') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-contents') }}">{{ __('Colors') }}</a>
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
        @include('includes.admin.partials.theme-tabs')
        <div class="add-product-content colors-themes">
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

                                <div class="title-section-form">
                                    <span>1</span>
                                    <h3>
                                        {{ __('Primary Colors') }}
                                    </h3>
                                </div>

                                <div class="row">

                                    <div class="col-xl-3">

                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Theme') }} *</h4>
                                            <div class="form-group">
                                                <div class="input-group colorpicker-component cp">
                                                    <input type="text" class="input-field color-field" name="colors"
                                                        value="{{ $admstore->colors }}" class="form-control cp" />
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Text') }} *
                                                <span>{{ __('Text over the primary theme color.') }}</span>
                                            </h4>
                                            <div class="form-group">
                                                <div class="input-group colorpicker-component cp">
                                                    <input class="input-field color-field" type="text"
                                                        name="copyright_color" value="{{ $admstore->copyright_color }}"
                                                        class="form-control cp" />
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="title-section-form">
                                    <span>2</span>
                                    <h3>
                                        {{ __('Secondary Colors') }}
                                    </h3>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Theme') }} *</h4>
                                            <div class="form-group">
                                                <div class="input-group colorpicker-component cp">
                                                    <input type="text" class="input-field color-field"
                                                        name="header_color" value="{{ $admstore->header_color }}"
                                                        class="form-control cp" />
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Text') }} *
                                                <span>{{ __('Text over the secondary theme color.') }}</span>
                                            </h4>
                                            <div class="form-group">
                                                <div class="input-group colorpicker-component cp">
                                                    <input class="input-field color-field" type="text"
                                                        name="footer_text_color" value="{{ $admstore->footer_text_color }}"
                                                        class="form-control cp" />
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="title-section-form">
                                    <span>3</span>
                                    <h3>
                                        {{ __('Other Colors') }}
                                    </h3>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Reference Code Color') }} *</h4>
                                            <div class="form-group">
                                                <div class="input-group colorpicker-component cp">
                                                    <input type="text" class="input-field color-field" name="ref_color"
                                                        value="{{ $admstore->ref_color }}" class="form-control cp" />
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
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
