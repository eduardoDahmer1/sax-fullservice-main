@if ((empty($color_gallery) && empty($material_gallery)) || (!empty($color_gallery) && !empty($material_gallery)))

<div class="col-lg-6">
    <div class="row gallery-product">
        <div class="col-12 col-6 remove-padding p-2">
            <a href="{{ filter_var($productt->photo, FILTER_VALIDATE_URL)
                ? $productt->photo
                : asset('storage/images/products/' . $productt->photo) }}">
                <img class="w-100" src="{{ filter_var($productt->photo, FILTER_VALIDATE_URL)
                    ? $productt->photo
                    : asset('storage/images/products/' . $productt->photo) }}"
                xoriginal="{{ filter_var($productt->photo, FILTER_VALIDATE_URL)
                    ? $productt->photo
                    : asset('storage/images/products/' . $productt->photo) }}" />
            </a>
        </div>
        @if ($gs->ftp_folder)
            @foreach ($ftp_gallery as $ftp_image)
                @if ($ftp_image != $productt->photo)
                    <div class="col-lg-6 col-6 remove-padding p-2">
                        <a href="{{ $ftp_image }}">
                            <img class="img-fluid" src="{{ $ftp_image }}"
    >
                        </a>
                    </div>
                @endif
            @endforeach
        @endif
        @foreach ($productt->galleries as $gal)
            <div class="col-lg-6 col-6 remove-padding p-2">
                <a href="{{ $gal->photo_url }}">
                    <img class="img-fluid" src="{{ $gal->photo_url }}">
                </a>
            </div>
        @endforeach
    </div>
</div>

{{-- <div class="col-lg-5 col-md-12">
    <div class="xzoom-container">
        <img class="xzoom5" id="xzoom-magnific"
            src="{{ filter_var($productt->photo, FILTER_VALIDATE_URL)
                ? $productt->photo
                : asset('storage/images/products/' . $productt->photo) }}"
            xoriginal="{{ filter_var($productt->photo, FILTER_VALIDATE_URL)
                ? $productt->photo
                : asset('storage/images/products/' . $productt->photo) }}" />
        <div class="xzoom-thumbs">
            <div class="all-slider">
                <a
                    href="{{ filter_var($productt->photo, FILTER_VALIDATE_URL)
                        ? $productt->photo
                        : asset('storage/images/products/' . $productt->photo) }}">
                    <img class="xzoom-gallery5" width="80"
                        src="{{ filter_var($productt->photo, FILTER_VALIDATE_URL)
                            ? $productt->photo
                            : asset('storage/images/products/' . $productt->photo) }}"
                        title="The description goes here">
                </a>
                @if ($gs->ftp_folder)
                    @foreach ($ftp_gallery as $ftp_image)
                        @if ($ftp_image != $productt->photo)
                            <a href="{{ $ftp_image }}">
                                <img class="xzoom-gallery5" width="80" src="{{ $ftp_image }}"
                                    title="The description goes here">
                            </a>
                        @endif
                    @endforeach
                @endif
                @foreach ($productt->galleries as $gal)
                    <a href="{{ $gal->photo_url }}">
                        <img class="xzoom-gallery5" width="80" src="{{ $gal->photo_url }}"
                            title="The description goes here">
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div> --}}

@elseif(!empty($color_gallery) && empty($material_gallery))
    <div class="col-lg-6 col-md-12">
        <div class="xzoom-container">
            @php
                if (!empty($color_gallery)) {
                    $first = explode('|', $color_gallery[0])[0];
                }
            @endphp
            @if (!empty($color_gallery))
                <img class="xzoom5" id="xzoom-magnific" src="{{ asset('storage/images/color_galleries/' . $first) }}" />
            @else
                <img class="xzoom5" id="xzoom-magnific"
                    src="{{ filter_var($productt->photo, FILTER_VALIDATE_URL)
                        ? $productt->photo
                        : asset('storage/images/products/' . $productt->photo) }}"
                    xoriginal="{{ filter_var($productt->photo, FILTER_VALIDATE_URL)
                        ? $productt->photo
                        : asset('storage/images/products/' . $productt->photo) }}" />
            @endif
            <div class="xzoom-thumbs">
                <div class="all-slider-color-gallery">
                    @if (!empty($color_gallery))
                        @foreach ($color_gallery as $color_gal)
                            @php
                                $aux_arr = [];
                                $color_arr = [];
                                foreach ($productt->color as $key => $color) {
                                    if (!array_key_exists($key, $color_gallery)) {
                                        break;
                                    }
                                    $aux_arr[$color] = $color_gallery[$key];
                                    foreach ($aux_arr as $aux) {
                                        $color_arr[$color] = explode('|', $aux);
                                    }
                                }
                            @endphp
                        @endforeach
                        @foreach ($productt->color as $arr_key => $color)
                            @if (array_key_exists($color, $color_arr))
                                @foreach ($color_arr[$color] as $key => $gal)
                                    <a href="{{ asset('storage/images/color_galleries/' . $gal) }}"
                                        class="color_gallery color-{{ str_replace('#', '', $color) }} {{ $arr_key == 0 ? 'active' : 'hidden' }}">
                                        <img class="xzoom-gallery5" width="80"
                                            src="{{ asset('storage/images/color_galleries/' . $gal) }}"
                                            title="The description goes here">
                                    </a>
                                @endforeach
                            @endif
                        @endforeach
                    @else
                        @foreach ($productt->galleries as $gal)
                            <a href="{{ $gal->photo_url }}">
                                <img class="xzoom-gallery5" width="80" src="{{ $gal->photo_url }}"
                                    title="The description goes here">
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@elseif(empty($color_gallery) && !empty($material_gallery))
    <div class="col-lg-5 col-md-12">
        <div class="xzoom-container">
            @php
                $first = explode('|', $material_gallery[0])[0];
            @endphp
            <img class="xzoom5" id="xzoom-magnific" src="{{ asset('storage/images/material_galleries/' . $first) }}" />
            <div class="xzoom-thumbs">
                <div class="all-slider-material-gallery">
                    @if (!empty($material_gallery))
                        @foreach ($material_gallery as $material_gal)
                            @php
                                $aux_arr = [];
                                $material_arr = [];
                                foreach ($productt->material as $key => $material) {
                                    if (!array_key_exists($key, $material_gallery)) {
                                        break;
                                    }
                                    $aux_arr[$material] = $material_gallery[$key];
                                    foreach ($aux_arr as $aux) {
                                        $material_arr[$material] = explode('|', $aux);
                                    }
                                }
                            @endphp
                        @endforeach
                        @foreach ($productt->material as $arr_key => $material)
                            @if (array_key_exists($material, $material_arr))
                                @foreach ($material_arr[$material] as $key => $gal)
                                    <a href="{{ asset('storage/images/material_galleries/' . $gal) }}"
                                        class="material_gallery material-{{ $arr_key }} {{ $arr_key == 0 ? 'active' : 'hidden' }}">
                                        <img class="xzoom-gallery5" width="80"
                                            src="{{ asset('storage/images/material_galleries/' . $gal) }}"
                                            title="The description goes here">
                                    </a>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                    @foreach ($productt->galleries as $gal)
                        <a href="{{ asset('storage/images/galleries/' . $gal->photo) }}">
                            <img class="xzoom-gallery5" width="80"
                                src="{{ asset('storage/images/galleries/' . $gal->photo) }}"
                                title="The description goes here">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
