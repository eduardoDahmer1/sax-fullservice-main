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
@if ($prod->user_id != 0)
    {{-- check  If This vendor status is active --}}
    @if ($prod->user->is_vendor == 2)
        <div class="card-product-flash">
            <a href="{{ route('front.product', $prod->slug) }}" class="item" data-aos="fade-in" data-aos-delay="{{$loop->index}}00" >
                @if (!is_null($prod->discount_percent))
                    <span class="badge badge-danger descont-card">
                        {{ $prod->discount_percent . '%' }} &nbsp;
                        <span style="font-weight: lighter">
                            {{ 'OFF' }}
                        </span>
                    </span>
                @endif
                <div class="info">
                    @if($prod->promotion_price && $admstore->show_product_prices)
                        <span style="text-decoration: line-through; color: #bababa;">{{ $highlight }}</span>
                    @endif
                    <h4 class="price">{{$curr->sign}}{{$prod->promotion_price}}
                        @if ($curr->id != $scurrency->id)
                            <br><small>{{ $small }}</small>
                        @endif
                    </h4>
                    <h5 class="name">{{ $prod->showName() }}</h5>

                </div>


                <div
                    class="item-img {{ $gs->show_products_without_stock_baw && !is_null($prod->stock) && $prod->stock == 0 ? 'baw' : '' }}">
                    @if ($admstore->reference_code == 1)
                        @php $prod = App\Models\Product::findOrFail($prod->id); @endphp
                        <div class="sell-area ref">
                            <span class="sale">{{ $prod->ref_code }}</span>
                        </div>
                    @endif
                    @if (!empty($prod->features))
                        <div class="sell-area">
                            @foreach ($prod->features as $key => $data1)
                                <span class="sale"
                                    style="background-color:{{ $prod->colors[$key] }}">{{ $prod->features[$key] }}</span>
                            @endforeach
                        </div>
                    @endif
                    <div class="extra-list">
                        <ul>
                            <li>
                                @if (Auth::guard('web')->check())
                                    <span class="add-to-wish" data-href="{{ route('user-wishlist-add', $prod->id) }}"
                                        data-toggle="tooltip" data-placement="right"
                                        title="{{ __('Add To Wishlist') }}" data-placement="right">
                                        <svg class="img-fluid icons-header" width="30" height="30"
                                            viewBox="0 0 30 30" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.5 10.5C2.49985 9.60296 2.67849 8.71489 3.0255 7.88768C3.37251 7.06048 3.88092 6.31074 4.52102 5.68228C5.16111 5.05383 5.92005 4.55926 6.75348 4.22748C7.58691 3.89571 8.47811 3.73339 9.375 3.75C10.4362 3.74436 11.4865 3.96433 12.4562 4.39534C13.426 4.82634 14.2931 5.45853 15 6.25C15.7069 5.45853 16.574 4.82634 17.5438 4.39534C18.5135 3.96433 19.5638 3.74436 20.625 3.75C21.5219 3.73339 22.4131 3.89571 23.2465 4.22748C24.0799 4.55926 24.8389 5.05383 25.479 5.68228C26.1191 6.31074 26.6275 7.06048 26.9745 7.88768C27.3215 8.71489 27.5002 9.60296 27.5 10.5C27.5 17.195 19.5262 22.25 15 26.25C10.4837 22.2163 2.5 17.2 2.5 10.5Z"
                                                fill="currentColor" />
                                        </svg>

                                    </span>
                                @else
                                    <span rel-toggle="tooltip" title="{{ __('Add To Wishlist') }}" data-toggle="modal"
                                        id="wish-btn" data-target="#comment-log-reg" data-placement="right">
                                        <svg class="img-fluid icons-header" width="30" height="30"
                                            viewBox="0 0 30 30" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.5 10.5C2.49985 9.60296 2.67849 8.71489 3.0255 7.88768C3.37251 7.06048 3.88092 6.31074 4.52102 5.68228C5.16111 5.05383 5.92005 4.55926 6.75348 4.22748C7.58691 3.89571 8.47811 3.73339 9.375 3.75C10.4362 3.74436 11.4865 3.96433 12.4562 4.39534C13.426 4.82634 14.2931 5.45853 15 6.25C15.7069 5.45853 16.574 4.82634 17.5438 4.39534C18.5135 3.96433 19.5638 3.74436 20.625 3.75C21.5219 3.73339 22.4131 3.89571 23.2465 4.22748C24.0799 4.55926 24.8389 5.05383 25.479 5.68228C26.1191 6.31074 26.6275 7.06048 26.9745 7.88768C27.3215 8.71489 27.5002 9.60296 27.5 10.5C27.5 17.195 19.5262 22.25 15 26.25C10.4837 22.2163 2.5 17.2 2.5 10.5Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                @endif
                            </li>
                            <x-wedding.product-add-icon :id="$prod->id" />
                            <li>
                                <span class="quick-view" rel-toggle="tooltip" title="{{ __('Quick View') }}"
                                    href="javascript:;" data-href="{{ route('product.quick', $prod->id) }}"
                                    data-toggle="modal" data-target="#quickview" data-placement="right">
                                    <svg class="img-fluid icons-header" width="30" height="27"
                                        viewBox="0 0 30 27" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M29.8187 12.5729C26.9942 7.06199 21.4026 3.33334 15 3.33334C8.59737 3.33334 3.00414 7.06459 0.181222 12.5734C0.0620767 12.8091 0 13.0695 0 13.3336C0 13.5977 0.0620767 13.8581 0.181222 14.0938C3.0057 19.6047 8.59737 23.3333 15 23.3333C21.4026 23.3333 26.9958 19.6021 29.8187 14.0932C29.9379 13.8576 29.9999 13.5972 29.9999 13.3331C29.9999 13.069 29.9379 12.8086 29.8187 12.5729V12.5729ZM15 20.8333C13.5166 20.8333 12.0666 20.3935 10.8332 19.5694C9.59983 18.7453 8.63853 17.5739 8.07087 16.2035C7.50322 14.833 7.35469 13.325 7.64408 11.8702C7.93347 10.4153 8.64778 9.07894 9.69667 8.03004C10.7456 6.98115 12.0819 6.26684 13.5368 5.97745C14.9917 5.68806 16.4997 5.83659 17.8701 6.40425C19.2405 6.9719 20.4119 7.9332 21.236 9.16657C22.0601 10.3999 22.5 11.85 22.5 13.3333C22.5005 14.3184 22.3068 15.2939 21.93 16.204C21.5533 17.1142 21.0009 17.9412 20.3043 18.6377C19.6078 19.3342 18.7808 19.8867 17.8707 20.2634C16.9605 20.6402 15.985 20.8338 15 20.8333V20.8333ZM15 8.33334C14.5537 8.33958 14.1103 8.40598 13.6817 8.53074C14.035 9.01078 14.2045 9.60151 14.1595 10.1958C14.1146 10.7901 13.8581 11.3486 13.4367 11.7701C13.0153 12.1915 12.4567 12.4479 11.8624 12.4929C11.2681 12.5379 10.6774 12.3684 10.1974 12.0151C9.92402 13.0222 9.97336 14.0896 10.3385 15.0672C10.7035 16.0448 11.366 16.8832 12.2326 17.4646C13.0992 18.0459 14.1262 18.3409 15.1692 18.3079C16.2122 18.275 17.2186 17.9158 18.0468 17.2809C18.8749 16.646 19.4832 15.7674 19.7858 14.7688C20.0885 13.7701 20.0704 12.7017 19.734 11.7139C19.3976 10.726 18.7599 9.86857 17.9107 9.26215C17.0615 8.65573 16.0435 8.33089 15 8.33334V8.33334Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                            </li>
                            <li>
                                <span class="add-to-compare" data-href="{{ route('product.compare.add', $prod->id) }}"
                                    data-toggle="tooltip" data-placement="right" title="{{ __('Compare') }}"
                                    data-placement="right">
                                    <svg class="img-fluid icons-header" width="30" height="30"
                                        viewBox="0 0 30 30" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20 17.5L13.75 11.25L20 5L21.75 6.78125L18.5313 10H27.5V12.5H18.5313L21.75 15.7188L20 17.5ZM10 25L16.25 18.75L10 12.5L8.25 14.2813L11.4688 17.5H2.5V20H11.4688L8.25 23.2188L10 25Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <img class="img-fluid"
                        src="{{ filter_var($prod->thumbnail, FILTER_VALIDATE_URL)
                            ? $prod->thumbnail
                            : asset('storage/images/thumbnails/' . $prod->thumbnail) }}"
                        alt="{{ $prod->showName() }}">
                    @if ($gs->is_rating == 1)
                        <div class="stars">
                            <div class="ratings">
                                <div class="empty-stars"></div>
                                <div class="full-stars" style="width:{{ App\Models\Rating::ratings($prod->id) }}%">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if ($gs->is_cart)
                    <div class="item-cart-area">
                        @if ($prod->product_type == 'affiliate')
                            <span class="add-to-cart-btn affilate-btn"
                                data-href="{{ route('affiliate.product', $prod->slug) }}">
                                {{ __('Buy Now') }}
                            </span>
                        @else
                            @if ($prod->emptyStock())
                                <span class="add-to-cart-btn cart-out-of-stock">
                                    <i class="icofont-close-circled"></i> {{ __('Out of Stock!') }}
                                </span>
                            @else
                                @if ($prod->is_available_to_buy())
                                    <span class="add-to-cart add-to-cart-btn"
                                        data-href="{{ route('product.cart.add', $prod->id) }}">
                                        <i class="fas fa-cart-plus"></i>
                                    </span>
                                    <span class="add-to-cart-quick add-to-cart-btn"
                                        data-href="{{ route('product.cart.quickadd', $prod->id) }}">
                                        {{ __('Buy Now') }}
                                    </span>
                                @else
                                    <span class="add-to-cart-btn" href="{{ route('front.product', $prod->slug) }}">
                                        {{ __('Details') }}
                                    </span>
                                @endif
                            @endif
                        @endif
                    </div>
                @else
                    <span class="add-to-cart-btn" href="{{ route('front.product', $prod->slug) }}">
                        {{ __('Details') }}
                    </span>
                @endif

            </a>
            <div class="deal-counter">
                <div data-countdown="{{ $prod->discount_date }}"></div>
            </div>
        </div>

    @endif
    {{-- If This product belongs admin and apply this --}}
@else
    <div class="card-product-flash">
        <a href="{{ route('front.product', $prod->slug) }}" class="item" data-aos="fade-in" data-aos-delay="{{$loop->index}}00">
            @if (!is_null($prod->discount_percent))
                <span class="badge badge-danger descont-card">
                    {{ $prod->discount_percent . '%' }} &nbsp;
                    <span style="font-weight: lighter">
                        {{ 'OFF' }}
                    </span>
                </span>
            @endif


            <div
                class="item-img {{ $gs->show_products_without_stock_baw && !is_null($prod->stock) && $prod->stock == 0 ? 'baw' : '' }}">
                @if ($admstore->reference_code == 1)
                    @php $prod = App\Models\Product::findOrFail($prod->id); @endphp
                    <div class="sell-area ref">
                        <span class="sale">{{ $prod->ref_code }}</span>
                    </div>
                @endif
                @if (!empty($prod->features))
                    <div class="sell-area">
                        @foreach ($prod->features as $key => $data1)
                            <span class="sale"
                                style="background-color:{{ $prod->colors[$key] }}">{{ $prod->features[$key] }}</span>
                        @endforeach
                    </div>
                @endif
                <div class="extra-list">
                    <ul>
                        <li>
                            @if (Auth::guard('web')->check())
                                <span class="add-to-wish" data-href="{{ route('user-wishlist-add', $prod->id) }}"
                                    data-toggle="tooltip" data-placement="right" title="{{ __('Add To Wishlist') }}"
                                    data-placement="right">
                                    <svg class="img-fluid icons-header" width="30" height="30"
                                        viewBox="0 0 30 30" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.5 10.5C2.49985 9.60296 2.67849 8.71489 3.0255 7.88768C3.37251 7.06048 3.88092 6.31074 4.52102 5.68228C5.16111 5.05383 5.92005 4.55926 6.75348 4.22748C7.58691 3.89571 8.47811 3.73339 9.375 3.75C10.4362 3.74436 11.4865 3.96433 12.4562 4.39534C13.426 4.82634 14.2931 5.45853 15 6.25C15.7069 5.45853 16.574 4.82634 17.5438 4.39534C18.5135 3.96433 19.5638 3.74436 20.625 3.75C21.5219 3.73339 22.4131 3.89571 23.2465 4.22748C24.0799 4.55926 24.8389 5.05383 25.479 5.68228C26.1191 6.31074 26.6275 7.06048 26.9745 7.88768C27.3215 8.71489 27.5002 9.60296 27.5 10.5C27.5 17.195 19.5262 22.25 15 26.25C10.4837 22.2163 2.5 17.2 2.5 10.5Z"
                                            fill="currentColor" />
                                    </svg>

                                </span>
                            @else
                                <span rel-toggle="tooltip" title="{{ __('Add To Wishlist') }}" data-toggle="modal"
                                    id="wish-btn" data-target="#comment-log-reg" data-placement="right">
                                    <svg class="img-fluid icons-header" width="30" height="30"
                                        viewBox="0 0 30 30" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.5 10.5C2.49985 9.60296 2.67849 8.71489 3.0255 7.88768C3.37251 7.06048 3.88092 6.31074 4.52102 5.68228C5.16111 5.05383 5.92005 4.55926 6.75348 4.22748C7.58691 3.89571 8.47811 3.73339 9.375 3.75C10.4362 3.74436 11.4865 3.96433 12.4562 4.39534C13.426 4.82634 14.2931 5.45853 15 6.25C15.7069 5.45853 16.574 4.82634 17.5438 4.39534C18.5135 3.96433 19.5638 3.74436 20.625 3.75C21.5219 3.73339 22.4131 3.89571 23.2465 4.22748C24.0799 4.55926 24.8389 5.05383 25.479 5.68228C26.1191 6.31074 26.6275 7.06048 26.9745 7.88768C27.3215 8.71489 27.5002 9.60296 27.5 10.5C27.5 17.195 19.5262 22.25 15 26.25C10.4837 22.2163 2.5 17.2 2.5 10.5Z"
                                            fill="currentColor" />
                                    </svg>

                                </span>
                            @endif
                        </li>
                        <x-wedding.product-add-icon :id="$prod->id" />
                        <li>
                            <span class="quick-view" rel-toggle="tooltip" title="{{ __('Quick View') }}"
                                href="javascript:;" data-href="{{ route('product.quick', $prod->id) }}"
                                data-toggle="modal" data-target="#quickview" data-placement="right">
                                <svg class="img-fluid icons-header" width="30" height="27"
                                    viewBox="0 0 30 27" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M29.8187 12.5729C26.9942 7.06199 21.4026 3.33334 15 3.33334C8.59737 3.33334 3.00414 7.06459 0.181222 12.5734C0.0620767 12.8091 0 13.0695 0 13.3336C0 13.5977 0.0620767 13.8581 0.181222 14.0938C3.0057 19.6047 8.59737 23.3333 15 23.3333C21.4026 23.3333 26.9958 19.6021 29.8187 14.0932C29.9379 13.8576 29.9999 13.5972 29.9999 13.3331C29.9999 13.069 29.9379 12.8086 29.8187 12.5729V12.5729ZM15 20.8333C13.5166 20.8333 12.0666 20.3935 10.8332 19.5694C9.59983 18.7453 8.63853 17.5739 8.07087 16.2035C7.50322 14.833 7.35469 13.325 7.64408 11.8702C7.93347 10.4153 8.64778 9.07894 9.69667 8.03004C10.7456 6.98115 12.0819 6.26684 13.5368 5.97745C14.9917 5.68806 16.4997 5.83659 17.8701 6.40425C19.2405 6.9719 20.4119 7.9332 21.236 9.16657C22.0601 10.3999 22.5 11.85 22.5 13.3333C22.5005 14.3184 22.3068 15.2939 21.93 16.204C21.5533 17.1142 21.0009 17.9412 20.3043 18.6377C19.6078 19.3342 18.7808 19.8867 17.8707 20.2634C16.9605 20.6402 15.985 20.8338 15 20.8333V20.8333ZM15 8.33334C14.5537 8.33958 14.1103 8.40598 13.6817 8.53074C14.035 9.01078 14.2045 9.60151 14.1595 10.1958C14.1146 10.7901 13.8581 11.3486 13.4367 11.7701C13.0153 12.1915 12.4567 12.4479 11.8624 12.4929C11.2681 12.5379 10.6774 12.3684 10.1974 12.0151C9.92402 13.0222 9.97336 14.0896 10.3385 15.0672C10.7035 16.0448 11.366 16.8832 12.2326 17.4646C13.0992 18.0459 14.1262 18.3409 15.1692 18.3079C16.2122 18.275 17.2186 17.9158 18.0468 17.2809C18.8749 16.646 19.4832 15.7674 19.7858 14.7688C20.0885 13.7701 20.0704 12.7017 19.734 11.7139C19.3976 10.726 18.7599 9.86857 17.9107 9.26215C17.0615 8.65573 16.0435 8.33089 15 8.33334V8.33334Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                        </li>
                        <li>
                            <span class="add-to-compare" data-href="{{ route('product.compare.add', $prod->id) }}"
                                data-toggle="tooltip" data-placement="right" title="{{ __('Compare') }}"
                                data-placement="right">
                                <svg class="img-fluid icons-header" width="30" height="30"
                                    viewBox="0 0 30 30" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M20 17.5L13.75 11.25L20 5L21.75 6.78125L18.5313 10H27.5V12.5H18.5313L21.75 15.7188L20 17.5ZM10 25L16.25 18.75L10 12.5L8.25 14.2813L11.4688 17.5H2.5V20H11.4688L8.25 23.2188L10 25Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                        </li>
                    </ul>
                </div>
                <img class="img-fluid"
                    src="{{ filter_var($prod->thumbnail, FILTER_VALIDATE_URL)
                        ? $prod->thumbnail
                        : asset('storage/images/thumbnails/' . $prod->thumbnail) }}"
                    alt="{{ $prod->showName() }}">
                @if ($gs->is_rating == 1)
                    <div class="stars">
                        <div class="ratings">
                            <div class="empty-stars"></div>
                            <div class="full-stars" style="width:{{ App\Models\Rating::ratings($prod->id) }}%"></div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="info">
                @if($prod->promotion_price && $admstore->show_product_prices)
                    <span style="text-decoration: line-through; color: #bababa;">{{ $highlight }}</span>
                @endif
                <h4 class="price">{{$curr->sign}}{{$prod->promotion_price}} 
                    @if ($curr->id != $scurrency->id)
                        <br><small>{{ $small }}</small>
                    @endif
                </h4>
                <h5 class="name">{{ $prod->showName() }}</h5>

            </div>

            @if ($gs->is_cart)
                <div class="item-cart-area">
                    @if ($prod->product_type == 'affiliate')
                        <span class="add-to-cart-btn affilate-btn"
                            data-href="{{ route('affiliate.product', $prod->slug) }}">
                            {{ __('Buy Now') }}
                        </span>
                    @else
                        @if ($prod->emptyStock())
                            <span class="add-to-cart-btn cart-out-of-stock">
                                <i class="icofont-close-circled"></i> {{ __('Out of Stock!') }}
                            </span>
                        @else
                            @if ($prod->is_available_to_buy())
                                <span class="add-to-cart add-to-cart-btn"
                                    data-href="{{ route('product.cart.add', $prod->id) }}">
                                    <i class="fas fa-cart-plus"></i>
                                </span>
                                <span class="add-to-cart-quick add-to-cart-btn"
                                    data-href="{{ route('product.cart.quickadd', $prod->id) }}">
                                    {{ __('Buy Now') }}
                                </span>
                            @else
                                <span class="add-to-cart-btn" href="{{ route('front.product', $prod->slug) }}">
                                    {{ __('Details') }}
                                </span>
                            @endif
                        @endif
                    @endif
                </div>
            @else
                <span class="add-to-cart-btn" href="{{ route('front.product', $prod->slug) }}">
                    {{ __('Details') }}
                </span>
            @endif

        </a>
        <div class="deal-counter">
            <div data-countdown="{{ $prod->discount_date }}"></div>
        </div>
    </div>

@endif
