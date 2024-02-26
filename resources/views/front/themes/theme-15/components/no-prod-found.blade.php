<div class="col-xl-12">
    <div class="page-center">
        <h2>Oops! :(</h2>
        <h4 class="text-center">{{ __('No Product Found.') }}</h4>
    </div>
</div>
@if ($feature_products->count() > 0)
    <div class="col-xl-12">
        <div class="page-center flash-deals no-prod-found" style="padding-top:4rem;">
            <h3>{{ __('Some products that may be of interest to you:') }}</h3>
            <div class="trending-item-slider">
                @foreach ($feature_products as $prod)
                    @include('front.themes.theme-11.components.slider-product')
                @endforeach
            </div>
        </div>
    </div>
    <script>
        // trending item  slider
        var $trending_slider = $('.trending-item-slider');
        $trending_slider.owlCarousel({
            items: 5,
            autoplay: false,
            margin: 0,
            loop: true,
            dots: false,
            nav: true,
            center: false,
            autoplayHoverPause: true,
            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
            smartSpeed: 800,
            responsive: {
                0: {
                    items: 2,
                },
                414: {
                    items: 2,
                },
                768: {
                    items: 3,
                },
                992: {
                    items: 4
                },
                1200: {
                    items: 5
                }
            }
        });
    </script>
@endif
