@extends('layouts.load')
@section('content')

<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('admin-faq-update',$data->id)}}" method="POST">
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
                
                <div class="col-xl-12">
                  <div class="input-form">
                    @component('admin.components.input-localized',["type" => "richtext", "from" => $data, "required" => true])
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

              </div><!--FECHAMENTO TAG ROWS-->

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