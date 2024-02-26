@extends('layouts.load')

@section('content')

            <div class="content-area">

              <div class="add-product-content">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        @include('includes.admin.form-error')  
                      <form id="geniusformdata" action="{{route('admin-sb-store')}}" method="POST" enctype="multipart/form-data">
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
                                <h4 class="heading">{{ __('Current Featured Image') }} *</h4>
                                <div class="img-upload full-width-img">
                                    <div id="image-preview" class="img-preview" style="background: url('{{ asset('assets/admin/images/upload.png') }}');">
                                        <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                        <input type="file" name="photo" class="img-upload" id="image-upload">
                                      </div>
                                      <p class="text">{{ __('Prefered Size: (400x630) or Square Sized Image') }}</p>
                                </div>
                            </div>
                          </div>
                          
                          <div class="col-xl-6">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Link') }} * <i class="icofont-question-circle" data-toggle="tooltip" style="display: inline-block " data-placement="top" title="{{ __('Link that will open when the object get clicked') }}"></i></h4>
                                <input type="text" class="input-field" name="link" placeholder="{{ __('Link') }}" value="">
                            </div>

                            <div class="input-form">
                                        <h4 class="heading">{{ __('Display in Stores') }}* </h4>
                                        @foreach($storesList as $store)
                                        <div class="row justify-content-left">
                                            <div class="col-lg-12 d-flex justify-content-between">
                                                <label class="control-label" for="store{{$store->id}}">{{$store->title}} |
                                                    {{$store->domain}}</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="stores[]" id="store{{$store->id}}"
                                                        value="{{$store->id}}" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                          </div>

                          <input type="hidden" name="type" value="BottomSmall">

                        </div> <!--FECHAMENTO TAG ROW-->


                        <div class="row justify-content-center">
                            <button class="addProductSubmit-btn" type="submit">{{ __('Create Banner') }}</button>
                        </div>
                      </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

@endsection