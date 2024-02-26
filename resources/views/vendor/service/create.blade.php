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
            <form id="geniusformdata" action="{{route('vendor-service-store')}}" method="POST"
              enctype="multipart/form-data">
              {{csrf_field()}}

              <div class="row">
                <div class="col-xl-12">
                    <div class="input-form">
                        <p><small>* {{ __("indicates a required field") }}</small></p>
                    </div>
                </div>
            </div>

              <div>
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
                        {{ __('Title') }}*
                    @endcomponent
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Current Featured Image') }} *</h4>
                    <div class="img-upload">
                      <div style="width:180px;background-image: url( {{ asset('assets/vendor/images/quadriculado.jpg')}} );background-size:contain;">
                        <div id="image-preview" class="img-preview"
                          style="background-image: url('{{ asset('assets/vendor/images/upload.png') }}');background-size:100% !important;background-position:center !important;">
                          <label for="image-upload" class="img-label" id="image-label"><i
                              class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                          <input type="file" name="photo" class="img-upload" id="image-upload">
                        </div>
                      </div>
                      <p class="text">{{ __('Prefered Size: (600x600) or Square Sized Image') }}</p>
                    </div>
                  </div>
                </div>
                
                <div class="col-xl-6">
                  <div class="input-form">
                    @component('admin.components.input-localized',["type" => "textarea"])
                        @slot('name')
                            details
                        @endslot
                        @slot('placeholder')
                            {{ __('Description') }}
                        @endslot
                        {{ __('Description') }} *
                    @endcomponent
                  </div>
                </div>


              </div> <!--FECHAMENTO TAG ROW-->

              

              <div class="row justify-content-center">
             
                  <button class="addProductSubmit-btn" type="submit">{{ __('Create Service') }}</button>
    
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection