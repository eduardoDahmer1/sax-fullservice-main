@extends('layouts.vendor')
@section('styles')
    <link href="{{ asset('assets/vendor/css/product.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/jquery.Jcrop.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/Jcrop-style.css') }}" rel="stylesheet" />
@endsection
@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading"> {{ __('Edit Product') }}<a class="add-btn"
                            href="{{ route('vendor-prod-index') }}"><i class="fas fa-arrow-left"></i>
                            {{ __('Back') }}</a>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('vendor-dashboard') }}">{{ __('Dashbord') }}</a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Products') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Digital Product') }}</a>
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

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Product Name') }}* </h4>
                                            <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" placeholder="{{ __(' Product Name') }}"
                                            name="name" required="" value="{{ $data->name }}">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Category') }}*</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <select id="cat" name="category_id" required="">
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

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Sub Category') }}*</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
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

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Child Category') }}*</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <select id="childcat" name="childcategory_id"
                                            {{ $data->subcategory_id == null ? 'disabled' : '' }}>
                                            <option value="">{{ __('Select Child Category') }}</option>
                                            @if ($data->subcategory_id != null)
                                                @if ($data->childcategory_id == null)
                                                    @foreach ($data->subcategory->childs as $child)
                                                        <option value="{{ $child->id }}">{{ $child->name }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach ($data->subcategory->childs as $child)
                                                        <option value="{{ $child->id }} "
                                                            {{ $child->id == $data->childcategory_id ? 'selected' : '' }}>
                                                            {{ $child->name }}</option>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Select Upload Type') }}*</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <select id="type_check" name="type_check">
                                            <option value="1" {{ $data->file != null ? 'selected' : '' }}>
                                                {{ __('Upload By File') }}
                                            </option>
                                            <option value="2" {{ $data->link != null ? 'selected' : '' }}>
                                                {{ __('Upload By Link') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row file {{ $data->file != null ? '' : 'hidden' }}">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Select File') }}*</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="file" name="file">
                                    </div>
                                </div>

                                <div class="row link {{ $data->link != null ? '' : 'hidden' }}">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Link') }}*</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <textarea class="input-field" rows="4" name="link" placeholder="{{ __(' Link') }}"
                                            {{ $data->link != null ? 'required' : '' }}>{{ $data->link }}</textarea>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Current Featured Image') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ filter_var($data->photo, FILTER_VALIDATE_URL)
                                                    ? $data->photo
                                                    : asset('storage/images/products/' . $data->photo) }});">
                                                <label for="image-upload" class="img-label" id="image-label"><i
                                                        class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                <input type="file" name="photo" class="img-upload"
                                                    id="image-upload">
                                            </div>
                                            <p class="img-alert mt-2 text-danger d-none"></p>
                                            <p class="text">{{ __('Prefered Size: (800x800) or Square Size.') }}</p>
                                        </div>

                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Product Gallery Images') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <a href="javascript" class="set-gallery" data-toggle="modal"
                                            data-target="#setgallery">
                                            <input type="hidden" value="{{ $data->id }}">
                                            <i class="icofont-plus"></i> {{ __('Set Gallery') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Product Current Price') }}*
                                            </h4>
                                            <p class="sub-heading">
                                                ({{ __('In') }} {{ $sign->name }})
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input name="price" step="0.1" type="number" class="input-field"
                                            placeholder="{{ __(' e.g 20') }}"
                                            value="{{ round($data->price * $sign->value, 2) }}" required=""
                                            min="0">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Product Previous Price') }}*</h4>
                                            <p class="sub-heading">{{ __('(Optional)') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input name="previous_price" step="0.1" type="number" class="input-field"
                                            placeholder="{{ __(' e.g 20') }}"
                                            value="{{ round($data->previous_price * $sign->value, 2) }}" min="0">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Product Description') }}*
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="text-editor">
                                            <textarea class="trumboedit-p" name="details">{{ $data->details }}</textarea>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Product Buy\/Return Policy') }}*
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="text-editor">
                                            <textarea class="trumboedit-p" name="policy">{{ $data->policy }}</textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Youtube Video URL') }}*</h4>
                                            <p class="sub-heading">{{ __('(Optional)') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input name="youtube" type="text" class="input-field"
                                            placeholder="{{ __('Youtube Video URL') }}" value="{{ $data->youtube }}">
                                        <div class="checkbox-wrapper">
                                            <input type="checkbox" name="seo_check" value="1" class="checkclick"
                                                id="allowProductSEO"
                                                {{ $data->meta_tag != null || strip_tags($data->meta_description) != null ? 'checked' : '' }}>
                                            <label for="allowProductSEO">{{ __('Allow Product SEO') }}</label>
                                        </div>
                                    </div>
                                </div>



                                <div
                                    class="{{ $data->meta_tag == null && strip_tags($data->meta_description) == null
                                        ? "
                                                                                                            								showbox"
                                        : '' }}">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Meta Tags') }} *</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <ul id="metatags" class="myTags">
                                                @if (!empty($data->meta_tag))
                                                    @foreach ($data->meta_tag as $element)
                                                        <li>{{ $element }}</li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="left-area">
                                                <h4 class="heading">
                                                    {{ __('Meta Description') }} *
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="text-editor">
                                                <textarea name="meta_description" class="input-field" placeholder="{{ __('Meta Description') }}">{{ $data->meta_description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="featured-keyword-area">
                                            <div class="heading-area">
                                                <h4 class="title">{{ __('Feature Tags') }}</h4>
                                            </div>

                                            <div class="feature-tag-top-filds" id="feature-section">
                                                @if (!empty($data->features))
                                                    @foreach ($data->features as $key => $data1)
                                                        <div class="feature-area">
                                                            <span class="remove feature-remove"><i
                                                                    class="fas fa-times"></i></span>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <input type="text" name="features[]"
                                                                        class="input-field"
                                                                        placeholder="{{ __(' Enter Your Keyword') }}"
                                                                        value="{{ $data->features[$key] }}">
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
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="feature-area">
                                                        <span class="remove feature-remove"><i
                                                                class="fas fa-times"></i></span>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input type="text" name="features[]"
                                                                    class="input-field"
                                                                    placeholder="{{ __(' Enter Your Keyword') }}">
                                                            </div>

                                                            <div class="col-lg-6">
                                                                <div class="input-group colorpicker-component cp">
                                                                    <input type="text" name="colors[]" value="#000000"
                                                                        class="input-field cp" />
                                                                    <span class="input-group-addon"><i></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <a href="javascript:;" id="feature-btn" class="add-fild-btn"><i
                                                    class="icofont-plus"></i> {{ __('Add More Field') }}</a>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Tags') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <ul id="tags" class="myTags">
                                            @if (!empty($data->tags))
                                                @foreach ($data->tags as $element)
                                                    <li>{{ $element }}</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7 text-center">
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
    <script type="text/javascript">
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
                `<img src="{{ empty($data->photo) ? asset('assets/images/noimage.png') : asset('storage/images/products/' . $data->photo) }}" alt="">`;
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

    <script src="{{ asset('assets/admin/js/product.js') }}"></script>
@endsection
