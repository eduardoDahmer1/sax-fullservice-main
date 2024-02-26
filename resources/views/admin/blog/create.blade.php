@extends('layouts.load')

@section('content')

<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('admin-blog-create')}}" method="POST"
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

                <div class="col-xl-6">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Current Featured Image') }}</h4>
                    <div class="img-upload">
                      <div id="image-preview" class="img-preview"
                        style="background: url('{{ asset('assets/admin/images/upload.png') }}');">
                        <label for="image-upload" class="img-label" id="image-label"><i
                            class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                          <input type="file" name="photo" class="img-upload" id="image-upload">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Category') }}*</h4>
                    <select name="category_id" required="">
                      <option value="">{{ __('Select Category') }}</option>
                      @foreach($cats as $cat)
                      <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="input-form">
                    <h4 class="heading">{{ __('Source') }} *</h4>
                    <input type="text" class="input-field" name="source" placeholder="{{ __('Source') }}" required=""
                    value="{{ Request::old('source') }}">
                  </div>

                </div>

                <div class="col-xl-12">
                  <div class="input-form">
                    @component('admin.components.input-localized',["required" => true, "type" => "richtext"])
                        @slot('name')
                          details
                        @endslot
                        {{ __('Description') }} *
                    @endcomponent
                  </div>
                </div>

                <div class="col-xl-12">
                  <div class="checkbox-wrapper list list-personalizada">
                    <input type="checkbox" name="secheck" class="checkclick1" id="allowProductSEO">
                    <label for="allowProductSEO">{{ __('Allow Blog SEO') }}</label> <i class="icofont-question-circle" data-toggle="tooltip" style="display: inline-block " data-placement="top" title="{{ __('SEO (Search Engine Optimization) focuses on your website presence in search results on search engines like Google') }}"></i>
                  </div>

                  <div class="showbox input-form">

                    @component('admin.components.input-localized',["type" => "tags"])
                        @slot('name')
                            meta_tag
                        @endslot
                        {{ __('Meta Tags') }}
                    @endcomponent

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

                <div class="col-xl-12">
                  <div class="input-form">
                    @component('admin.components.input-localized',["type" => "tags"])
                        @slot('name')
                            tags
                        @endslot
                        {{ __('Tags') }}<i class="icofont-question-circle" data-toggle="tooltip" style="display: inline-block " data-placement="top" title="{{ __('It can help you tracking your blog') }}"></i>
                    @endcomponent
                  </div>
                </div>
  

              </div> <!--FECHAMENTO TAG ROW-->
      

              <div class="row justify-content-center">
                  <button class="addProductSubmit-btn" type="submit">{{ __('Create Post') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
