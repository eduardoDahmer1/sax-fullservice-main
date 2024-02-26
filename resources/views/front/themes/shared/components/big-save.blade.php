@if($ps->big == 1)
<!-- Clothing and Apparel Area Start -->
<section class="categori-item clothing-and-Apparel-Area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="section-top">
                    <h2 class="section-title" data-aos="fade-in" data-aos-delay="100">
                        {{ __("Big Save") }}
                    </h2>

                </div>
            </div>
        </div>
        <div class="row">
            @if($ps->big_save_banner or $ps->big_save_banner1)
            <div class="col-lg-10">
                @else
                <div class="col-lg-12">
                    @endif
                    <div class="row row-theme">
                        @foreach($big_products as $prod)

                        @include('front.themes.'.env('THEME', 'theme-01').'.components.home-product')
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-2 remove-padding d-none d-lg-block">
                    <div class="aside">
                        @if($ps->big_save_banner)
                        <a class="banner-effect sider-bar-align" href="{{ $ps->big_save_banner_link }}">
                            <img src="{{asset('storage/images/banners/'.$ps->big_save_banner)}}" alt=""
                                style="width:100%;border-radius: 5px;">
                        </a>
                        @endif
                        @if($ps->big_save_banner1)
                        <a class="banner-effect sider-bar-align" href="{{ $ps->big_save_banner_link1 }}">
                            <img src="{{asset('storage/images/banners/'.$ps->big_save_banner1)}}" alt=""
                                style="width:100%;border-radius: 5px;">
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Clothing and Apparel Area start-->
@endif
