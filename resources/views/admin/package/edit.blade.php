@extends('layouts.load')

@section('content')
<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('admin-package-update',$data->id)}}" method="POST"
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
                     @component('admin.components.input-localized',["required" => true, "from" => $data])
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
                </div>

                <div class="col-xl-6">
                  <div class="input-form">
                    @component('admin.components.input-localized', ["from" => $data])
                      @slot('name')
                        subtitle
                      @endslot
                      @slot('placeholder')
                        {{ __('Subtitle') }}
                      @endslot
                      @slot('value')
                        subtitle
                      @endslot
                        {{ __('Subtitle') }}
                    @endcomponent
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Price') }} *
                      <span>({{ __('In') }} {{ $sign->name }})</span>
                    </h4>
                    <input type="number" class="input-field" name="price" placeholder="{{ __('Price') }}" required=""
                    value="{{ $data->price }}" min="0" step="0.01">
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