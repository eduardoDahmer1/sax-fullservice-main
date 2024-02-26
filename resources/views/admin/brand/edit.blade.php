@extends('layouts.load')

@section('content')
<div class="alert alert-success validation" style="display: none;">
    <button type="button" class="close alert-close"><span>×</span></button>
    <p class="text-left"></p>
</div>
<div class="content-area">

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        @include('includes.admin.form-error')
                        <form id="geniusformdata" action="{{route('admin-brand-update',$data->id)}}" method="POST"
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
                                        <h4 class="heading">{{ __('Name') }} *</h4>
                                        <input type="text" class="input-field" name="name"
                                            placeholder="{{ __('Enter Name') }}" required="" value="{{$data->name}}">
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">{{ __('Current Image') }}</h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $data->image ? asset('storage/images/brands/'.$data->image):asset('assets/images/noimage.png') }});">
                                                @if ($data->image)
                                                <div class="buttons">
                                                    <div class="deleteImage"
                                                        onclick="deleteImage({{$data->id}}, 'image', this)"></div>
                                                </div>
                                                @endif
                                                <label for="image-upload" class="img-label" id="image-label"><i
                                                        class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                <input type="file" name="image" class="img-upload" id="image-upload">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">{{ __('Banner') }} *</h4>
                                        <div class="img-upload full-width-img">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url('{{ $data->banner ? asset('storage/images/brands/banners/'.$data->banner):asset('assets/images/noimage.png') }}');">
                                                <label for="image-upload" class="img-label" id="image-label"><i
                                                        class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                <input type="file" name="banner" class="img-upload" id="image-upload">
                                            </div>
                                            <p class="text">{{ __('Prefered Size: (1920x400) or Rectangular Sized
                                                Image') }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!--FECHAMENT TAG ROW-->

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
    $(document).ready(function(){
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
		});
</script>
@endsection
