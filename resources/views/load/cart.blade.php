@if(Session::has('cart'))
<div class="dropdownmenu-wrapper">
    <div class="dropdown-cart-header">
        <span class="item-no">
            <span class="cart-quantity">
                {{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}
            </span> {{ __("Item(s)") }}
        </span>

        <a class="view-cart" href="{{ route('front.cart') }}">
            {{ __("View Cart") }}
        </a>
    </div><!-- End .dropdown-cart-header -->
    <ul class="dropdown-cart-products">
        @foreach(Session::get('cart')->items as $product)
        @php
        $custom_item_id =
        $product['item']['id'].$product['size'].$product['material'].$product['color'].$product['customizable_gallery'].$product['customizable_name'].$product['customizable_number'].$product['customizable_logo'].str_replace(str_split('
        ,'),'',$product['values']);
        $custom_item_id = str_replace( array( '\'', '"', ',', '.', ' ', ';', '<', '>' ), '' , $custom_item_id); @endphp
            <li class="product cremove{{ str_replace(['~', '/', '-'],'',$custom_item_id) }}">
            <div class="product-details">
                <div class="content">
                    <a href="{{ route('front.product',$product['item']['slug']) }}">
                        <h4 class="product-title">
                            {{mb_strlen($product['item']->name,'utf-8') > 45 ?
                            mb_substr($product['item']->name,0,45,'utf-8').'...' : $product['item']->name}}
                        </h4>
                    </a>

                        <span class="cart-product-info">
                            <span class="cart-product-qty" id="cqt{{ $custom_item_id }}">{{$product['qty']}}</span><span>{{
                                $product['item']['measure'] }}</span>
                            x
                            @if($admstore->show_product_prices)
                                @if($product['item']['promotion_price'] && $product['item']['promotion_price'] > 0 && $product['item']['promotion_price'] < $product['item']['price'])
                                    <span id="prct{{ $custom_item_id }}">{{
                                        App\Models\Product::convertPrice($product['item']['promotion_price']) }}
                                    </span>
                                @else
                                    <span id="prct{{ $custom_item_id }}">{{
                                        App\Models\Product::convertPrice($product['item']['price']) }}
                                    </span>
                                @endif
                            @endif
                        </span>
                   
                </div>
            </div><!-- End .product-details -->

            <figure class="product-image-container">
                <a href="{{ route('front.product', $product['item']['slug']) }}" class="product-image">
                    <img src="{{filter_var($product['item']['photo'], FILTER_VALIDATE_URL) ? $product['item']['photo'] :
					asset('storage/images/products/'.$product['item']['photo'])}}" alt="product">
                </a>
                <div class="cart-remove" data-class="cremove{{ str_replace(['~', '/', '-'],'',$custom_item_id) }}"
                    data-href="{{ route('product.cart.remove',$custom_item_id) }}" title="Remove Product">
                    <i class="icofont-close"></i>
                </div>
            </figure>
            </li><!-- End .product -->
            @endforeach
    </ul><!-- End .cart-product -->

    @if($admstore->show_product_prices)
        <div class="dropdown-cart-total">
            <span>{{ __("Total") }}</span>

            <span class="cart-total-price">
                <span class="cart-total">{{ Session::has('cart') ?
                    App\Models\Product::convertPrice(Session::get('cart')->totalPrice) : '0.00' }}
                </span>
            </span>
        </div><!-- End .dropdown-cart-total -->
    @endif

    @if($gs->is_standard_checkout && !env('ENABLE_SAX_BRIDAL'))
    <div class="dropdown-cart-action">
        <a href="{{ route('front.checkout') }}" class="mybtn1">{{ __("Checkout") }}</a>
    </div><!-- End .dropdown-cart-total -->
    @endif

    <!-- Desativado a opção de wpp antes da pessoa fazer login no checkout" -->
    {{-- Essa opcao e para a bridal, pois eles tem apenas o checkout simplificado --}}
    @if(env('ENABLE_SAX_BRIDAL'))
        <div class="dropdown-cart-action">
            <a href="#" class="mybtn1 px-1" data-toggle="modal" data-target="#simplified-checkout-modal">
                {{ __("Simplified Checkout") }}
            </a>
        </div>
    @endif 
</div>
@else
<p class="mt-1 pl-3 text-left">{{ __("Cart is empty.") }}</p>
@endif
