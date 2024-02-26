@if ($ps->featured_category == 1)
    @php
        $categoryhasimage = false;
        foreach ($categories->where('is_featured', '=', 1) as $cat) {
            if (!empty($cat->image)) {
                $categoryhasimage = true;
                break;
            }
            $categoryhasimage = false;
        }
    @endphp
    {{-- Slider buttom Category Start --}}
    <section class="slider-buttom-category categorias-destaq">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 remove-padding">
                    <div class="section-top text-center">
                        <h2 class="section-title" data-aos="fade-in">
                            {{ __("Featured Categories") }}
                        </h2>
                        <h5 data-aos="fade-in" data-aos-delay="100">{{__("Browse our wide range of categories and find what you are looking for.")}}</h5>
                    </div>
                </div>
            </div>
            <div class="row pt-4 justify-content-center">
                @foreach ($categories->where('is_featured', '=', 1) as $cat)
                    <div class="col-6 col-md-2 d-flex flex-column align-items-center" data-aos="fade-in" data-aos-delay="{{$loop->index}}00">
                        <a href="{{ route('front.category', $cat->slug) }}">
                            <img loading="lazy" class="img-fluid mb-3" src="{{ asset('storage/images/categories/' . $cat->image) }}"
                                alt="Image in featured for category {{ $cat->name }}">
                            <h5 class="text-center font-weight-light">{{ $cat->name }}</h5>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    {{-- Slider buttom banner End --}}

@endif
