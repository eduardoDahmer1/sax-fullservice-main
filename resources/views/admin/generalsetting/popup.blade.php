@extends('layouts.admin')

@section('styles')
    <link href="{{ asset('assets/admin/css/jquery.Jcrop.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/Jcrop-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/cropper.min.css') }}" rel="stylesheet" />
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
                    <h4 class="heading">{{ __('Popup') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-popup') }}">{{ __('Popup') }}</a>
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
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form action="{{ route('admin-gs-update-popup') }}" id="geniusform" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row">

                                    <div class="col-xl-12">

                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['from' => $admstore])
                                                @slot('name')
                                                    popup_title
                                                @endslot
                                                @slot('placeholder')
                                                    {{ __('Popup Title') }}
                                                @endslot
                                                @slot('value')
                                                    popup_title
                                                @endslot
                                                {{ __('Popup Title') }} *
                                            @endcomponent
                                        </div>

                                    </div>

                                    <!--Crop -->
                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Feature Image') }} <span><i
                                                        class="icofont-question-circle" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{ __('Image will be adjusted based on screen resolution. Recommended size: 1024x600.') }}"></i></span>
                                            </h4>
                                            <div class="row">
                                                <div class="panel panel-body">
                                                    @if ($admstore->popup_background)
                                                        <div class="buttons">
                                                            <div class="deleteImage" onclick="deleteImage()"></div>
                                                        </div>
                                                    @endif
                                                    <div class="span4 cropme text-center" id="landscape"
                                                        style="width: 400px; height: 225px; border: 1px dashed black;background: url({{ $admstore->popup_background ? asset('storage/images/' . $admstore->popup_background) : asset('assets/images/noimage.png') }}); background-size: cover;">
                                                    </div>

                                                </div>
                                            </div>


                                            <a href="javascript:;" id="crop-image" class="d-inline-block mybtn1">
                                                <i class="icofont-upload-alt"></i> {{ __('Upload Image Here') }}
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['from' => $admstore, 'type' => 'textarea'])
                                                @slot('name')
                                                    popup_text
                                                @endslot
                                                @slot('value')
                                                    popup_text
                                                @endslot
                                                {{ __('Popup Text') }}
                                            @endcomponent
                                        </div>
                                    </div>

                                    <input type="hidden" id="feature_photo" name="popup_background"
                                        value="{{ $gs->popup_background }}">

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

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function deleteImage() {
            $.ajax({
                url: '{{ route('admin-gs-delete-pop') }}',
                type: 'POST',
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

    <script src="{{ asset('assets/admin/js/cropper.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.SimpleCropper.js') }}"></script>

    <script type="text/javascript">
        $('.cropme').simpleCropper({
            "landscape": true
        });
        $('#crop-image').on('click', function() {
            $('.cropme').click();
        });
    </script>
@endsection
