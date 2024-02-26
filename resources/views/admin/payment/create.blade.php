@extends('layouts.load')

@section('content')

            <div class="content-area">

              <div class="add-product-content">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        @include('includes.admin.form-error') 
                      <form id="geniusformdata" action="{{route('admin-payment-create')}}" method="POST" enctype="multipart/form-data">
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
                                <h4 class="heading">{{ __('Title') }} *
                                  <span>{{ __('(In Any Language)') }}</span>
                                </h4>
                                <input type="text" class="input-field" name="title" placeholder="{{ __('Title') }}" required="" value="">
                            </div>
                          </div>

                          <div class="col-xl-4">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Subtitle') }} *
                                  <span>{{ __('(Optional)') }}</span>
                                </h4>
                                <input type="text" class="input-field" name="subtitle" placeholder="{{ __('Subtitle') }}" value="">
                            </div>
                          </div>

                          <div class="col-xl-4">
                            <div class="input-form">
                              <h4 class="heading">
                                  {{ __('Description') }} *
                              </h4>
                              <textarea  class="trumboedit" name="details" placeholder="{{ __('Description') }}"></textarea> 
                            </div>
                          </div>

                        </div> <!--FECHAMENTO TAG ROW-->

                        <div class="row justify-content-center">
                          
                            <button class="addProductSubmit-btn" type="submit">{{ __('Create Payment') }}</button>
                         
                        </div>
                      </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
@endsection
