@extends('layouts.load')
@section('content')
    <div class="content-area">
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            @include('includes.admin.form-error')
                            <form id="geniusformdata" action="{{ route('admin-staff-update', $data->id) }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            <p><small>* {{ __('indicates a required field') }}</small></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Staff Profile Image') }}</h4>
                                            <div class="img-upload">
                                                <div id="image-preview" class="img-preview"
                                                    style="background: url('{{ $data->photo ? asset('storage/images/admins/' . $data->photo) : asset('assets/images/noimage.png') }}');">
                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                    <input type="file" name="photo" class="img-upload"
                                                        id="image-upload">
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
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Email') }} *</h4>
                                            <input type="email" class="input-field" name="email"
                                                placeholder="{{ __('Email Address') }}" required=""
                                                value="{{ $data->email }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Phone') }}</h4>
                                            <input type="text" class="input-field" name="phone"
                                                placeholder="{{ __('Phone Number') }}" value="{{ $data->phone }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Role') }} *</h4>
                                            <select name="role_id" required="">
                                                <option value="">{{ __('Select Role') }}</option>
                                                @foreach ($roles as $dta)
                                                    <option value="{{ $dta->id }}"
                                                        {{ $data->role_id == $dta->id ? 'selected' : '' }}>
                                                        {{ $dta->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Password') }} *</h4>
                                            <input type="password" id="staff_password" class="input-field" name="password"
                                                placeholder="{{ __('Password') }}" value="">
                                            <span toggle="#staff_password"
                                                class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        </div>
                                    </div>

                                </div>
                                <!--FECHAMENTO TAG ROW-->

                                <div class="row">

                                    <div class="col-lg-7">

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
        $('.toggle-password').click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
@endsection
