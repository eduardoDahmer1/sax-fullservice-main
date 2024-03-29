@extends('layouts.admin')
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Edit Profile') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.profile') }}">{{ __('Edit Profile') }} </a>
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
                            <form id="geniusform" action="{{ route('admin.profile.update') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row">

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Current Profile Image') }} *</h4>
                                            <div class="img-upload">
                                                <div id="image-preview" class="img-preview"
                                                    style="background: url('{{ $data->photoUrl }}');">
                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                    <input type="file" name="photo" class="img-upload"
                                                        id="image-upload">
                                                    @if ($data->photo)
                                                        <div class="buttons">
                                                            <div class="deleteImage" onclick="deleteImage()"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Name') }} *</h4>
                                            <input type="text" class="input-field" name="name"
                                                placeholder="{{ __('User Name') }}" required=""
                                                value="{{ $data->name }}">
                                        </div>

                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Email') }} *</h4>
                                            <input type="email" class="input-field" name="email"
                                                placeholder="{{ __('Email Address') }}" required=""
                                                value="{{ $data->email }}">
                                        </div>

                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Phone') }} *</h4>
                                            <input type="text" class="input-field" name="phone"
                                                placeholder="{{ __('Phone Number') }}" required=""
                                                value="{{ $data->phone }}">
                                        </div>
                                    </div>

                                    @if (Auth::guard('admin')->user()->role_id == 0)
                                        <div class="col-xl-6">
                                            <div class="input-form">
                                                <h4 class="heading">{{ __('Shop Name') }} *</h4>
                                                <input type="text" class="input-field" name="shop_name"
                                                    placeholder="{{ __('Shop Name') }}" required=""
                                                    value="{{ $data->shop_name }}">
                                            </div>
                                        </div>
                                    @endif

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
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function deleteImage() {
            $.ajax({
                url: '{{ route('admin.delete.image') }}',
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
