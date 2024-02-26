@if ($ps->reviews_store == 1)
<section style="padding: 3rem 0;">
    <div class="container">
        <div class="row justify-content-center" data-aos="fade-in">
            <div class="col-md-6">
                <div class="aside bg-section-items">
                    <div class="slider-wrapper">
                        <div class="aside-review-slider">
                            @foreach ($reviews as $review)
                                <div class="slide-item">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img class="img-testimonials" src="{{ $review->photo ? asset('storage/images/reviews/' . $review->photo) : asset('assets/images/noimage.png') }}" alt="">
                                        <h4 class="name">{{ $review->title }}</h4>
                                        <p style="font-weight:300;color: rgb(118, 118, 118);">{{ $review->subtitle }}</p>
                                        <h5><small>{!! $review->details !!}</small></h5>
                                    </div>
                                    {{-- <div class="top-area">
                                        <div class="content">
                                            <img src="
                                                alt="">
                                            <h4 class="name">{{ $review->title }}</h4>
                                            <p class="dagenation">{{ $review->subtitle }}</p>
                                            <blockquote class="review-text">
                                                <p>
                                                    {!! $review->details !!}
                                                </p>
                                            </blockquote>
                                        </div>
                                    </div> --}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif