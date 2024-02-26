@if (count($prods) > 0)
    @foreach ($prods as $key => $prod)
        <div class="col-lg-3 col-md-4 col-6 remove-padding">
            <a href="{{ route('front.product', $prod->slug) }}" class="item">
                @if (!is_null($prod->discount_percent))
                    <span class="badge badge-danger descont-card">
                        {{ $prod->discount_percent . '%' }} &nbsp;
                        <span style="font-weight: lighter">
                            {{ 'OFF' }}
                        </span>
                    </span>
                @endif
                <div class="info">

                    @php
                        if ($gs->switch_highlight_currency) {
                            $highlight = $prod->firstCurrencyPrice();
                            $small = $prod->showPrice();
                        } else {
                            $highlight = $prod->showPrice();
                            $small = $prod->firstCurrencyPrice();
                        }
                    @endphp

                    <p class="m-0" style="font-weight: 500;font-size: 13px;">{{ $prod->brand->name }}</p>
                    <h5 class="name">{{ $prod->showName() }}</h5>
                    @if($admstore->show_product_prices)
                       @if($prod->promotion_price > 0 && $prod->promotion_price != $highlight )
                            <span style="text-decoration: line-through; color: #bababa;">{{ $highlight }}</span>
                            <h4 class="price">{{$curr->sign}}{{$prod->promotion_price}}
                                @if ($curr->id != $scurrency->id)
                                    <small>{{ $small }}</small>
                                @endif
                            </h4>
                        @else
                        <span style="text-decoration: line-through; color: #bababa;"> <br> </span>
                            <h4 class="price">{{ $highlight }}
                                @if ($curr->id != $scurrency->id)
                                    <small>{{ $small }}</small>
                                @endif
                            </h4>
                        @endif
                    @endif
                </div>

                <div
                    class="item-img {{ $gs->show_products_without_stock_baw && !is_null($prod->stock) && $prod->stock == 0 ? 'baw' : '' }}">
                    @if ($admstore->reference_code == 1)
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
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 448.45 448.45" style="enable-background:new 0 0 448.45 448.45;"
                                            xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path
                                                        d="M446.285,250.825c-5.322-24.01-21.278-44.294-43.36-55.12v0c-6.892-3.388-14.3-5.605-21.92-6.56
                                            c1.36-3.84,2.56-8,3.6-11.52c7.714-31.008,0.882-63.842-18.56-89.2c-20.05-27.267-51.457-43.884-85.28-45.12
                                            c-36.314-1.332-70.143,18.386-86.88,50.64c-16.765-32.267-50.623-51.983-86.96-50.64c-33.795,1.26-65.167,17.874-85.2,45.12
                                            c-19.47,25.345-26.331,58.179-18.64,89.2c12.72,48.8,50.16,83.52,89.84,120c12.4,11.52,25.28,23.44,37.28,35.84
                                            c32,33.36,57.44,61.12,57.68,61.36c2.972,3.27,8.031,3.511,11.301,0.539c0.188-0.171,0.368-0.351,0.539-0.539
                                            c0,0,10.8-12,27.12-29.36c7.12,20,11.76,34.08,11.84,34.24c1.379,4.198,5.9,6.482,10.097,5.104
                                            c0.266-0.087,0.527-0.189,0.783-0.304c0,0,24-10.88,54.88-23.28c11.28-4.56,22.96-8.56,34.32-12.48
                                            c36.64-12.24,71.2-24.32,93.52-52.72C446.647,297.562,451.802,273.556,446.285,250.825z M193.886,377.945
                                            c-9.36-10.24-28.88-31.28-52.16-55.28c-12.32-12.64-25.28-24.72-37.84-36.4c-37.92-35.2-73.76-68.4-85.28-112.64
                                            c-6.475-26.347-0.585-54.208,16-75.68c17.164-23.31,44.031-37.51,72.96-38.56c58.8-2.32,78,53.44,78.72,56
                                            c1.38,4.197,5.901,6.482,10.098,5.102c2.415-0.794,4.308-2.687,5.102-5.102c0-0.56,19.44-57.92,78.72-56
                                            c28.929,1.05,55.796,15.25,72.96,38.56c16.585,21.472,22.475,49.333,16,75.68c-1.423,5.448-3.214,10.794-5.36,16
                                            c-10.968,1.564-21.456,5.525-30.72,11.6c-3.21-24.908-19.692-46.108-43.04-55.36c-22.909-9.147-48.738-7.35-70.16,4.88
                                            c-20.526,11.219-34.812,31.19-38.8,54.24c-5.84,35.92,8.64,69.52,24,105.12c4.72,11.04,9.68,22.4,13.84,33.76l1.92,5.12
                                            C209.086,361.385,199.566,371.705,193.886,377.945z M420.126,306.105l0-0.16c-19.84,25.04-51.84,36.24-86.56,48.08
                                            c-11.52,4-23.44,8-35.12,12.8c-20.24,8-37.68,16-47.36,19.92c-3.36-10-9.6-28-17.2-48.4c-4.4-11.76-9.36-23.36-14.16-34.56
                                            c-14.4-33.36-27.92-64.88-22.88-96c3.219-18.253,14.589-34.041,30.88-42.88c9.757-5.547,20.776-8.495,32-8.56
                                            c8.222,0.01,16.367,1.585,24,4.64c37.2,14.88,33.76,54.64,33.6,56c-0.457,4.395,2.736,8.327,7.131,8.784
                                            c2.553,0.265,5.078-0.711,6.789-2.624c1.12-1.28,28.24-30.64,64-13.12c17.829,8.655,30.732,24.975,35.04,44.32
                                            C434.949,272.245,431.21,291.295,420.126,306.105z" />
                                                </g>
                                            </g>
                                        </svg>

                                    </span>
                                @else
                                    <span rel-toggle="tooltip" title="{{ __('Add To Wishlist') }}" data-toggle="modal"
                                        id="wish-btn" data-target="#comment-log-reg" data-placement="right">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 448.45 448.45" style="enable-background:new 0 0 448.45 448.45;"
                                            xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path
                                                        d="M446.285,250.825c-5.322-24.01-21.278-44.294-43.36-55.12v0c-6.892-3.388-14.3-5.605-21.92-6.56
                                            c1.36-3.84,2.56-8,3.6-11.52c7.714-31.008,0.882-63.842-18.56-89.2c-20.05-27.267-51.457-43.884-85.28-45.12
                                            c-36.314-1.332-70.143,18.386-86.88,50.64c-16.765-32.267-50.623-51.983-86.96-50.64c-33.795,1.26-65.167,17.874-85.2,45.12
                                            c-19.47,25.345-26.331,58.179-18.64,89.2c12.72,48.8,50.16,83.52,89.84,120c12.4,11.52,25.28,23.44,37.28,35.84
                                            c32,33.36,57.44,61.12,57.68,61.36c2.972,3.27,8.031,3.511,11.301,0.539c0.188-0.171,0.368-0.351,0.539-0.539
                                            c0,0,10.8-12,27.12-29.36c7.12,20,11.76,34.08,11.84,34.24c1.379,4.198,5.9,6.482,10.097,5.104
                                            c0.266-0.087,0.527-0.189,0.783-0.304c0,0,24-10.88,54.88-23.28c11.28-4.56,22.96-8.56,34.32-12.48
                                            c36.64-12.24,71.2-24.32,93.52-52.72C446.647,297.562,451.802,273.556,446.285,250.825z M193.886,377.945
                                            c-9.36-10.24-28.88-31.28-52.16-55.28c-12.32-12.64-25.28-24.72-37.84-36.4c-37.92-35.2-73.76-68.4-85.28-112.64
                                            c-6.475-26.347-0.585-54.208,16-75.68c17.164-23.31,44.031-37.51,72.96-38.56c58.8-2.32,78,53.44,78.72,56
                                            c1.38,4.197,5.901,6.482,10.098,5.102c2.415-0.794,4.308-2.687,5.102-5.102c0-0.56,19.44-57.92,78.72-56
                                            c28.929,1.05,55.796,15.25,72.96,38.56c16.585,21.472,22.475,49.333,16,75.68c-1.423,5.448-3.214,10.794-5.36,16
                                            c-10.968,1.564-21.456,5.525-30.72,11.6c-3.21-24.908-19.692-46.108-43.04-55.36c-22.909-9.147-48.738-7.35-70.16,4.88
                                            c-20.526,11.219-34.812,31.19-38.8,54.24c-5.84,35.92,8.64,69.52,24,105.12c4.72,11.04,9.68,22.4,13.84,33.76l1.92,5.12
                                            C209.086,361.385,199.566,371.705,193.886,377.945z M420.126,306.105l0-0.16c-19.84,25.04-51.84,36.24-86.56,48.08
                                            c-11.52,4-23.44,8-35.12,12.8c-20.24,8-37.68,16-47.36,19.92c-3.36-10-9.6-28-17.2-48.4c-4.4-11.76-9.36-23.36-14.16-34.56
                                            c-14.4-33.36-27.92-64.88-22.88-96c3.219-18.253,14.589-34.041,30.88-42.88c9.757-5.547,20.776-8.495,32-8.56
                                            c8.222,0.01,16.367,1.585,24,4.64c37.2,14.88,33.76,54.64,33.6,56c-0.457,4.395,2.736,8.327,7.131,8.784
                                            c2.553,0.265,5.078-0.711,6.789-2.624c1.12-1.28,28.24-30.64,64-13.12c17.829,8.655,30.732,24.975,35.04,44.32
                                            C434.949,272.245,431.21,291.295,420.126,306.105z" />
                                                </g>
                                            </g>
                                        </svg>

                                    </span>
                                @endif
                            </li>
                            <x-wedding.product-add-icon :id="$prod->id" />
                            <li>
                                <span class="quick-view" rel-toggle="tooltip" title="{{ __('Quick View') }}"
                                    href="javascript:;" data-href="{{ route('product.quick', $prod->id) }}"
                                    data-toggle="modal" data-target="#quickview" data-placement="right">
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
                                        xml:space="preserve">
                                        <g>
                                            <g>
                                                <path
                                                    d="M510.484,251.474C447.574,168.069,354.819,120.235,256,120.235S64.425,168.07,1.515,251.474
                                            c-2.02,2.679-2.02,6.371,0,9.051C64.425,343.93,157.181,391.765,256,391.765s191.574-47.834,254.484-131.239
                                            C512.505,257.846,512.505,254.153,510.484,251.474z M256,376.736c-92.263,0-179.064-43.928-239.014-120.736
                                            C76.936,179.192,163.737,135.264,256,135.264c92.262,0,179.063,43.928,239.014,120.736
                                            C435.063,332.808,348.262,376.736,256,376.736z" />
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M345.357,176.3c-2.763-3.096-7.514-3.367-10.611-0.603c-3.096,2.764-3.366,7.515-0.603,10.611
                                            c17.128,19.19,26.562,43.942,26.562,69.692c0,57.734-46.971,104.704-104.705,104.704c-57.735,0-104.705-46.971-104.705-104.704
                                            S198.265,151.295,256,151.295c16.904,0,33.036,3.902,47.945,11.596c3.686,1.901,8.22,0.456,10.124-3.232
                                            c1.903-3.688,0.456-8.221-3.232-10.124c-16.821-8.681-35.783-13.269-54.836-13.269c-66.022,0-119.734,53.712-119.734,119.734
                                            S189.978,375.734,256,375.734S375.734,322.021,375.734,256C375.734,226.552,364.945,198.248,345.357,176.3z" />
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M256,208.407c-26.242,0-47.593,21.35-47.593,47.593c0,26.242,21.351,47.593,47.593,47.593s47.593-21.351,47.593-47.593
                                            S282.242,208.407,256,208.407z M256,288.563c-17.955,0-32.564-14.608-32.564-32.564s14.609-32.564,32.564-32.564
                                            c17.956,0,32.564,14.608,32.564,32.564S273.956,288.563,256,288.563z" />
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                            </li>
                            <li>
                                <span class="add-to-compare" data-href="{{ route('product.compare.add', $prod->id) }}"
                                    data-toggle="tooltip" data-placement="right" title="{{ __('Compare') }}"
                                    data-placement="right">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
                                        xml:space="preserve">
                                        <g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M508.479,162.74l-106.667-96c-4.354-3.938-11.104-3.594-15.083,0.792c-3.938,4.375-3.583,11.125,0.792,15.063
                                                L473.542,160H181.333c-5.896,0-10.667,4.771-10.667,10.667s4.771,10.667,10.667,10.667h292.208l-86.021,77.406
                                                c-4.375,3.938-4.729,10.688-0.792,15.063c2.125,2.344,5.021,3.531,7.938,3.531c2.542,0,5.104-0.906,7.146-2.74l106.667-96
                                                c2.229-2.021,3.521-4.906,3.521-7.927C512,167.646,510.708,164.761,508.479,162.74z" />
                                                    <path
                                                        d="M330.667,330.667H38.458l86.021-77.406c4.375-3.938,4.729-10.688,0.792-15.063c-3.979-4.385-10.708-4.729-15.083-0.792
                                                l-106.667,96C1.292,335.427,0,338.313,0,341.333s1.292,5.906,3.521,7.927l106.667,96c2.042,1.833,4.604,2.74,7.146,2.74
                                                c2.917,0,5.813-1.188,7.938-3.531c3.938-4.375,3.583-11.125-0.792-15.063L38.458,352h292.208c5.896,0,10.667-4.771,10.667-10.667
                                                S336.563,330.667,330.667,330.667z" />
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <img class="img-fluid"
                        src="{{ filter_var($prod->photo, FILTER_VALIDATE_URL)
                            ? $prod->photo
                            : asset('storage/images/products/' . $prod->photo) }}"
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
                                        {{ __('Add To Cart') }}
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
        </div>
    @endforeach
    <div class="col-lg-12">
        <div class="page-center mt-5">
            {!! $prods->appends(['search' => request()->input('search')])->links() !!}
        </div>
    </div>
@else
    @include('front.themes.theme-11.components.no-prod-found')
@endif
@if (isset($ajax_check))
    <script type="text/javascript">
        // Tooltip Section
        $('[data-toggle="tooltip"]').tooltip({});
        $('[data-toggle="tooltip"]').on('click', function() {
            $(this).tooltip('hide');
        });
        $('[rel-toggle="tooltip"]').tooltip();
        $('[rel-toggle="tooltip"]').on('click', function() {
            $(this).tooltip('hide');
        });
        // Tooltip Section Ends
    </script>
@endif
