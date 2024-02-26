@if($ps->small_banner == 1)
<!-- Banner Area One Start -->
<section class="banner-section position-top py-4">
    <div class="container">
        <div class="row pb-4">
            <div class="col-lg-12 remove-padding">
                <div class="section-top text-center">
                    <h2 class="section-title" data-aos="fade-in">
                        {{ __("Exclusive") }}
                    </h2>
                    <h5 data-aos="fade-in" data-aos-delay="100">{{__("Unique and limited edition items that you will only find at Sax Departemente.")}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="slider-banners-excluse" data-aos="fade-in">
        @foreach ($top_small_banners as $img)
            <div class="item-slider">
                <img loading="lazy" src="{{ asset('storage/images/banners/' . $img->photo) }}" alt="Banner Shop Sax">
            </div>
        @endforeach
    </div>
    <div class="container">
        <div class="row justify-content-center pt-4" data-aos="fade-in">
            <div class="col-md-3">
                <a class="btn btn-style-1" href="{{ route('front.category') }}">{{ __('See all')}}</a>
            </div>
        </div>
    </div>
</section>
<!-- Banner Area One Start -->
@endif
