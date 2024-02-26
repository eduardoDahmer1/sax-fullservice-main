@if($ps->small_banner == 1)

<!-- Banner Area One Start -->
<section class="banner-section">
    <div class="container">
        @foreach($top_small_banners->chunk(2) as $chunk)
        <div class="row">
            @foreach($chunk as $img)
            <div class="col-lg-6">
                <div class="left">
                    <a class="banner-effect" href="{{ $img->link }}" target="_blank">
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
