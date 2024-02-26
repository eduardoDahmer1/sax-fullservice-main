@extends('layouts.load')

@section('styles')

<style>
    .img-upload #image-preview {
        width: 180px;
        height: 180px;
    }
</style>

@endsection

@section('content')
<div class="content-area">

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        @include('includes.admin.form-error')
                        <form id="geniusformdata" action="{{route('admin-service-update',$data->id)}}" method="POST"
                            enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="input-form">
                                        <p><small>* {{ __("indicates a required field") }}</small></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-xl-12">
                                    <div class="input-form">
                                        @component('admin.components.input-localized',["required" => true, "from" =>
                                        $data])
                                        @slot('name')
                                        title
                                        @endslot
                                        @slot('placeholder')
                                        {{ __('Title') }}
                                        @endslot
                                        @slot('value')
                                        title
                                        @endslot
                                        {{ __('Title') }}*
                                        @endcomponent
                                    </div>
                                </div>


                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">{{ __('Current Featured Image') }} *</h4>
                                        <div class="img-upload">
                                            <div
                                                style="width:180px;background-image: url( {{ asset('assets/admin/images/quadriculado.jpg')}} );background-size:contain;">
                                                <div id="image-preview" class="img-preview"
                                                    style="background-image:url('{{ $data->photo ? asset('storage/images/services/'.$data->photo):asset('assets/images/noimage.png') }}');background-size:100% !important;background-position:center !important;">
                                                    <label for="image-upload" class="img-label" id="image-label"><i
                                                            class="icofont-upload-alt"></i>{{ __('Upload Image')
                                                        }}</label>
                                                    <input type="file" name="photo" class="img-upload"
                                                        id="image-upload">
                                                </div>
                                            </div>
                                            <p class="text">{{ __('Prefered Size: (600x600) or Square Sized Image') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="input-form">
                                        @component('admin.components.input-localized',["type" => "textarea", "from" =>
                                        $data])
                                        @slot('name')
                                        details
                                        @endslot
                                        @slot('placeholder')
                                        {{ __('Description') }}
                                        @endslot
                                        @slot('value')
                                        details
                                        @endslot
                                        {{ __('Description') }} *
                                        @endcomponent
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="input-form">
                                        <h4 class="heading">{{ __('Link') }} <i class="icofont-question-circle"
                                                data-toggle="tooltip" style="display: inline-block "
                                                data-placement="top"
                                                title="{{ __('Link that will open when the object get clicked') }}"></i>
                                        </h4>
                                        <input type="text" class="input-field" name="link"
                                            placeholder="{{ __('Link') }}" value="{{ $data->link  }}">
                                    </div>
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
