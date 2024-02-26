@if ($ps->slider == 1)
    @if (count($sliders))
        @include('includes.slider-style')
    @endif
@endif

@if ($ps->slider == 1)

    <!-- main-gallery of the Page -->
    <section class="main-slider">
        <!-- social-networks of the page -->
        <ul class="list-unstyled social-network">
            @if ($socials->f_status == 1)
            <li class="facebook">
                <a href="{{ $socials->facebook }}" class="icon-facebook">
                    <i class="fab fa-facebook"></i>
                </a>
            </li>
            @endif
            
            @if ($socials->t_status == 1)
            <li>
                <a href="{{ $socials->twitter }}" class="icon-twitter">
                    <i class="fab fa-twitter"></i>
                </a>
            </li>
            @endif

            @if ($socials->i_status == 1)
            <li class="instagram">
                <a href="{{ $socials->instagram }}">
                    <i class="fab fa-instagram"></i>
                </a>
            </li>
            @endif
        </ul>
        <!-- Main Slider of the page -->
        <div id="main-slider">
          @foreach ($sliders as $data)
            {{-- <Slide of the page --> --}}
            <div class="slide">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        {{-- <h1 class="slider-heading">{{ $data->title_text }}</h1> --}}
                        <div class="img-holder">
                            <a href="{{ $data->link }}">
                                <img loading="lazy" class='img-fluid' src="{{ asset('storage/images/sliders/' . $data->photo) }}" alt="image description">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
          @endforeach

        </div>
    </section>
    <!-- main-banner of the page -->

    {{-- <!-- Hero Area Start -->
    <section class="hero-area">
        @if ($ps->slider == 1)

            @if (count($sliders))

                <div class="hero-area-slider">
                    <div class="intro-carousel">
                        @foreach ($sliders as $data)
                            <a href="{{ $data->link }}" target="_blank">
                                <div class="intro-content ">
                                    <img src="{{ asset('storage/images/sliders/' . $data->photo) }}">
                                    <div class="slider-content">
                                        <!-- layer 1 -->
                                        <div class="layer-1">
                                            <h4 style="font-size: {{ $data->subtitle_size }}px; color: {{ $data->subtitle_color }}"
                                                class="subtitle subtitle{{ $data->id }}"
                                                data-animation="animated {{ $data->subtitle_anime }}">
                                                {{ $data->subtitle_text }}</h4>
                                            <h2 style="font-size: {{ $data->title_size }}px; color: {{ $data->title_color }}"
                                                class="title title{{ $data->id }}"
                                                data-animation="animated {{ $data->title_anime }}">
                                                {{ $data->title_text }}</h2>
                                        </div>
                                        <!-- layer 2 -->
                                        <div class="layer-2">
                                            <p style="font-size: {{ $data->details_size }}px; color: {{ $data->details_color }}"
                                                class="text text{{ $data->id }}"
                                                data-animation="animated {{ $data->details_anime }}">
                                                {{ $data->details_text }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                </div>

            @endif

        @endif

    </section>
    <!-- Hero Area End --> --}}
@endif
