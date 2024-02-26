@extends('layouts.load')

@section('content')

            <div class="content-area">

              <div class="add-product-content">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        @include('includes.admin.form-error')  
                      <form id="geniusformdata" action="{{route('admin-cat-create')}}" method="POST" enctype="multipart/form-data">
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
                                  name
                                @endslot
                                @slot('placeholder')
                                  {{ __('Enter Name') }}
                                @endslot
                                {{ __('Name') }} *
                              @endcomponent
                            </div>
                          </div>

                          <div class="col-xl-6">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Set Icon') }}</h4>
                                <div class="img-upload">
                                  <div id="image-preview" class="img-preview" style="background: url({{ asset('assets/admin/images/upload.png') }});">
                                    <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i>{{ __('Upload Icon') }}</label>
                                    <input type="file" name="photo" class="img-upload" id="image-upload">
                                  </div>
                                </div>
                            </div>
                          </div> <!---FECHAMENTO ROW XXL-6 -->

                          <div class="col-xl-6">

                            @if(env("ENABLE_CUSTOM_PRODUCT"))
                            <div class="checkbox-wrapper" style="display:block;">
                              <input type="checkbox" name="is_customizable" id="is_customizable" value="1" >
                              <label for="is_customizable">{{ __('Allow Customizable Itens') }}</label>
                            </div>
                            @endif
                            @if(env("ENABLE_CUSTOM_PRODUCT_NUMBER"))
                            <div class="checkbox-wrapper" style="display:block;">
                              <input type="checkbox" name="is_customizable_number" id="is_customizable_number" value="1" >
                              <label for="is_customizable_number">{{ __('Allow Customizable Itens by Number') }}</label>
                            </div>
                            @endif

                            <div class="checkbox-wrapper" style="display:block;">
							                <input type="checkbox" name="is_featured" class="checkclick1" id="is_featured" value="1">
							                <label for="is_featured">{{ __('Allow Featured Category') }}</label>
							              </div>

                            <div class="showbox input-form">

                              <h4 class="heading">{{ __('Set Feature Image') }} *</h4>
                              <div class="img-upload">
                                <div id="image-preview" class="img-preview" style="background: url({{ asset('assets/admin/images/upload.png') }});">
                                  <label for="image-upload" class="img-label"><i class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                  <input type="file" name="image" class="img-upload">
                                </div>
                              </div>
                            </div>

                          </div> <!---FECHAMENTO ROW XXL-6 -->

                          <div class="col-xl-12">
                            <div class="input-form">
                              <h4 class="heading">{{ __('Banner') }} *</h4>
                              <div class="img-upload full-width-img">
                                <div id="image-preview" class="img-preview"
                                  style="background: url('{{ asset('assets/admin/images/upload.png') }}');">
                                  <label for="image-upload" class="img-label" id="image-label"><i
                                      class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                  <input type="file" name="banner" class="img-upload" id="image-upload">
                                </div>
                                <p class="text">{{ __('Prefered Size: (1920x400) or Rectangular Sized Image') }}</p>
                              </div>
                            </div>
                          </div>
                          <div class="col-xl-12">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Link') }}<i class="icofont-question-circle"
                                    data-toggle="tooltip" style="display: inline-block "
                                    data-placement="top"
                                    title="{{ __('Link that will open when the object gets clicked') }}"></i>
                                </h4>
                                    <input type="text" class="input-field" name="link" placeholder="{{ __('Link') }}">                      
                        </div>
                        <div class="row justify-content-center">
                          <button class="addProductSubmit-btn" type="submit">{{ __('Create Category') }}</button>
                        </div>
                      </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

@endsection
