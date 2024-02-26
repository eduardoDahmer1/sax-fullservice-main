@extends('layouts.load')

@section('content')

<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('vendor-package-store')}}" method="POST"
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
                    @component('admin.components.input-localized',["required" => true])
                      @slot('name')
                        title
                      @endslot
                      @slot('placeholder')
                        {{ __('Title') }}
                      @endslot
                        {{ __('Title') }} *
                    @endcomponent
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="input-form">
                    @component('admin.components.input-localized')
                      @slot('name')
                        subtitle
                      @endslot
                      @slot('placeholder')
                        {{ __('Subtitle') }}
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
                    value="" min="0" step="0.01">
                  </div>
                </div>
               
              </div>

              <div class="row justify-content-center">
              
                  <button class="addProductSubmit-btn" type="submit">{{ __('Create Package') }}</button>
               
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection