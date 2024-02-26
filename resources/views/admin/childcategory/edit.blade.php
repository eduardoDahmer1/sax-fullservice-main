@extends('layouts.load')

@section('content')

<div class="content-area">

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        @include('includes.admin.form-error')
                        <form id="geniusformdata" action="{{route('admin-childcat-update',$data->id)}}" method="POST"
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
                                        <h4 class="heading">{{ __('Sub Category') }} *</h4>
                                        <select id="subcat" name="subcategory_id" required="">
                                            <option value="">{{ __('Select Sub Category') }}</option>
                                            @foreach($data->subcategory->category->subs as $sub)
                                            <option value="{{$sub->id}}" {{$sub->id == $data->subcategory->id ?
                                                "selected":""}}>{{$sub->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">{{ __('Category') }}*</h4>
                                        <select id="cat" name="category_id" required="">
                                            <option value="">{{ __('Select Category') }}</option>
                                            @foreach($cats as $cat)
                                            <option data-href="{{ route('admin-subcat-load',$cat->id) }}"
                                                value="{{ $cat->id }}" {{ $cat->id == $data->subcategory->category->id ?
                                                "selected":"" }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>



                                <div class="col-xl-12">
                                    <div class="input-form">
                                        @component('admin.components.input-localized',["required" => true, "from" =>
                                        $data])
                                        @slot('name')
                                        name
                                        @endslot
                                        @slot('placeholder')
                                        {{ __('Enter Name') }}
                                        @endslot
                                        @slot('value')
                                        name
                                        @endslot
                                        {{ __('Name') }} *
                                        @endcomponent
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="input-form">
                                        <h4 class="heading">{{ __('Banner') }} *</h4>
                                        <div class="img-upload full-width-img">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url('{{ $data->banner ? asset('storage/images/childcategories/banners/'.$data->banner):asset('assets/images/noimage.png') }}');">
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
