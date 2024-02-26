@if($ps->best == 1)
<!-- Phone and Accessories Area Start -->
<section class="phone-and-accessories categori-item">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="section-top">
                    <h2 class="section-title">
                        {{ __("Best Seller") }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            @if($ps->best_seller_banner or $ps->best_seller_banner1)
            <div class="col-lg-10">
                @else
                <div class="col-lg-12">
                    @endif
                    <div class="row">
                        @foreach($best_products as $prod)
                        @include('includes.product.home-product')
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-2 remove-padding d-none d-lg-block">
                    <div class="aside">
                        @if($ps->best_seller_banner)
                        <a class="banner-effect sider-bar-align" href="{{ $ps->best_seller_banner_link }}">
                            <img src="{{asset('storage/images/'.$ps->best_seller_banner)}}" alt=""
                                style="width:100%;border-radius: 5px;">
                        </a>
                        @endif
                        @if($ps->best_seller_banner1)
                        <a class="banner-effect sider-bar-align" href="{{ $ps->best_seller_banner_link1 }}">
                            <img src="{{asset('storage/images/'.$ps->best_seller_banner1)}}" alt=""
                                style="width:100%;border-radius: 5px;">
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</section>
<!-- Phone and Accessories Area start-->
@endif

@if($ps->flash_deal == 1)
<!-- Electronics Area Start -->
<section class="categori-item electronics-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="section-top">
                    <h2 class="section-title">
                        {{ __("Flash Deal") }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="flash-deals">
                    <div class="flas-deal-slider">

                        @foreach($discount_products as $prod)
                        @include('includes.product.flash-product')
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Electronics Area start-->
@endif

@if($ps->large_banner == 1)
<!-- Banner Area One Start -->
<section class="banner-section">
    <div class="container">
        @foreach($large_banners->chunk(1) as $chunk)
        <div class="row">
            @foreach($chunk as $img)
            <div class="col-lg-12 remove-padding">
                <div class="img">
                    <a class="banner-effect" href="{{ $img->link }}">
                        <img src="{{asset('storage/images/banners/'.$img->photo)}}" alt="">
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</section>
<!-- Banner Area One Start -->
@endif

@if($ps->top_rated == 1)
<!-- Electronics Area Start -->
<section class="categori-item electronics-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="section-top">
                    <h2 class="section-title">
                        {{ __("Top Rated") }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">

                    @foreach($top_products as $prod)
                    @include('includes.product.top-product')
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Electronics Area start-->
@endif

@if($ps->bottom_small == 1)
<!-- Banner Area One Start -->
<section class="banner-section">
    <div class="container">
        @foreach($bottom_small_banners->chunk(3) as $chunk)
        <div class="row">
            @foreach($chunk as $img)
            <div class="col-lg-4 remove-padding">
                <div class="left">
                    <a class="banner-effect" href="{{ $img->link }}" target="_blank">
                        <img src="{{asset('storage/images/banners/'.$img->photo)}}" alt="">
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</section>
<!-- Banner Area One Start -->
@endif

@if($ps->big == 1 && ($ps->big_save_banner || $ps->big_save_banner1))
<!-- Clothing and Apparel Area Start -->
<section class="categori-item clothing-and-Apparel-Area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="section-top">
                    <h2 class="section-title">
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
                    <div class="row">
                        @foreach($best_products as $prod)
                        @include('includes.product.home-product')
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-2 remove-padding d-none d-lg-block">
                    <div class="aside">
                        @if($ps->big_save_banner)
                        <a class="banner-effect sider-bar-align" href="{{ $ps->big_save_banner_link }}">
                            <img src="{{asset('storage/images/'.$ps->big_save_banner)}}" alt=""
                                style="width:100%;border-radius: 5px;">
                        </a>
                        @endif
                        @if($ps->big_save_banner1)
                        <a class="banner-effect sider-bar-align" href="{{ $ps->big_save_banner_link1 }}">
                            <img src="{{asset('storage/images/'.$ps->big_save_banner1)}}" alt=""
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

@if($ps->hot_sale == 1)
<!-- hot-and-new-item Area Start -->
<section class="hot-and-new-item">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="accessories-slider">
                    <div class="slide-item">
                        <div class="row">
                            <div class="col-lg-12 col-sm-6">
                                <div class="categori">
                                    <div class="section-top">
                                        <h2 class="section-title">
                                            {{ __("Hot") }}
                                        </h2>
                                    </div>
                                    <div class="hot-and-new-item-slider">
                                        @foreach($hot_products as $prod)
                                        <div class="item-slide">
                                            <ul class="item-list">
                                                @include('includes.product.list-product')
                                            </ul>
                                        </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6">
                                <div class="categori">
                                    <div class="section-top">
                                        <h2 class="section-title">
                                            {{ __("New") }}
                                        </h2>
                                    </div>

                                    <div class="hot-and-new-item-slider">

                                        @foreach($latest_products as $prod)
                                        <div class="item-slide">
                                            <ul class="item-list">
                                                @include('includes.product.list-product')
                                            </ul>
                                        </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6">
                                <div class="categori">
                                    <div class="section-top">
                                        <h2 class="section-title">
                                            {{ __("Trending") }}
                                        </h2>
                                    </div>


                                    <div class="hot-and-new-item-slider">

                                        @foreach($trending_products as $prod)
                                        <div class="item-slide">
                                            <ul class="item-list">
                                                @include('includes.product.list-product')
                                            </ul>
                                        </div>
                                        @endforeach

                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6">
                                <div class="categori">
                                    <div class="section-top">
                                        <h2 class="section-title">
                                            {{ __("Sale") }}
                                        </h2>
                                    </div>

                                    <div class="hot-and-new-item-slider">

                                        @foreach($sale_products as $prod)
                                        <div class="item-slide">
                                            <ul class="item-list">
                                                @include('includes.product.list-product')
                                            </ul>
                                        </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Clothing and Apparel Area start-->
@endif

@if($ps->review_blog == 1)
<!-- Blog Area Start -->
<section class="blog-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="aside">
                    <div class="slider-wrapper">
                        <div class="aside-review-slider">
                            @foreach($reviews as $review)
                            <div class="slide-item">
                                <div class="top-area">
                                    <div class="left">
                                        <img src="{{ $review->photo ? asset('storage/images/reviews/'.$review->photo) : asset('assets/images/noimage.png') }}"
                                            alt="">
                                    </div>
                                    <div class="right">
                                        <div class="content">
                                            <h4 class="name">{{ $review->title }}</h4>
                                            <p class="dagenation">{{ $review->subtitle }}</p>
                                        </div>
                                    </div>
                                </div>
                                <blockquote class="review-text">
                                    <p>
                                        {!! $review->details !!}
                                    </p>
                                </blockquote>
                            </div>
                            @endforeach


                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                @foreach($extra_blogs as $blogg)

                <div class="blog-box">
                    <div class="blog-images">
                        <div class="img">
                            <img src="{{ $blogg->photo ? asset('storage/images/blogs/'.$blogg->photo):asset('assets/images/noimage.png') }}"
                                class="img-fluid" alt="">
                            <div class="date d-flex justify-content-center">
                                <div class="box align-self-center">
                                    <p>{{date('d', strtotime($blogg->created_at))}}</p>
                                    <p>{{date('M', strtotime($blogg->created_at))}}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="details">
                        <a href='{{route(' front.blogshow',$blogg->id)}}'>
                            <h4 class="blog-title">
                                {{mb_strlen($blogg->title,'utf-8') > 40 ?
                                mb_substr($blogg->title,0,40,'utf-8')."...":$blogg->title}}
                            </h4>
                        </a>
                        <p class="blog-text">
                            {{substr(strip_tags($blogg->details),0,170)}}
                        </p>
                        <a class="read-more-btn" href="{{route('front.blogshow',$blogg->id)}}">{{ __("Read More") }}</a>
                    </div>
                </div>

                @endforeach

            </div>
        </div>
    </div>
</section>
<!-- Blog Area start-->
@endif

@if($ps->partners == 1)
<!-- Partners Area Start -->
<section class="partners">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-top">
                    <h2 class="section-title">
                        {{ __("Brands") }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="partner-slider">
                    @foreach($partners as $data)
                    <div class="item-slide">
                        <a href="{{ $data->link }}" target="_blank">
                            <img src="{{asset('storage/images/partner/'.$data->photo)}}" alt="">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Partners Area Start -->
@endif

<!-- main -->
<script src="{{asset('assets/front/js/mainextra.js')}}"></script>
