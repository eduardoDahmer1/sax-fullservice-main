{{-- CUSTOM GALLERY MODAL --}}

<div class="modal fade" id="openOptions" data-target="#customProd" tabindex="-1" role="dialog"
    aria-labelledby="setgallery" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center" style="border-bottom: 1px solid #ccc !important;">
                <h5 class="modal-title mt-4" id="exampleModalCenterTitle">{{ __("Gallery Options") }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="top-area">
                    <div class="row">
                        <div class="col-sm-12">
                        </div>

                    </div>
                </div>
                <div class="gallery-images">
                    <div class="selected-image">
                        <div class="row justify-content-center">
                            @foreach($category_gallery as $cat_gal)
                            <a class="textureIconsModal" id="textureIcons" style="cursor: pointer;">
                                <img class="textureImagesModal ml-2" id="textureImagesModal" width="100"
                                    src="{{asset('storage/images/thumbnails/' . $cat_gal->customizable_gallery)}}"
                                    style="border-radius: 50px;">
                                <div class="textureOverlayModal">
                                    <span class="overlaySpanModal"><i class="icofont-ui-add icofont-2x"
                                            style="color: #fff"></i></span>
                                </div>
                            </a>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CUSTOM GALLERY MODAL ENDS --}}
<!-- Product Details Area End -->
