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
    {{-- check If This vendor status is active --}}
    @if ($prod->user->is_vendor == 2)
        <a href="{{ route('front.product', $prod->slug) }}" class="item">
            @if (!is_null($prod->discount_percent))
                <span class="badge badge-danger descont-card">
                    {{ $prod->discount_percent . '%' }} &nbsp;
                    <span style="font-weight: lighter">
                        {{ 'OFF' }}
                    </span>
                </span>
            @endif
            <div
                class="item-img {{ $gs->show_products_without_stock_baw && !is_null($prod->stock) && $prod->stock == 0
                    ? "
                        baw"
                    : '' }}">
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
                                    <svg width="25" height="25" viewBox="0 0 30 30" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.5 10.5C2.49985 9.60296 2.67849 8.71489 3.0255 7.88768C3.37251 7.06048 3.88092 6.31074 4.52102 5.68228C5.16111 5.05383 5.92005 4.55926 6.75348 4.22748C7.58691 3.89571 8.47811 3.73339 9.375 3.75C10.4362 3.74436 11.4865 3.96433 12.4562 4.39534C13.426 4.82634 14.2931 5.45853 15 6.25C15.7069 5.45853 16.574 4.82634 17.5438 4.39534C18.5135 3.96433 19.5638 3.74436 20.625 3.75C21.5219 3.73339 22.4131 3.89571 23.2465 4.22748C24.0799 4.55926 24.8389 5.05383 25.479 5.68228C26.1191 6.31074 26.6275 7.06048 26.9745 7.88768C27.3215 8.71489 27.5002 9.60296 27.5 10.5C27.5 17.195 19.5262 22.25 15 26.25C10.4837 22.2163 2.5 17.2 2.5 10.5Z" fill="currentColor"/>
                                    </svg>                                        
                                </span>
                            @else
                                <span rel-toggle="tooltip" title="{{ __('Add To Wishlist') }}" data-toggle="modal"
                                    id="wish-btn" data-target="#comment-log-reg" data-placement="right">
                                    <svg width="25" height="25" viewBox="0 0 30 30" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.5 10.5C2.49985 9.60296 2.67849 8.71489 3.0255 7.88768C3.37251 7.06048 3.88092 6.31074 4.52102 5.68228C5.16111 5.05383 5.92005 4.55926 6.75348 4.22748C7.58691 3.89571 8.47811 3.73339 9.375 3.75C10.4362 3.74436 11.4865 3.96433 12.4562 4.39534C13.426 4.82634 14.2931 5.45853 15 6.25C15.7069 5.45853 16.574 4.82634 17.5438 4.39534C18.5135 3.96433 19.5638 3.74436 20.625 3.75C21.5219 3.73339 22.4131 3.89571 23.2465 4.22748C24.0799 4.55926 24.8389 5.05383 25.479 5.68228C26.1191 6.31074 26.6275 7.06048 26.9745 7.88768C27.3215 8.71489 27.5002 9.60296 27.5 10.5C27.5 17.195 19.5262 22.25 15 26.25C10.4837 22.2163 2.5 17.2 2.5 10.5Z" fill="currentColor"/>
                                    </svg>                                        
                                </span>
                            @endif
                        </li>
                        <li>
                            <span class="quick-view" rel-toggle="tooltip" title="{{ __('Quick View') }}"
                                href="javascript:;" data-href="{{ route('product.quick', $prod->id) }}"
                                data-toggle="modal" data-target="#quickview" data-placement="right">
                                <svg width="25" height="25" viewBox="0 0 28 25" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M27.8308 11.7347C25.1946 6.59118 19.9757 3.11111 14 3.11111C8.02421 3.11111 2.80386 6.59361 0.16914 11.7352C0.0579382 11.9552 0 12.1982 0 12.4447C0 12.6912 0.0579382 12.9342 0.16914 13.1542C2.80532 18.2977 8.02421 21.7778 14 21.7778C19.9757 21.7778 25.1961 18.2953 27.8308 13.1537C27.942 12.9337 27.9999 12.6907 27.9999 12.4442C27.9999 12.1977 27.942 11.9547 27.8308 11.7347V11.7347ZM14 19.4444C12.6155 19.4444 11.2621 19.0339 10.111 18.2647C8.95984 17.4956 8.06263 16.4023 7.53282 15.1232C7.003 13.8441 6.86438 12.4367 7.13448 11.0788C7.40457 9.72095 8.07126 8.47367 9.05023 7.4947C10.0292 6.51573 11.2765 5.84905 12.6343 5.57895C13.9922 5.30885 15.3997 5.44748 16.6788 5.97729C17.9578 6.5071 19.0511 7.40431 19.8203 8.55546C20.5894 9.7066 21 11.06 21 12.4444C21.0004 13.3638 20.8197 14.2743 20.468 15.1238C20.1164 15.9732 19.6008 16.7451 18.9507 17.3952C18.3006 18.0453 17.5288 18.5609 16.6793 18.9125C15.8298 19.2641 14.9194 19.4449 14 19.4444V19.4444ZM14 7.77778C13.5834 7.7836 13.1696 7.84557 12.7696 7.96202C13.0993 8.41005 13.2575 8.96141 13.2156 9.51609C13.1736 10.0708 12.9342 10.592 12.5409 10.9854C12.1476 11.3787 11.6263 11.6181 11.0716 11.66C10.5169 11.702 9.96558 11.5438 9.51754 11.2141C9.26242 12.154 9.30847 13.1503 9.64922 14.0627C9.98997 14.9751 10.6083 15.7577 11.4171 16.3003C12.2259 16.8428 13.1845 17.1181 14.1579 17.0874C15.1314 17.0566 16.0707 16.7214 16.8437 16.1288C17.6166 15.5363 18.1843 14.7162 18.4668 13.7842C18.7492 12.8521 18.7323 11.8549 18.4184 10.9329C18.1044 10.011 17.5093 9.21066 16.7167 8.64467C15.9241 8.07868 14.9739 7.77549 14 7.77778V7.77778Z" fill="currentColor"/>
                                </svg>                                    
                            </span>
                        </li>
                        <li>
                            <span class="add-to-compare" data-href="{{ route('product.compare.add', $prod->id) }}"
                                data-toggle="tooltip" data-placement="right" title="{{ __('Compare') }}"
                                data-placement="right">
                                <svg width="25" height="25" viewBox="0 0 28 28" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.6666 16.3333L12.8333 10.5L18.6666 4.66666L20.3 6.32916L17.2958 9.33333H25.6666V11.6667H17.2958L20.3 14.6708L18.6666 16.3333ZM9.33331 23.3333L15.1666 17.5L9.33331 11.6667L7.69998 13.3292L10.7041 16.3333H2.33331V18.6667H10.7041L7.69998 21.6708L9.33331 23.3333Z" fill="currentColor"/>
                                </svg>                                    
                            </span>
                        </li>
                    </ul>
                </div>
                <img class="img-fluid"
                    src="{{ filter_var($prod->thumbnail, FILTER_VALIDATE_URL)
                        ? $prod->thumbnail
                        : asset('storage/images/thumbnails/' . $prod->thumbnail) }}"
                    alt="">
                @if ($admstore->reference_code == 1)
                    <span class="badge badge-primary"
                        style="background-color: {{ $admstore->ref_color }} ">{{ $prod->ref_code }}</span>
                @endif
                @if ($gs->is_rating == 1)
                    <div class="stars">
                        <div class="ratings">
                            <div class="empty-stars"></div>
                            <div class="full-stars" style="width:{{ App\Models\Rating::ratings($prod->id) }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
            @if (config('features.marketplace'))
                <div class="info">
                    @if ($prod->user->isVendor())
                        <p style="color: green; font-weight: 600"> {{ __('Sold By') }} {{ $prod->user->shop_name }}
                        </p>
                    @endif
                </div>
            @endif
            <div class="info">
                
                <h5 class="name">{{ $prod->showName() }}</h5>
                <h4 class="price">{{ $highlight }} @if ($curr->id != $scurrency->id)
                        <small>{{ $small }}</small>
                    @endif
                </h4>
                
                @if ($gs->is_cart)
                    <div class="item-cart-area">
                        @if ($prod->product_type == 'affiliate')
                            <span class="add-to-cart-btn affilate-btn"
                                data-href="{{ route('affiliate.product', $prod->slug) }}"><i class="icofont-cart"></i>
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
                                        <svg width="25" height="23" viewBox="0 0 25 23" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_305_10)">
                                            <path d="M1.04167 0C0.46441 0 0 0.46441 0 1.04167C0 1.61892 0.46441 2.08333 1.04167 2.08333H3.30295L5.92014 15.8203C6.01563 16.3108 6.44531 16.6667 6.94444 16.6667H21.1806C21.7578 16.6667 22.2222 16.2023 22.2222 15.625C22.2222 15.0477 21.7578 14.5833 21.1806 14.5833H7.80816L7.41319 12.5H21.1719C21.7925 12.5 22.3394 12.0877 22.5087 11.4887L24.8524 3.15538C25.0998 2.26997 24.4358 1.38889 23.5156 1.38889H5.29514L5.19097 0.846354C5.09549 0.355903 4.6658 0 4.16667 0H1.04167ZM7.63889 22.2222C8.78906 22.2222 9.72222 21.2891 9.72222 20.1389C9.72222 18.9887 8.78906 18.0556 7.63889 18.0556C6.48872 18.0556 5.55556 18.9887 5.55556 20.1389C5.55556 21.2891 6.48872 22.2222 7.63889 22.2222ZM22.2222 20.1389C22.2222 18.9887 21.2891 18.0556 20.1389 18.0556C18.9887 18.0556 18.0556 18.9887 18.0556 20.1389C18.0556 21.2891 18.9887 22.2222 20.1389 22.2222C21.2891 22.2222 22.2222 21.2891 22.2222 20.1389ZM10.9375 6.94444C10.9375 6.46701 11.3281 6.07639 11.8056 6.07639H13.7153V4.16667C13.7153 3.68924 14.1059 3.29861 14.5833 3.29861C15.0608 3.29861 15.4514 3.68924 15.4514 4.16667V6.07639H17.3611C17.8385 6.07639 18.2292 6.46701 18.2292 6.94444C18.2292 7.42188 17.8385 7.8125 17.3611 7.8125H15.4514V9.72222C15.4514 10.1997 15.0608 10.5903 14.5833 10.5903C14.1059 10.5903 13.7153 10.1997 13.7153 9.72222V7.8125H11.8056C11.3281 7.8125 10.9375 7.42188 10.9375 6.94444Z" fill="currentColor"/>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_305_10">
                                            <rect width="25" height="22.2222" fill="white"/>
                                            </clipPath>
                                            </defs>
                                        </svg>
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
            </div>
        </a>
    @endif
    {{-- If This product belongs admin and apply this --}}
@else
    <a href="{{ route('front.product', $prod->slug) }}" class="item">
        @if (!is_null($prod->discount_percent))
            <span class="badge badge-danger descont-card">
                {{ $prod->discount_percent . '%' }} &nbsp;
                <span style="font-weight: lighter">
                    {{ 'OFF' }}
                </span>
            </span>
        @endif
        <div
            class="item-img {{ $gs->show_products_without_stock_baw && !is_null($prod->stock) && $prod->stock == 0
                ? "
                    baw"
                : '' }}">
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
                                <svg width="25" height="25" viewBox="0 0 30 30" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.5 10.5C2.49985 9.60296 2.67849 8.71489 3.0255 7.88768C3.37251 7.06048 3.88092 6.31074 4.52102 5.68228C5.16111 5.05383 5.92005 4.55926 6.75348 4.22748C7.58691 3.89571 8.47811 3.73339 9.375 3.75C10.4362 3.74436 11.4865 3.96433 12.4562 4.39534C13.426 4.82634 14.2931 5.45853 15 6.25C15.7069 5.45853 16.574 4.82634 17.5438 4.39534C18.5135 3.96433 19.5638 3.74436 20.625 3.75C21.5219 3.73339 22.4131 3.89571 23.2465 4.22748C24.0799 4.55926 24.8389 5.05383 25.479 5.68228C26.1191 6.31074 26.6275 7.06048 26.9745 7.88768C27.3215 8.71489 27.5002 9.60296 27.5 10.5C27.5 17.195 19.5262 22.25 15 26.25C10.4837 22.2163 2.5 17.2 2.5 10.5Z" fill="currentColor"/>
                                </svg>                                        
                            </span>
                        @else
                            <span rel-toggle="tooltip" title="{{ __('Add To Wishlist') }}" data-toggle="modal"
                                id="wish-btn" data-target="#comment-log-reg" data-placement="right">
                                <svg width="25" height="25" viewBox="0 0 30 30" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.5 10.5C2.49985 9.60296 2.67849 8.71489 3.0255 7.88768C3.37251 7.06048 3.88092 6.31074 4.52102 5.68228C5.16111 5.05383 5.92005 4.55926 6.75348 4.22748C7.58691 3.89571 8.47811 3.73339 9.375 3.75C10.4362 3.74436 11.4865 3.96433 12.4562 4.39534C13.426 4.82634 14.2931 5.45853 15 6.25C15.7069 5.45853 16.574 4.82634 17.5438 4.39534C18.5135 3.96433 19.5638 3.74436 20.625 3.75C21.5219 3.73339 22.4131 3.89571 23.2465 4.22748C24.0799 4.55926 24.8389 5.05383 25.479 5.68228C26.1191 6.31074 26.6275 7.06048 26.9745 7.88768C27.3215 8.71489 27.5002 9.60296 27.5 10.5C27.5 17.195 19.5262 22.25 15 26.25C10.4837 22.2163 2.5 17.2 2.5 10.5Z" fill="currentColor"/>
                                </svg>                                        
                            </span>
                        @endif
                    </li>
                    <li>
                        <span class="quick-view" rel-toggle="tooltip" title="{{ __('Quick View') }}"
                            href="javascript:;" data-href="{{ route('product.quick', $prod->id) }}"
                            data-toggle="modal" data-target="#quickview" data-placement="right">
                            <svg width="25" height="25" viewBox="0 0 28 25" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M27.8308 11.7347C25.1946 6.59118 19.9757 3.11111 14 3.11111C8.02421 3.11111 2.80386 6.59361 0.16914 11.7352C0.0579382 11.9552 0 12.1982 0 12.4447C0 12.6912 0.0579382 12.9342 0.16914 13.1542C2.80532 18.2977 8.02421 21.7778 14 21.7778C19.9757 21.7778 25.1961 18.2953 27.8308 13.1537C27.942 12.9337 27.9999 12.6907 27.9999 12.4442C27.9999 12.1977 27.942 11.9547 27.8308 11.7347V11.7347ZM14 19.4444C12.6155 19.4444 11.2621 19.0339 10.111 18.2647C8.95984 17.4956 8.06263 16.4023 7.53282 15.1232C7.003 13.8441 6.86438 12.4367 7.13448 11.0788C7.40457 9.72095 8.07126 8.47367 9.05023 7.4947C10.0292 6.51573 11.2765 5.84905 12.6343 5.57895C13.9922 5.30885 15.3997 5.44748 16.6788 5.97729C17.9578 6.5071 19.0511 7.40431 19.8203 8.55546C20.5894 9.7066 21 11.06 21 12.4444C21.0004 13.3638 20.8197 14.2743 20.468 15.1238C20.1164 15.9732 19.6008 16.7451 18.9507 17.3952C18.3006 18.0453 17.5288 18.5609 16.6793 18.9125C15.8298 19.2641 14.9194 19.4449 14 19.4444V19.4444ZM14 7.77778C13.5834 7.7836 13.1696 7.84557 12.7696 7.96202C13.0993 8.41005 13.2575 8.96141 13.2156 9.51609C13.1736 10.0708 12.9342 10.592 12.5409 10.9854C12.1476 11.3787 11.6263 11.6181 11.0716 11.66C10.5169 11.702 9.96558 11.5438 9.51754 11.2141C9.26242 12.154 9.30847 13.1503 9.64922 14.0627C9.98997 14.9751 10.6083 15.7577 11.4171 16.3003C12.2259 16.8428 13.1845 17.1181 14.1579 17.0874C15.1314 17.0566 16.0707 16.7214 16.8437 16.1288C17.6166 15.5363 18.1843 14.7162 18.4668 13.7842C18.7492 12.8521 18.7323 11.8549 18.4184 10.9329C18.1044 10.011 17.5093 9.21066 16.7167 8.64467C15.9241 8.07868 14.9739 7.77549 14 7.77778V7.77778Z" fill="currentColor"/>
                            </svg>                                    
                        </span>
                    </li>
                    <li>
                        <span class="add-to-compare" data-href="{{ route('product.compare.add', $prod->id) }}"
                            data-toggle="tooltip" data-placement="right" title="{{ __('Compare') }}"
                            data-placement="right">
                            <svg width="25" height="25" viewBox="0 0 28 28" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.6666 16.3333L12.8333 10.5L18.6666 4.66666L20.3 6.32916L17.2958 9.33333H25.6666V11.6667H17.2958L20.3 14.6708L18.6666 16.3333ZM9.33331 23.3333L15.1666 17.5L9.33331 11.6667L7.69998 13.3292L10.7041 16.3333H2.33331V18.6667H10.7041L7.69998 21.6708L9.33331 23.3333Z" fill="currentColor"/>
                            </svg>                                    
                        </span>
                    </li>
                </ul>
            </div>
            <div>
                <img class="img-fluid"
                    src="{{ filter_var($prod->thumbnail, FILTER_VALIDATE_URL)
                        ? $prod->thumbnail
                        : asset('storage/images/thumbnails/' . $prod->thumbnail) }}"
                    alt="">
            </div>
            @if ($admstore->reference_code == 1)
                <span class="badge badge-primary"
                    style="background-color: {{ $admstore->ref_color }} ">{{ $prod->ref_code }}</span>
            @endif
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
            <h5 class="name">{{ $prod->showName() }}</h5>
            @if (!config('features.marketplace'))
                <h4 class="price">{{ $highlight }} @if ($curr->id != $scurrency->id)
                        <small>{{ $small }}</small>
                    @endif
                </h4>
            @else
                <h4 class="price">{{ $prod->showVendorMinPrice() }} até {{ $prod->showVendorMaxPrice() }}
                    @if ($curr->id != $scurrency->id)
                        <small><span id="originalprice">{{ $small }}</span></small>
                    @endif
                </h4>
            @endif
            
            @if ($gs->is_cart)
                <div class="item-cart-area">
                    @if ($prod->product_type == 'affiliate')
                        <span class="add-to-cart-btn affilate-btn"
                            data-href="{{ route('affiliate.product', $prod->slug) }}"><i class="icofont-cart"></i>
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
        </div>
    </a>
@endif
