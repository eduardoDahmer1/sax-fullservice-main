@if($ps->bottom_small == 1)
<!-- Banner Area One Start -->
@if(env("THEME") == "theme-07" || env("THEME") == "theme-08" )
<section class="small-banners-2">
    @else
    <section>
        @endif
        <div class="container px-4">
            @foreach($bottom_small_banners->chunk(3) as $chunk)
            <div class="row {{ env(" THEME")=="theme-08" ? "justify-content-center" : "" }}">
                @foreach($chunk as $img)
                <div class="col-12 col-lg-4">
                    <div class="left">

                        <a class="banner-effect shadow-banner" href="{{ $img->link }}" target="_blank">

                            <img src="{{asset('storage/images/banners/'.$img->photo)}}" alt="">
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @break
            @endforeach
        </div>
    </section>
    <!-- Banner Area One Start -->
    @endif
