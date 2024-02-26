@extends('front.themes.'.env('THEME', 'theme-01').'.layout')

@section('content')

<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="pages">
                    <li>
                        <a href="{{ route('front.index') }}">
                            {{ __("Home") }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user-wishlists') }}">
                            {{ __("Wishlists") }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->

<section>
    <img src="{{ asset('assets/front/themes/theme-15/assets/images/Banner_Sax.png') }}" class="img-fluid w-100">
</section>

<!-- Wish List Area Start -->
<section class="sub-categori wish-list">
    <div class="container">

        @if(count($wishlists))
        <div class="right-area">
            @include('includes.wishlist-filter')
            <div id="ajaxContent">
                <div class="row wish-list-area">

                    @foreach($wishlists as $wishlist)

                    @if(!empty($sort))

                    @php
                    if ($gs->switch_highlight_currency) {
                    $highlight = $wishlist->firstCurrencyPrice();
                    $small = $wishlist->showPrice();
                    } else {
                    $highlight = $wishlist->showPrice();
                    $small = $wishlist->firstCurrencyPrice();
                    }
                    @endphp

                    <div class="col-lg-6">
                        <div class="single-wish">
                            <span class="remove wishlist-remove"
                                data-href="{{ route('user-wishlist-remove', App\Models\Wishlist::where('user_id','=',$user->id)->where('product_id','=',$wishlist->id)->first()->id ) }}"><i
                                    class="fas fa-times"></i>
                            </span>
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
                                        <div class="full-stars"
                                            style="width:{{App\Models\Rating::ratings($wishlist->id)}}%"></div>
                                    </div>
                                </ul>
                                @endif
                                <div class="price">
                                    {{ $highlight }} <small>{{ $small }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @else

                    @php
                    if ($gs->switch_highlight_currency) {
                    $highlight = $wishlist->product->firstCurrencyPrice();
                    $small = $wishlist->product->showPrice();
                    } else {
                    $highlight = $wishlist->product->showPrice();
                    $small = $wishlist->product->firstCurrencyPrice();
                    }
                    @endphp

                    <div class="col-lg-6">
                        <div class="single-wish">
                            <span class="remove wishlist-remove"
                                data-href="{{ route('user-wishlist-remove',$wishlist->id) }}"><i
                                    class="fas fa-times"></i>
                            </span>
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
                                        <div class="full-stars"
                                            style="width:{{App\Models\Rating::ratings($wishlist->product->id)}}%"></div>
                                    </div>
                                </ul>
                                @endif
                                <div class="price">
                                    {{ $highlight }} <small>{{ $small }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endif
                    @endforeach

                </div>

                <div style="padding-bottom: 100px;" class="page-center category">
                    {{ $wishlists->appends(['sort' => $sort])->links() }}
                </div>

            </div>
        </div>
        @else
            <div class="page-center">
                <h2>Oops! :(</h2>
                <h4 class="text-center pt-3">{{ __("No Product Found.") }}</h4>
            </div>
            @include('front.themes.theme-15.components.best-sellers')
        @endif

    </div>
</section>
<!-- Wish List Area End -->

@endsection

@section('scripts')

<script type="text/javascript">
    $("#sortby").on('change',function () {
        var sort = $("#sortby").val();
        window.location = "{{url('/user/wishlists')}}?sort="+sort;
    	});
</script>

@endsection
