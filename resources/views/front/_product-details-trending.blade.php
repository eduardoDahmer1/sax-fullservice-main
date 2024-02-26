<!-- Trending Item Area Start -->
<div class="trending">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="section-top">
                    <h2 class="section-title">
                        {{ __("Complete the look") }}
                    </h2>
                    <h5>{{__("Discover the next piece to add to your collection")}}</h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="trending-item-slider">
                    @foreach($related_products as $prod)
                        @include('includes.product.slider-product')
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-md-3" data-aos="fade-in">
                <a class="btn btn-style-1" href="{{ route('front.category') }}">{{ __('See all')}}</a>
            </div>
        </div>
    </div>
</div>
<!-- Tranding Item Area End -->
