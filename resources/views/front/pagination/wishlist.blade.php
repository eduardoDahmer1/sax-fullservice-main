<div class="row wish-list-area">

	@php
	if ($gs->switch_highlight_currency) {
	$highlight = $wishlist->firstCurrencyPrice();
	$small = $wishlist->showPrice();
	} else {
	$highlight = $wishlist->showPrice();
	$small = $wishlist->firstCurrencyPrice();
	}
	@endphp

	@foreach($wishlists as $wishlist)

	@if(!empty($sort))

	<div class="col-lg-6">
		<div class="single-wish">
			<span class="remove wishlist-remove"
				data-href="{{ route('user-wishlist-remove', App\Models\Wishlist::where('user_id','=',$user->id)->where('product_id','=',$wishlist->id)->first()->id ) }}"><i
					class="fas fa-times"></i></span>
			<div class="left">
				<img src="{{ $wishlist->photo ? asset('storage/images/products/'.$wishlist->photo):asset('assets/images/noimage.png') }}"
					alt="">
			</div>
			<div class="right">
				<h4 class="title">
					<a href="{{ route('front.product', $wishlist->slug) }}">
						{{ $wishlist->name }}
					</a>
				</h4>
				@if($gs->is_rating == 1)
				<ul class="stars">
					<div class="ratings">
						<div class="empty-stars"></div>
						<div class="full-stars" style="width:{{App\Models\Rating::ratings($wishlist->id)}}%"></div>
					</div>
				</ul>
				@endif
				<div class="price">
					{{ $highlight }}<small>{{ $small }}</small>
				</div>
			</div>
		</div>
	</div>

	@else

	<div class="col-lg-6">
		<div class="single-wish">
			<span class="remove wishlist-remove" data-href="{{ route('user-wishlist-remove',$wishlist->id) }}"><i
					class="fas fa-times"></i></span>
			<div class="left">
				<img src="{{filter_var($wishlist->product->photo, FILTER_VALIDATE_URL) ? $wishlist->product->photo :
								asset('storage/images/products/'.$wishlist->product->photo)}}" alt="">
			</div>
			<div class="right">
				<h4 class="title">
					<a href="{{ route('front.product', $wishlist->product->slug) }}">
						{{ $wishlist->product->name }}
					</a>
				</h4>
				@if($gs->is_rating == 1)
				<ul class="stars">
					<div class="ratings">
						<div class="empty-stars"></div>
						<div class="full-stars" style="width:{{App\Models\Rating::ratings($wishlist->product->id)}}%">
						</div>
					</div>
				</ul>
				@endif
				<div class="price">
					{{ $highlight }}<small>{{ $small }}</small>
				</div>
			</div>
		</div>
	</div>

	@endif
	@endforeach

</div>

@if(isset($sort))

<div class="page-center category">
	{!! $wishlists->appends(['sort' => $sort])->links() !!}
</div>

@else

<div class="page-center category">
	{!! $wishlists->links() !!}
</div>

@endif
<script type="text/javascript">
	$("#sortby").on('change',function () {
        var sort = $("#sortby").val();
        window.location = "{{url('/user/wishlists')}}?sort="+sort;
    	});
</script>
