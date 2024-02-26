@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')
<style>
    .name{
        height: auto;
        font-weight: 300;
        line-height: normal;
        margin-bottom: 10px;
        font-size: 20px;
        color: #333;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        font-family: 'Cormorant', serif;
    }

    .selected {
        background: #f3f8fc;
    }

    .wishlist:hover {
        text-decoration: underline;
    }

    #share:hover {
        cursor: pointer;
        opacity: 0.8;
    }
</style>
<div class="container-fluid mt-2 mb-5">
    <div class="row px-5">
        <div class="col-12">
            <h2 class="h2 mb-3">
                @if (auth()->check() && $wishlistGroup->user_id === auth()->user()->id)
                    {{__('Your wishlists')}}
                @else
                    {{__('Wishlist')}}
                @endif
            </h2>
            <div class="row border" style="padding: 15px;">
                @if ($wishlistsGroup->count())
                    <div class="col-md-2 col-12">
                        <div class="row flex-column" style="padding-right: 15px;">
                            @foreach ($wishlistsGroup as $group)
                                <a @class([
                                    'col-12 my-3 px-2 aling-center wishlist d-flex justify-content-between w-100', 
                                    'selected py-2 text-dark' => $group->id == $wishlistGroup->id
                                ]) href="{{route('user-wishlists.show', $group)}}" >
                                    <span>{{$group->name}}</span>
                                    <small>{{$group->is_public ? __('Public') : __('Private')}}</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div @class(['col-12', 'col-md-10' => $wishlistsGroup->count()])>
                    <div class="row w-100 justify-content-between">
                        <h1 class="d-inline-block h3 mb-3">
                            {{$wishlistGroup->name}}
                        </h1>
                        @auth
                            @if ($wishlistGroup->user_id === auth()->user()->id)
                                <div class="d-flex">
                                    @if ($wishlistGroup->is_public)    
                                        <div id="share" class="mr-3" onclick="share()" style="margin-top: 4px;">
                                            <i class="fas fa-share-alt"></i>
                                            {{__('Share')}}
                                        </div>
                                    @endif
                                    <div>
                                        <input @checked($wishlistGroup->is_public) class="styled-checkbox" id="checkboxPrivacity" type="checkbox" name="privacity">
                                        <label for="checkboxPrivacity">{{ __('Public wishlist ?') }}</label>
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>
                    <div class="row">
                        @foreach ($wishlistGroup->wishlists as $wishlist)
                            <div class="col-12 py-3 px-2 aling-center wishlist border-top">
                                <div class="row">
                                    <div class="col-md-2 col-4">
                                        <img src="{{$wishlist->product->image}}">
                                    </div>
                                    <div class="col-md-7 col-5">
                                        <p class="m-0" style="font-weight: 500;font-size: 13px;">{{$wishlist->product->brand->name}}</p>
                                        <a class="d-inline name" href="{{route('front.product', $wishlist->product->slug)}}">
                                            {{$wishlist->product->name}}
                                        </a>
                                    </div>
                                    <div class="col-3 d-flex flex-column justify-content-center">
                                        @if ( !auth()->check()  
                                            || (auth()->check() && $wishlistGroup->user_id !== auth()->user()->id)
                                        )
                                            <div class="row justify-content-center">
                                                <button class="btn btn-dark add-to-cart w-auto" data-href="{{route('product.cart.add', $wishlist->product->id)}}">
                                                    {{__('Add To Cart')}}
                                                </button>
                                            </div>
                                        @else
                                            <div class="row justify-content-center mb-3">
                                                <button class="btn btn-dark add-to-cart-quick w-auto" data-href="{{route('product.cart.quickadd', $wishlist->product->id)}}">
                                                    {{__('Shop Now')}}
                                                </button>
                                            </div>
                                            <div class="row px-4 justify-content-center">
                                                <button class="btn btn-light border add-to-cart mr-2" data-href="{{route('product.cart.add', $wishlist->product->id)}}">
                                                    <i class="fas fa-cart-plus"></i>
                                                </button>
                                                <button class="btn btn-light border wishlist-remove" data-href="{{ route('user-wishlist-remove', $wishlist->id)}}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        @endif
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
<script>
    $("#checkboxPrivacity").on('click', function() {
        var url = "{{route('user-wishlists.privacity', $wishlistGroup->id)}}"
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            type: 'POST',
            url: url,
            success: () => window.location.reload()
        });
    });

    function share() {
        let url = "{{ url()->current() }}"
        navigator.clipboard.writeText(url)
    }
</script>
@endsection