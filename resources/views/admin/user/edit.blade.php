@extends('layouts.load')
@section('content')
    <div class="content-area">
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            @include('includes.admin.form-error')
                            <form id="geniusformdata" action="{{ route('admin-user-edit', $data->id) }}" method="POST"
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
                                            <h4 class="heading">{{ __('Customer Profile Image') }} *</h4>
                                            <div class="img-upload">

                                                <div id="image-preview" class="img-preview"
                                                    style="background: url('{{ $data->photoUrl }}');">

                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                    <input type="file" name="photo" class="img-upload"
                                                        id="image-upload">

                                                </div>
                                                <p class="text">{{ __('Prefered Size: (600x600) or Square Sized Image') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Name') }} *</h4>
                                            <input type="text" class="input-field" name="name"
                                                placeholder="{{ __(' User Name') }}" required=""
                                                value="{{ $data->name }}">
                                        </div>

                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Phone') }} *</h4>
                                            <input type="text" class="input-field" name="phone"
                                                placeholder="{{ __('Phone Number') }}" required=""
                                                value="{{ $data->phone }}">
                                        </div>

                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Email') }} *</h4>
                                            <input type="email" class="input-field" name="email"
                                                placeholder="{{ __('Email Address') }}" value="{{ $data->email }}"
                                                disabled="">
                                        </div>

                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Address') }} *</h4>
                                            <input type="text" class="input-field" name="address"
                                                placeholder="{{ __('Address') }}" required=""
                                                value="{{ $data->address }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('City') }} </h4>
                                            <input type="text" class="input-field" name="phone"
                                                placeholder="{{ __('City') }}" value="{{ $data->phone }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Fax') }} </h4>
                                            <input type="text" class="input-field" name="fax"
                                                placeholder='{{ __('Fax') }}' value="{{ $data->fax }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Postal Code') }} </h4>
                                            <input type="text" class="input-field" name="zip"
                                                placeholder="{{ __('Postal Code') }}" value="{{ $data->zip }}">
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
