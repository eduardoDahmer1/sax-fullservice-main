@extends('layouts.load')
@section('content')
<div class="content-area">
    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        @include('includes.admin.form-error')
                        <form id="geniusformdata" action="{{route('admin-team_member-update',$data->id)}}" method="POST"
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
                                        <h4 class="heading">{{ __('Category') }}*</h4>
                                        <select name="category_id" required="">
                                            <option value="">{{ __('Select Category') }}</option>
                                            @foreach($cats as $cat)
                                            <option value="{{ $cat->id }}" {{ $data->category_id == $cat->id ?
                                                'selected' :'' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="input-form">
                                        <h4 class="heading">{{ __('Name') }} *
                                            <span>{{ __('(In Any Language)') }}</span>
                                        </h4>
                                        <input type="text" class="input-field" name="name" placeholder="Name"
                                            value="{{$data->name}}" required="">
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">{{ __('Current Featured Image') }} *</h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url('{{ $data->photo ? asset('storage/images/team_member/'.$data->photo):asset('assets/images/noimage.png') }}');">
                                                <label for="image-upload" class="img-label" id="image-label"><i
                                                        class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                                <input type="file" name="photo" class="img-upload" id="image-upload">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">{{ __('Whatsapp') }}</h4>
                                        <input type="text" class="input-field" name="whatsapp"
                                            placeholder="{{ __('Whatsapp') }}" value="{{$data->whatsapp}}">
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">{{ __('Skype') }}</h4>
                                        <input type="text" class="input-field" name="skype"
                                            placeholder="{{ __('Skype') }}" value="{{$data->skype}}">
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">{{ __('Email') }}</h4>
                                        <input type="text" class="input-field" name="email"
                                            placeholder="{{ __('Email') }}" value="{{$data->email}}">
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
