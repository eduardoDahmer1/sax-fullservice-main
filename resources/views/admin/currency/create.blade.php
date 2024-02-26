@extends('layouts.load')

@section('content')

<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('admin-currency-create')}}" method="POST"
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

                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Name') }} *
                      <span>({{ __('ISO Currency Code') }})</span>
                    </h4>
                    <input type="text" class="input-field" name="name" placeholder="{{ __('Enter Currency Name') }}"
                    required="" value="">
                  </div>
                </div>

             
                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Description') }} *</h4>
                    <input type="text" class="input-field" name="description" placeholder="{{ __('Enter Description') }}"
                    required="" value="">
                  </div>
                </div>

                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Sign') }} *</h4>
                    <input type="text" class="input-field" name="sign" placeholder="{{ __('Enter Currency Sign') }}"
                    required="" value="">
                  </div>
                </div>

                <div class="col-xl-3">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Value') }} *
                      <span>{{ __('(Enter the Value For 1(First Currency) = ?)') }}</span>
                    </h4>
                    <input type="number" step="0.001" min="0.001" class="input-field" name="value"
                    placeholder="{{ __('Enter Currency Value') }}" required="" value="">
                  </div>
                </div>

                <div class="col-xl-3">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Decimal Separator') }} *</h4>
                    <input type="text" class="input-field" name="decimal_separator" value="" required="" maxlength="1">
                  </div>
                </div>

                <div class="col-xl-3">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Thousands Separator') }} *</h4>
                    <input type="text" class="input-field" name="thousands_separator" value="" maxlength="1" required="">
                  </div>
                </div>

                <div class="col-xl-3">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Decimal Digits') }} *</h4>
                    <input type="number" class="input-field" name="decimal_digits" step="1" min="0" required="">
                  </div>
                </div>
          
              </div> <!--FECHAMENTO TAG ROW-->

              <div class="row justify-content-center">
                  <button class="addProductSubmit-btn" type="submit">{{ __('Create Currency') }}</button>
              
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection