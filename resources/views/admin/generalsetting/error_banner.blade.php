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
                    <h4 class="heading">{{ __('Page Not Found') }} </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Page Not Found') }} </a>
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
                            <form id="geniusform" action="{{ route('admin-gs-update') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row">

                                    <div class="col-xl-5">
                                        <div class="input-form">
                                            <h4 class="heading"> {{ __('Banner Image') }} * <i
                                                    class="icofont-question-circle" data-toggle="tooltip"
                                                    style="display: inline-block " data-placement="top"
                                                    title="{{ __('Banner that will appear when the link cannot be found') }}"></i>
                                                <span>{{ __('(Preferred Size: 600 X 600 Pixel)') }}</span>
                                            </h4>

                                            <div class="img-upload">
                                                <div id="image-preview" class="img-preview"
                                                    style="position:relative;background: url({{ $admstore->errorBannerUrl }});">
                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                    <input type="file" name="error_banner" class="img-upload"
                                                        id="image-upload">
                                                    @if ($admstore->error_banner)
                                                        <div class="buttons">
                                                            <div class="deleteImage" onclick="deleteImage()"></div>
                                                        </div>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-7">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['from' => $admstore, 'type' => 'richtext'])
                                                @slot('name')
                                                    page_not_found_text
                                                @endslot
                                                @slot('value')
                                                    page_not_found_text
                                                @endslot
                                                {{ __('Page Not Found Text') }} * <i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block " data-placement="top"
                                                    title="{{ __('Text that will appear when the link cannot be found') }}"></i>
                                            @endcomponent
                                        </div>
                                    </div>

                                </div>

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
                url: '{{ route('admin-gs-delete-error-banner') }}',
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
@endsection
