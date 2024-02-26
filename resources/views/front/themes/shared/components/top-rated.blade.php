@if($ps->top_rated == 1)
<!-- Electronics Area Start -->
<section class="categori-item electronics-section best-seller">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="section-top">
                    <h2 class="section-title" data-aos="fade-in" data-aos-delay="100">
                        {{ __("Top Rated") }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-10 row-theme">
                <div class="row">
                    @foreach($top_products as $prod)
                    @include('front.themes.'.env('THEME', 'theme-01').'.components.home-product')
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Electronics Area start-->
@endif
