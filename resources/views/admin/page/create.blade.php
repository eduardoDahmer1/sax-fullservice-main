@extends('layouts.load')

@section('content')

<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('admin-page-create')}}" method="POST"
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
                    @component('admin.components.input-localized',["required" => true, "type" => "richtext"])
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

                <div class="col-xl-6">
                  <div class="checkbox-wrapper list list-personalizada">
                    <input type="checkbox" name="secheck" class="checkclick1" id="allowProductSEO">
                    <label for="allowProductSEO">{{ __('Allow Page SEO') }} </label> <i class="icofont-question-circle" data-toggle="tooltip" style="display: inline-block " data-placement="top" title="{{ __('SEO (Search Engine Optimization) focuses on your website presence in search results on search engines like Google') }}"></i>
                  </div>

                  <div class="showbox">

                    <div class="input-form">
                      @component('admin.components.input-localized',["type" => "tags"])
                          @slot('name')
                              meta_tag
                          @endslot
                          {{ __('Meta Tags') }}
                      @endcomponent
                    </div>

                    <div class="input-form">
                      @component('admin.components.input-localized',["type" => "textarea"])
                            @slot('name')
                                meta_description
                            @endslot
                            @slot('placeholder')
                                {{ __('Meta Description') }}
                            @endslot
                            {{ __('Meta Description') }}
                        @endcomponent
                    </div>

                  </div>
                </div>

              </div>

              <div class="row justify-content-center">
                  <button class="addProductSubmit-btn" type="submit">{{ __('Create Page') }}</button>
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
