@extends('layouts.vendor')
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Edit Profile') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('vendor-dashboard') }}">{{ __('Dashbord') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('vendor-profile') }}">{{ __('Edit Profile') }} </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    @include('includes.vendor.form-both')
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 offset-3">
                    <div class="input-form">
                        <h4 class="heading">{{ __('Current Profile Image') }} *</h4>
                        <form id="geniusform" action="{{ route('vendor-profile-update') }}" method="POST"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="img-upload">
                                <div id="image-preview" class="img-preview"
                                    style="background: url('{{ $data->photo ? asset('storage/images/users/' . $data->photo) : asset('assets/images/noimage.png') }}');">
                                    <label for="image-upload" class="img-label" id="image-label"><i
                                            class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                    <input type="file" name="photo" class="img-upload" id="image-upload">
                                    @if ($data->photo)
                                        <div class="buttons">
                                            <div class="deleteImage" onclick="deleteImage()"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Shop Name') }}: </h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="right-area">
                                        <h6 class="heading"> {{ $data->shop_name }}
                                            @if ($data->checkStatus())
                                                <a class="badge badge-success verify-link"
                                                    href="javascript:;">{{ __('Verified') }}</a>
                                            @else
                                                <span class="verify-link"><a
                                                        href="{{ route('vendor-verify') }}">{{ __('Verify Account') }}</a></span>
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Corporate Name') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="vendor_corporate_name"
                                        placeholder="{{ __('Corporate Name') }}" required=""
                                        value="{{ $data->vendor_corporate_name }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Phone') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="vendor_phone"
                                        placeholder="{{ __(' Phone') }}" required=""
                                        value="{{ $data->vendor_phone }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Opening Hours') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="vendor_opening_hours"
                                        placeholder="{{ __('Opening Hours') }}" required=""
                                        value="{{ $data->vendor_opening_hours }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Payment Methods') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="vendor_payment_methods"
                                        placeholder="{{ __(' Payment Methods') }}" required=""
                                        value="{{ $data->vendor_payment_methods }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Delivery Info') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="vendor_delivery_info"
                                        placeholder="{{ __('Delivery Info') }}" required=""
                                        value="{{ $data->vendor_delivery_info }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Registration Number') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="reg_number"
                                        placeholder="{{ __('Registration Number') }}" value="{{ $data->reg_number }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Owner Name') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="owner_name"
                                        placeholder="{{ __('Owner Name') }}" required=""
                                        value="{{ $data->owner_name }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Shop Number') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="shop_number"
                                        placeholder="{{ __('Shop Number') }}" required=""
                                        value="{{ $data->shop_number }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Shop Address') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="shop_address"
                                        placeholder="{{ __('Shop Address') }}" required=""
                                        value="{{ $data->shop_address }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Embed Google Maps') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="vendor_map_embed"
                                        placeholder="{{ __('Paste Google Maps Embed here') }}"
                                        value="{{ $data->vendor_map_embed }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Shop Details') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <textarea class="input-field" name="shop_details" placeholder="{{ __(' Shop Details') }}">{{ $data->shop_details }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                    </div>
                                </div>
                                <div class="col-lg-7">
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
                url: '{{ route('vendor-delete.image') }}',
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
