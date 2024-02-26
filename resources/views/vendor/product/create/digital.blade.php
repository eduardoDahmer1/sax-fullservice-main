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
                    <h4 class="heading">{{ __('Digital Product') }} <a class="add-btn"
                            href="{{ route('vendor-prod-types') }}"><i class="fas fa-arrow-left"></i>
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
                            <a href="{{ route('vendor-prod-index') }}">{{ __('All Products') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('vendor-prod-types') }}">{{ __('Add New Product') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('vendor-prod-digital-create') }}">{{ __('Digital Product') }}</a>
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


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Product Name') }}* </h4>
                                            <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" placeholder="{{ __('Product Name') }}"
                                            name="name" required="">
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
                                            <option value="">{{ __('Select Category') }}</option>
                                            @foreach ($cats as $cat)
                                                <option data-href="{{ route('vendor-subcat-load', $cat->id) }}"
                                                    value="{{ $cat->id }}">{{ $cat->name }}</option>
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
                                        <select id="subcat" name="subcategory_id" disabled="">
                                            <option value="">{{ __('Select Sub Category') }}</option>
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
                                        <select id="childcat" name="childcategory_id" disabled="">
                                            <option value="">{{ __('Select Child Category') }}</option>
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
                                            <option value="1">{{ __('Upload By File') }}</option>
                                            <option value="2">{{ __('Upload By Link') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row file">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Select File') }}*</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="file" name="file" required="">
                                    </div>
                                </div>

                                <div class="row link hidden">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Link') }}*</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <textarea class="input-field" rows="4" name="link" placeholder="{{ __('Link') }}"></textarea>
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
                                                style="background: url({{ asset('assets/vendor/images/upload.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label"><i
                                                        class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                <input type="file" name="photo" class="img-upload-p"
                                                    id="image-upload" required>
                                            </div>
                                            <p class="img-alert mt-2 text-danger d-none"></p>
                                            <p class="text">{{ __('Prefered Size: (800x800) or Square Size.') }}</p>
                                        </div>

                                    </div>
                                </div>



                                <input type="file" name="gallery[]" class="hidden" id="uploadgallery"
                                    accept="image/*" multiple>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Product Gallery Images') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <a href="#" class="set-gallery" data-toggle="modal"
                                            data-target="#setgallery">
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
                                            placeholder="{{ __('e.g 20') }}" required="" min="0">
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
                                            placeholder="{{ __('e.g 20') }}" min="0">
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
                                            <textarea name="details" class="trumboedit-p"></textarea>
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
                                            <textarea name="policy" class="trumboedit-p"></textarea>
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
                                            placeholder="{{ __('Youtube Video URL') }}">
                                        <div class="checkbox-wrapper">
                                            <input type="checkbox" name="seo_check" class="checkclick"
                                                id="allowProductSEO" value="1">
                                            <label for="allowProductSEO">{{ __('Allow Product SEO') }}</label>
                                        </div>
                                    </div>
                                </div>



                                <div class="showbox">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Allow Product SEO') }} *</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <ul id="metatags" class="myTags">
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
                                                <textarea name="meta_description" class="input-field" placeholder="{{ __('Meta Description') }}"></textarea>
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
                                                <div class="feature-area">
                                                    <span class="remove feature-remove"><i
                                                            class="fas fa-times"></i></span>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <input type="text" name="features[]" class="input-field"
                                                                placeholder="{{ __('Enter Your Keyword') }}">
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
                                        </ul>
                                    </div>
                                </div>

                                <input type="hidden" name="type" value="Digital">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7 text-center">
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
    <script src="{{ asset('assets/admin/js/jquery.Jcrop.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.SimpleCropper.js') }}"></script>

    <script type="text/javascript">
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
