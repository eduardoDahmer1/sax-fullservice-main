@if ($ps->reviews_store == 1)
    <section class="blog-area">
        <div class="container">
            <div class="row">
            
                <div class="col-lg-12">
                    <div class="section-top">
                        <h2 class="section-title" data-aos="fade-in" >
                            {{ __('Blog of the week') }}
                        </h2>
                        <h5 data-aos="fade-in" data-aos-delay="100">{{__("Stay on top of all the news")}}</h5>
                    </div>
                    <div class="py-4">
                        <div class="blog-carousel owl-theme">
                            @foreach ($extra_blogs->take(4) as $post)
                            <div class="item">
                                <div class="blog-box" data-aos="fade-in" data-aos-delay="{{ $loop->index }}00">
                                    <div style="background: url('{{ $post->photo ? asset('storage/images/blogs/' . $post->photo) : asset('assets/images/noimage.png') }}'); background-position: center;
                                    background-size: cover; height: 260px; width:100%;"></div>
                                  
                                    <div class="box-infos pt-2">
                                        <p class="date-blog">
                                            <i class="fa fa-calendar-days"></i>
                                            {{$post->created_at->format('d M, Y')}}
                                        </p>

                                        <a href='{{ route('front.blogshow', $post->id) }}'>
                                            <h4 class="blog-title">
                                                {{ str($post->title)->limit(55,'...') }}
                                            </h4>
                                        </a>
                                        <div class="details">
                                            <a class="read-more-btn"
                                                href="{{ route('front.blogshow', $post->id) }}">{{ __('Check it out on our blog') }}</a>
                                        </div>
                                    </div>
                                </div>
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
