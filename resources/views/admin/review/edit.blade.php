@extends('layouts.load')

@section('content')
<div class="content-area">

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        @include('includes.admin.form-error')
                        <form id="geniusformdata" action="{{route('admin-review-update',$data->id)}}" method="POST"
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

                                <div class="col-xl-6">

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
                                        {{ __('Title') }} *
                                        @endcomponent
                                    </div>

                                    <div class="input-form">
                                        @component('admin.components.input-localized',["required" => true, "from" =>
                                        $data])
                                        @slot('name')
                                        subtitle
                                        @endslot
                                        @slot('placeholder')
                                        {{ __('Subtitle') }}
                                        @endslot
                                        @slot('value')
                                        subtitle
                                        @endslot
                                        {{ __('Subtitle') }} *
                                        @endcomponent
                                    </div>

                                </div>

                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">{{ __('Current Featured Image') }} *</h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url('{{ $data->photo ? asset('storage/images/reviews/'.$data->photo):asset('assets/images/noimage.png') }}');">
                                                <label for="image-upload" class="img-label" id="image-label"><i
                                                        class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                <input type="file" name="photo" class="img-upload" id="image-upload">
                                            </div>
                                            <p class="text">{{ __('Prefered Size: (100x100) or Square Sized Image') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="input-form">
                                        @component('admin.components.input-localized',["type" => "richtext", "required"
                                        => true, "from" => $data])
                                        @slot('name')
                                        details
                                        @endslot
                                        @slot('value')
                                        details
                                        @endslot
                                        {{ __('Description') }}
                                        @endcomponent
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
