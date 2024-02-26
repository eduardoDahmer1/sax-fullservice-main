@extends('layouts.load')

@section('content')

<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('admin-faq-create')}}" method="POST"
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

                <div class="col-xl-12">
                  <div class="input-form">
                    @component('admin.components.input-localized',["type" => "richtext", "required" => true])
                        @slot('name')
                            details
                        @endslot
                        {{ __('Description') }}
                    @endcomponent
                  </div>
                </div>

              </div>

              <div class="row justify-content-center">
                <button class="addProductSubmit-btn" type="submit">{{ __('Create FAQ') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection