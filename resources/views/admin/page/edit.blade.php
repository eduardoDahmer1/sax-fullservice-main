@extends('layouts.load')

@section('content')

<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('admin-page-update',$data->id)}}" method="POST"
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
                    @component('admin.components.input-localized',["from" => $data, "type" => "richtext"])
                      @slot('name')
                        details
                      @endslot
                      @slot('placeholder')
                      {{ __('Details') }}
                      @endslot
                      @slot('value')
                        details
                      @endslot
                      {{ __('Description') }} *
                    @endcomponent
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="checkbox-wrapper list list-personalizada">
                    <input type="checkbox" name="secheck" class="checkclick1" id="allowProductSEO"
                      {{ ($data->meta_tag != null || strip_tags($data->meta_description) != null) ? 'checked':'' }}>
                    <label for="allowProductSEO">{{ __('Allow Page SEO') }}</label> <i class="icofont-question-circle" data-toggle="tooltip" style="display: inline-block " data-placement="top" title="{{ __('SEO (Search Engine Optimization) focuses on your website presence in search results on search engines like Google') }}"></i>
                  </div>

                  <div class='{{ ($data->meta_tag == null && strip_tags($data->meta_description) == null) ? "showbox":"" }}'>
                    <div class="input-form">
                      @component('admin.components.input-localized',["from" => $data, "type" => "tags"])
                        @slot('name')
                          meta_tag
                        @endslot
                        @slot('value')
                          meta_tag
                        @endslot
                        {{ __('Meta Tags') }}
                      @endcomponent
                    </div>

                    <div class="input-form">
                      @component('admin.components.input-localized',["from" => $data, "type" => "textarea"])
                        @slot('name')
                          meta_description
                        @endslot
                        @slot('placeholder')
                          {{ __('Meta Description') }}
                        @endslot
                        @slot('value')
                          meta_description
                        @endslot
                        {{ __('Meta Description') }}
                      @endcomponent
                    </div>

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
