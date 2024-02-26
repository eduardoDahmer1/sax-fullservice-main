@extends('layouts.vendor')
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Banner') }}</h4>

                    <ul class="links">
                        <li>
                            <a href="{{ route('vendor-dashboard') }}">{{ __('Dashbord') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Settings') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('vendor-banner') }}">{{ __('Banner') }}</a>
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
                            <form id="geniusform" action="{{ route('vendor-profile-update') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}


                                @include('includes.vendor.form-both')

                                <div class="row">

                                    <div class="left-area">
                                        <h4 class="heading" style="font-size: 23px!important;">{{ __('Current Banner') }} *
                                        </h4>
                                    </div>

                                    <div class="col-xl-12 input-form">
                                        <div class="img-upload full-width-img">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $data->shop_image ? asset('storage/images/vendorbanner/' . $data->shop_image) : asset('storage/images/vendor-noimage.png') }});">
                                                @if ($data->shop_image)
                                                    <button type="button" class="btn btn-danger m-2 remove-banner"
                                                        onclick="deleteImage()">x</button>
                                                @endif
                                                <label for="image-upload" class="img-label" id="image-label"><i
                                                        class="icofont-upload-alt"></i>{{ __('Upload Banner') }}</label>
                                                <input type="file" name="shop_image" class="img-upload"
                                                    id="image-upload">
                                            </div>
                                            <p class="text">{{ __('Prefered Size: (1920x220) Image') }}</p>
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
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function deleteImage() {
            $.ajax({
                url: '{{ route('vendor-delete-banner') }}',
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
