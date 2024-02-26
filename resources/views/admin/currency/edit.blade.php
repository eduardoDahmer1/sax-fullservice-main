@extends('layouts.load')

@section('content')

            <div class="content-area">

              <div class="add-product-content">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                      @include('includes.admin.form-error') 
                      <form id="geniusformdata" action="{{route('admin-currency-update',$data->id)}}" method="POST" enctype="multipart/form-data">
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
                                <input type="text" class="input-field" name="name" placeholder="{{ __('Enter Currency Name') }}" required="" value="{{$data->name}}">
                            </div>
                          </div>

                         
                          <div class="col-xl-4">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Description') }} *
                                  <span>{{ __('(In Any Language)') }}</span>
                                </h4>
                                <input type="text" class="input-field" name="description" placeholder="{{ __('Enter Description') }}" required="" value="{{$data->description}}">
                            </div>
                          </div>

                          <div class="col-xl-4">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Sign') }} *</h4>
                                <input type="text" class="input-field" name="sign" placeholder="{{ __('Enter Currency Sign') }}" required="" value="{{$data->sign}}">
                            </div>
                          </div>

                          <div class="col-xl-3">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Value') }} *
                                  <span>{{ __('(Enter the Value For 1(First Currency) = ?)') }}</span>
                                </h4>
                                <input type="number" class="input-field" name="value" step="0.001" min="0.001"  placeholder="{{ __('Enter Currency Value') }}" required="" value="{{$data->value}}" {{ $data->value == 1 ? "disabled style=background:#f2f2f2" : ""}}>
                            </div>
                          </div>

                          <div class="col-xl-3">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Decimal Separator') }} *</h4>
                                <input type="text" class="input-field" name="decimal_separator" value="{{ $data->decimal_separator }}" required="" maxlength="1">
                            </div>
                          </div>

                          <div class="col-xl-3">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Thousands Separator') }}</h4>
                                <input type="text" class="input-field" name="thousands_separator" value="{{ $data->thousands_separator }}" maxlength="1">
                            </div>
                          </div>

                          <div class="col-xl-3">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Decimal Digits') }}</h4>
                                <input type="number" class="input-field" name="decimal_digits" step="1" min="0" value="{{ $data->decimal_digits }}">
                            </div>
                          </div>

                        </div> <!--FECHAMENTO TAG ROW-->

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