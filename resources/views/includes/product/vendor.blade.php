<div class="col-lg-3 col-md-3 col-6 remove-padding">

    <a href="{{ route('front.product', $prod->slug) }}" class="item-vendor">
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
                <span class="sale" style="background-color:{{ $prod->colors[$key] }}">{{ $prod->features[$key] }}</span>
                @endforeach
            </div>
            @endif
            <div class="extra-list">
                <ul>
                    <li>
                        @if (Auth::guard('web')->check())

                        <span class="add-to-wish" data-href="{{ route('user-wishlist-add', $prod->id) }}"
                            data-toggle="tooltip" data-placement="right" title="{{ __('Add To Wishlist') }}"
                            data-placement="right"><i class="icofont-heart-alt"></i>
                        </span>

                        @else

                        <span rel-toggle="tooltip" title="{{ __('Add To Wishlist') }}" data-toggle="modal" id="wish-btn"
                            data-target="#comment-log-reg" data-placement="right">
                            <i class="icofont-ui-love-add"></i>
                        </span>

                        @endif
                    </li>
                    <li>
                        <span class="quick-view" rel-toggle="tooltip" title="{{ __('Quick View') }}" href="javascript:;"
                            data-href="{{ route('product.quick', $prod->id) }}" data-toggle="modal"
                            data-target="#quickview" data-placement="right"> <i class="icofont-eye-alt"></i>
                        </span>
                    </li>
                    <li>
                        <span class="add-to-compare" data-href="{{ route('product.compare.add', $prod->id) }}"
                            data-toggle="tooltip" data-placement="right" title="{{ __('Compare') }}"
                            data-placement="right">
                            <i class="icofont-exchange"></i>
                        </span>
                    </li>
                </ul>
            </div>
            <img class="img-fluid"
                src="{{ filter_var($prod->thumbnail, FILTER_VALIDATE_URL) ? $prod->thumbnail : asset('storage/images/thumbnails/' . $prod->thumbnail) }}"
                alt="">
            @if ($admstore->reference_code == 1)
            <span class="badge badge-primary" style="background-color: {{ $admstore->ref_color }}">{{ $prod->ref_code
                }}</span>
            @endif
        </div>
        <div class="info">
            <h4 class="price">{{ $prod->setCurrency() }} <small>{{ $prod->setCurrencyFirst() }}</small></h4>
            <h5 class="name">{{ $prod->showName() }}</h5>
        </div>
    </a>

</div>
