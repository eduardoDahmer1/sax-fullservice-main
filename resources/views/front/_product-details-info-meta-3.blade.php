<div class="col-lg-12">
    <div class="info-meta-3">
        <ul class="meta-list">
    
            @if(!empty($productt->attributes))
                @php
                    $attrArr = json_decode($productt->attributes, true);
                @endphp
            @endif
    
            @if(!empty($attrArr))
    
                @if($gs->attribute_clickable)
                    @include('front._product-details-attribute-clickable')
                @else
                    @include('front._product-details-attribute-normal')
                @endif
    
            @endif
    
            @include('front._product-details-info-meta-3-custom')
    
            @include('front._product-details-info-meta-3-custom-number')
    
        </ul>
    </div>
</div>

<div class="col-lg-12">
    <div class="info-meta-3">
        <ul class="meta-list">
            <div class="d-flex flex-wrap">
                @if($gs->is_cart)
                    @if($productt->product_type == "affiliate")
                    <li class="addtocart">
                        <a href="{{ route('affiliate.product', $productt->slug) }}" target="_blank">
                            <i class="icofont-cart"></i>
                            {{ __("Buy Now") }}
                        </a>
                    </li>
                    @else

                        @if($productt->emptyStock() && !$productt->associatedProductsBySize->contains(fn($product) => $product->stock  ? true : false ))
                        <li class="addtocart">
                            <a href="javascript:;" class="cart-out-of-stock">
                                <i class="icofont-close-circled"></i>
                                {{ __("Out of Stock!") }}</a>
                        </li>
                        @else
                        <li class="addtocart">
                            <a href="javascript:;" id="addcrt">
                                @if(!env('ENABLE_SAX_BRIDAL'))
                                    <img width="19px" class="mr-1" src="{{asset('assets/images/theme15/bagicone.png')}}" alt="">
                                        {{ __("+ Add to Bag") }}
                                @else
                                    <img width="22px" src="{{asset('assets/images/theme15/wishicone.png')}}" alt="">
                                        {{ __("+ Add to Bag") }}
                                @endif
                            </a>
                        </li>
                        <li class="addtocart">
                            <a id="qaddcrt" href="javascript:;">
                                <img width="19px" class="mr-1" src="{{asset('assets/images/theme15/carrinho.png')}}" alt="">
                                {{ __("Buy Now") }}
                            </a>
                        </li>
                        @endif
                    @endif
                @endif
    
                @if(Auth::guard('web')->check() && !env('ENABLE_SAX_BRIDAL'))
                    <li class="favorite">
                        <a href="javascript:;" class="add-to-wish" data-href="{{ route('user-wishlist-add',$productt->id) }}">
                            <img width="22px" src="{{asset('assets/images/theme15/wishicone.png')}}" alt="">
                        </a>
                    </li>
                @else
                    @if(!env('ENABLE_SAX_BRIDAL'))
                        <li class="favorite">
                            <a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg">
                                <img width="22px" src="{{asset('assets/images/theme15/wishicone.png')}}" alt="">
                            </a>
                        </li>
                    @endif
                @endif

                @if(!env('ENABLE_SAX_BRIDAL'))
                    <x-wedding.product-add-icon class="d-flex flex-column justify-content-center cursor-pointer" :id="$productt->id" />
        
                    <li class="compare">
                        <a href="javascript:;" class="add-to-compare" data-href="{{ route('product.compare.add',$productt->id) }}">
                            <i class="icofont-exchange"></i>
                        </a>
                    </li>
                @endif
            </div>
        </ul>
    </div>
</div>