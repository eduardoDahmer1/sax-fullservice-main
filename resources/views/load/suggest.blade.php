@foreach($prods as $prod)
@if(config("features.marketplace"))
<div class="docname">
    <a href="{{ route('front.product', $prod->slug) }}">
        <img src="{{filter_var($prod->thumbnail, FILTER_VALIDATE_URL) ? $prod->thumbnail :
				asset('storage/images/thumbnails/'.$prod->thumbnail)}}" alt="">
        <div class="search-content">
            <p>{!! mb_strlen($prod->name,'utf-8') > 66 ?
                str_replace($slug,'<b>'.$slug.'</b>',mb_substr($prod->name,0,66,'utf-8')).'...' :
                str_replace($slug,'<b>'.$slug.'</b>',$prod->name) !!} </p>
            <span style="font-size: 14px; font-weight:600; display:block;">{{ __("From") }} {{
                $prod->showVendorMinPrice() }} até {{ $prod->showVendorMaxPrice() }}</span>
        </div>
    </a>
</div>
@else
<div class="docname">
    <a href="{{ route('front.product', $prod->slug) }}">
        <img src="{{filter_var($prod->thumbnail, FILTER_VALIDATE_URL) ? $prod->thumbnail :
				asset('storage/images/thumbnails/'.$prod->thumbnail)}}" alt="">
        <div class="search-content">
            <p>{!! mb_strlen($prod->name,'utf-8') > 66 ?
                str_replace($slug,'<b>'.$slug.'</b>',mb_substr($prod->name,0,66,'utf-8')).'...' :
                str_replace($slug,'<b>'.$slug.'</b>',$prod->name) !!} </p>
            <span style="font-size: 14px; font-weight:600; display:block;">{{ $prod->showPrice() }}</span>
        </div>
    </a>
</div>
@endif
@endforeach
<script>
    if(typeof fbq != 'undefined'){
		fbq('track', 'Search', {
			search_string: '{{ $slug }}'
		});
	}
</script>
