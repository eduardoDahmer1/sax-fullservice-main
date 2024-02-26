@if($ps->partners == 1)
<!-- Partners Area Start -->
<section class="partners">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 row-theme">
                <div class="partner-slider">
                    @foreach($partners as $data)
                    <div class="item-slide">
                        <a href="{{ $data->link }}" target="_blank">
                            <img loading="lazy" src="{{asset('storage/images/partner/'.$data->photo)}}" alt="Logo Shop Sax">
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
