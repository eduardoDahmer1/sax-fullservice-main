@if($ps->flash_deal == 1)
<!-- Electronics Area Start -->
<section class="oferta-relampago categori-item electronics-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="section-top">
                    <h2 class="section-title title-oferta" data-aos="fade-in" data-aos-delay="100">
                        {{ __("Flash Deal") }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 row-theme">
                <div class="flash-deals">
                    <div class="flas-deal-slider d-flex justify-content-center align-items-center">
                        @foreach($discount_products as $prod)

                        @include('front.themes.'.env('THEME', 'theme-01').'.components.flash-product')
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Electronics Area start-->
@endif
