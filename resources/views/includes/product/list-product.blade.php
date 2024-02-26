@php
if ($gs->switch_highlight_currency) {
$highlight = $prod->firstCurrencyPrice();
$small = $prod->showPrice();
} else {
$highlight = $prod->showPrice();
$small = $prod->firstCurrencyPrice();
}
@endphp
{{-- If This product belongs to vendor then apply this --}}
@if($prod->user_id != 0)

{{-- check If This vendor status is active --}}
@if($prod->user->is_vendor == 2)

<li>
    <div class="single-box">
        <div class="left-area {{ $gs->show_products_without_stock_baw && !is_null($prod->stock) && $prod->stock == 0 ? "
            baw":"" }}">
            <img src="{{filter_var($prod->thumbnail, FILTER_VALIDATE_URL) ? $prod->thumbnail :
																	asset('storage/images/thumbnails/'.$prod->thumbnail)}}" alt="">
        </div>
        <div class="right-area">
            @if($gs->is_rating == 1)
            <div class="stars">
                <div class="ratings">
                    <div class="empty-stars"></div>
                    <div class="full-stars" style="width:{{App\Models\Rating::ratings($prod->id)}}%"></div>
                </div>
            </div>
            @endif
            <h4 class="price">{{ $highlight }}
                @if(!is_null($prod->discount_percent))
                <span class="badge badge-danger " style="background: red; font-size: 14px">
                    {{ "-".$prod->discount_percent."%"}}
                </span>
                @endif
                @if($curr->id != $scurrency->id)<small>{{ $small }}</small> @endif
            </h4>
            <p class="text"><a href="{{ route('front.product',$prod->slug) }}">{{ mb_strlen($prod->name,'utf-8') > 35 ?
                    mb_substr($prod->name,0,35,'utf-8').'...' : $prod->name }}</a></p>
        </div>
    </div>
</li>


@endif

{{-- If This product belongs admin and apply this --}}

@else

<li>
    <div class="single-box">
        <div class="left-area {{ $gs->show_products_without_stock_baw && !is_null($prod->stock) && $prod->stock == 0 ? "
            baw":"" }}">
            <a href="{{ route('front.product',$prod->slug) }}">
                <img src="{{filter_var($prod->thumbnail, FILTER_VALIDATE_URL) ? $prod->thumbnail :
																	asset('storage/images/thumbnails/'.$prod->thumbnail)}}">
            </a>
        </div>
        <div class="right-area">
            @if($gs->is_rating == 1)
            <div class="stars">
                <div class="ratings">
                    <div class="empty-stars"></div>
                    <div class="full-stars" style="width:{{App\Models\Rating::ratings($prod->id)}}%"></div>
                </div>
            </div>
            @endif
            @if(!config("features.marketplace"))
            <h4 class="price"> {{ $highlight }}
                @if(!is_null($prod->discount_percent))
                <span class="badge badge-danger " style="background: red; font-size: 14px">
                    {{ "-".$prod->discount_percent."%"}}
                </span>
                @endif
                @if($curr->id != $scurrency->id)<small>{{ $small }} </small> @endif
            </h4>
            @else
            <h4 class="price">{{ $prod->showVendorMinPrice() }} até {{ $prod->showVendorMaxPrice() }}
                @if($curr->id != $scurrency->id)
                <small><span id="originalprice">{{ $small }}</span></small>
                @endif
            </h4>
            @endif
            <p class="text"><a href="{{ route('front.product',$prod->slug) }}">{{ mb_strlen($prod->name,'utf-8') > 35 ?
                    mb_substr($prod->name,0,35,'utf-8').'...' : $prod->name }}</a></p>
        </div>
    </div>
</li>


@endif
