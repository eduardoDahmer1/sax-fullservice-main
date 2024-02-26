@if(env("ENABLE_CUSTOM_PRODUCT"))
@if($productt->category->is_customizable)
<h4 class="customize-title" style="text-transform: uppercase;">
    {{__('Customize your product:')}}</h4>
<div class="mt-4 mb-4 customizable-item">
    <input type="text" class="form-control col-lg-8 mt-2" name="customizable_name" id="customizable_name" value=""
        style="margin-top: -13px;" placeholder="{{ __('Enter your name') }}">
    <div class="mt-4">
        @include('includes.admin.form-error')
        <input type="checkbox" id="customLogo" class="checkclick" onclick="showLogoField()" value="1">
        <label for="customLogo">{{ __('Upload Logo Image') }}</label>
    </div>

    <div class="mt-4" style="display: none;" id="logoField">
        <form method="POST" enctype="multipart/form-data" id="logoUpload">
            @csrf
            <div class="img-upload">
                <label for="image-upload" class="img-label mt-4" id="image-label"><i class="icofont-upload-alt"></i>{{
                    __('Upload Image') }}</label>
                <input type="file" name="customizable_logo" class="img-upload" id="image-upload">
                <h4 class="customize-title">{{ __('Accepted formats: png, jpg
                    and svg.') }}</h4><br>
                <div class="row">
                    <button type="submit" class="btn btn-primary uploadLogoBtn"
                        style="margin-top: -10px; margin-left: 10px">
                        <p style="">{{ __('Upload Logo') }}</p>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<input type="hidden" name="customizable_gallery_count" id="customizable_gallery_count"
    value="{{ count($category_gallery) }}">
@if(count($category_gallery) > 0)
<div class="row">
    <div class="d-flex">
        @php
        $i=1;
        @endphp
        <img src="" id="currentGallery" class="image-responsive" width="100" alt=""
            style="display:none; border-radius: 50px; height:100px; margin-top: -6px;">
        <span class="overlayCurrentSpan" style="display:none"><i class="icofont-check-alt icofont-4x"
                style="color: #fff"></i></span>
        <div class="textureCurrentOverlay" style="position: relative; display:none"></div>
        @foreach($category_gallery as $cat_gal)
        <input type="hidden" id="customizable_gallery" value="">
        <a class="textureIcons" id="textureIcons" onclick="" style="cursor: pointer;">
            <img class="textureImages" width="80"
                src="{{asset('storage/images/thumbnails/' . $cat_gal->customizable_gallery)}}"
                style="border-radius: 50px; margin-left: 5px;">
            <div class="textureOverlay" style="position: relative;"></div>
            <span class="overlaySpan"><i class="icofont-ui-add icofont-1x" style="color: #fff"></i></span>
        </a>

        @php
        $i++;
        @endphp

        @if($i == 4)

        @break
        @endif

        @endforeach
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="allOptions d-flex mt-4 ml-2">
            <a class="allOptionsAnchor" id="openBtn" style="cursor:pointer;">
                <p style="font-weight: 600;">Ver todas as opções</p>
            </a>
        </div>
    </div>
</div>
@endif
<div class="row mb-2">
    <div class="col-lg-12">
        <input type="checkbox" name="agree_terms" id="agreeCustomTerms" value="" class="checkclick">
        <label for="agreeCustomTerms" style="font-weight:500;">{{ __('I reviewed my choices.') }}</label>
    </div>
</div>
@endif
@endif
