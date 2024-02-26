@extends('layouts.load')
@section('content')
    <div class="content-area">
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            @include('includes.admin.form-error')
                            <form id="geniusformdata" action="{{ route('admin-vendor-edit', $data->id) }}" method="POST"
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
                                            <h4 class="heading">{{ __('Email') }} *</h4>
                                            <input type="email" class="input-field" name="email"
                                                placeholder="{{ __('Email Address') }}" value="{{ $data->email }}"
                                                disabled="">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Shop Name') }} *</h4>
                                            <input type="text" class="input-field" name="shop_name"
                                                placeholder="{{ __('Shop Name') }}" required=""
                                                value="{{ $data->shop_name }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Shop Details') }} *</h4>
                                            <textarea class="input-field" name="shop_details" rows="4">{{ $data->shop_details }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Owner Name') }} *</h4>
                                            <input type="text" class="input-field" name="owner_name"
                                                placeholder="{{ __('Owner Name') }}" required=""
                                                value="{{ $data->owner_name }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Shop Number') }} *</h4>
                                            <input type="text" class="input-field" name="shop_number"
                                                placeholder="{{ __('Shop Number') }}" required=""
                                                value="{{ $data->shop_number }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Shop Address') }} *</h4>
                                            <input type="text" class="input-field" name="shop_address"
                                                placeholder="{{ __('Shop Address') }}" required=""
                                                value="{{ $data->shop_address }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Registration Number') }}
                                                <span>{{ __('(This Field is Optional)') }}</span>
                                            </h4>
                                            <input type="text" class="input-field" name="reg_number"
                                                placeholder="Registration Number" value="{{ $data->reg_number }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['from' => $data])
                                                @slot('name')
                                                    shop_message
                                                @endslot
                                                @slot('placeholder')
                                                    {{ __('Message') }}
                                                @endslot
                                                @slot('value')
                                                    shop_message
                                                @endslot
                                                {{ __('Message') }}
                                            @endcomponent
                                        </div>
                                    </div>

                                </div>
                                <!--FECHAMENTO TAG ROW-->


                                <div class="row justify-content-center">

                                    <button class="addProductSubmit-btn" type="submit">{{ __('Submit') }}</button>

                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
