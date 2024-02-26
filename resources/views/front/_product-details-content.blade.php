<div class="col-lg-5 pl-lg-5">
    <div class="right-area">
        <div class="product-info">
            <!-- @if($isAdmin)
            <div class="mybadge1">
                {{ __('Viewing as Admin')}}
            </div>
            @endif -->
            
            <a href="{{ route('front.brand', $productt->brand->slug) }}" class="d-block mb-2">
                <img src="{{$productt->brand->image ? asset('storage/images/brands/'.$productt->brand->image) : asset('assets/images/noimage.png') }}"
                    alt="{{$productt->brand->name}}" width="55px">
            </a>

            <h4 class="product-name">{{ $productt->name }}</h4>

            @if($productt->product_size != null)
            <h4>
                <span class="badge badge-primary" style="background-color: {{$admstore->ref_color}}">{{ __('Reference
                    Code') }}: <span id="size-code"></span>
                </span>
            </h4>
            @else
            <h4>
                <span class="badge badge-primary" style="background-color: {{$admstore->ref_color}}">{{ __('Reference
                    Code') }}: {{$productt->ref_code}}</span>
                </span>
            </h4>
            @endif

            @if($productt->show_price)
            @include('front._product-details-show-price')
            @endif

            @include('front._product-details-info-meta-1')

            @include('front._product-details-info-meta-2')

            @if(!empty($productt->size))
            @include('front._product-details-size')
            @endif

            <div class="row">
                <div class="col-lg-6">
                    @if(!empty($productt->color))
                        @include('front._product-details-color')
                    @endif
                </div>
                <div class="col-lg-6 list-attr">
                    <div class="product-color product-size">

                        @if ($productt->product_size)
                            <p class="title">{{__("Sizes")}} :</p>
                            <span class="boxassociatedProductSize">
                                <input class="input-associatedProductSize" @disabled($productt->stock == 0) @checked($productt->stock > 0) 
                                name="associatedProductsBySize" 
                                type="radio" 
                                id="associatedProductsBySize0"
                                data-product-stock="{{$productt->stock}}" 
                                data-product-id="{{$productt->id}}"
                                data-product-code="{{$productt->ref_code}}">
                                <label for="associatedProductsBySize0">
                                    {{$productt->product_size}}
                                </label>
                            </span>
                        @endif
                        
                        @if ( preg_match("/[0-9]/", $productt->product_size) )
                            @foreach ($productt->associatedProductsBySize->sortBy('product_size') as $productSize)
                                <span class="boxassociatedProductSize">
                                    <input class="input-associatedProductSize"
                                    @disabled($productSize->stock == 0)
                                    @checked($productt->stock == 0 && $productSize->stock != 0)
                                    name="associatedProductsBySize" 
                                    type="radio" 
                                    id="associatedProductsBySize{{$loop->index + 1}}"
                                    data-product-stock="{{$productSize->stock}}" 
                                    data-product-id="{{$productSize->id}}"
                                    data-product-code="{{$productSize->ref_code}}">
                                    <label for="associatedProductsBySize{{$loop->index+1}}">
                                        {{ str_replace(',', '.', $productSize->product_size) }}
                                    </label>
                                </span>
                            @endforeach
                        @else
                            @foreach ($productt->associatedProductsBySize->sortByDesc('product_size') as $productSize)
                                <span class="boxassociatedProductSize">
                                    <input class="input-associatedProductSize"
                                    @disabled($productSize->stock == 0)
                                    @checked($productt->stock == 0 && $productSize->stock != 0)
                                    name="associatedProductsBySize" 
                                    type="radio" 
                                    id="associatedProductsBySize{{$loop->index + 1}}"
                                    data-product-stock="{{$productSize->stock}}" 
                                    data-product-id="{{$productSize->id}}"
                                    data-product-code="{{$productSize->ref_code}}">
                                    <label for="associatedProductsBySize{{$loop->index+1}}">
                                        {{$productSize->product_size}}
                                    </label>
                                </span>
                            @endforeach
                        @endif

                        

                    </div>
                </div>
                @include('front._product-details-info-meta-3')
            </div>

            @php
            $stck = (string) $productt->stock;
            @endphp

            @if($stck != null)
            <input type="hidden" id="stock" value="{{ $stck }}">
            @elseif($productt->type != 'Physical')
            <input type="hidden" id="stock" value="0">
            @else
            <input type="hidden" id="stock" value="">
            @endif

            <input type="hidden" id="product_price"
                value="{{ round($productt->vendorPrice(),2) * $product_curr->value,2 }}">
            <input type="hidden" id="product_id" value="{{ $productt->id }}">
            <input type="hidden" id="curr_pos" value="{{ $gs->currency_format }}">
            <input type="hidden" id="dec_sep" value="{{ $product_curr->decimal_separator }}">
            <input type="hidden" id="tho_sep" value="{{ $product_curr->thousands_separator }}">
            <input type="hidden" id="dec_dig" value="{{ $product_curr->decimal_digits }}">
            <input type="hidden" id="dec_sep2" value="{{ $first_curr->decimal_separator }}">
            <input type="hidden" id="tho_sep2" value="{{ $first_curr->thousands_separator }}">
            <input type="hidden" id="dec_dig2" value="{{ $first_curr->decimal_digits }}">
            <input type="hidden" id="curr_sign" value="{{ $product_curr->sign }}">
            <input type="hidden" id="first_sign" value="{{ $first_curr->sign }}">
            <input type="hidden" id="currency_value" value="{{ $product_curr->value }}">
            <input type="hidden" id="curr_value" value="{{ $product_curr->value }}">

            @if($gs->is_back_in_stock && $productt->emptyStock())
            @include('front._product-details-back-in-stock')
            @endif

            @if($productt->ship != null)
            <p class="estimate-time">{{ __("Estimated Shipping Time") }}: <b>
                    {{ $productt->ship }}</b></p>
            @endif

            @if($productt->sku != null)
            <p class="p-sku">
                {{ __("SKU") }}: <span class="idno">{{ $productt->sku }}</span>
            </p>
            @endif

            {{-- <div class="social-links social-sharing a2a_kit a2a_kit_size_32">
                {{ __("Share on")}}:
                <br>
                <ul class="link-list social-links">
                    <li>
                        <a class="facebook a2a_button_facebook" href="">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                    <li>
                        <a class="twitter a2a_button_twitter" href="">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a class="whatsapp a2a_button_whatsapp" href="">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </li>
                    <li>
                        <a class="copy a2a_button_copy_link" href="">
                            <i class="fas fa-copy"></i>
                        </a>
                    </li>
                </ul>
            </div> --}}

            <script async src="https://static.addtoany.com/menu/page.js"></script>

            @if($gs->is_report)

            {{-- PRODUCT REPORT SECTION 
            @if(Auth::guard('web')->check())
            <div class="report-area">
                <a href="javascript:;" data-toggle="modal" data-target="#report-modal">
                    <i class="fas fa-flag"></i> {{ __("Report This Item") }}
                </a>
            </div>
            @else
            <div class="report-area">
                <a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg">
                    <i class="fas fa-flag"></i> {{ __("Report This Item") }}
                </a>
            </div>
            @endif
            PRODUCT REPORT SECTION ENDS --}}

            @endif
        </div>
    </div>
    <div class="py-4">
        <h3 style="text-transform: uppercase;font-weight: 300;">{{__('Product details')}}</h3>
        <div style="color:#848484;font-weight:300;font-family:'Cormorant', serif;">{!! nl2br($productt->details) !!}</div>
    </div>
</div>

<script>

    const spans = document.querySelectorAll('.boxassociatedProductSize');
    const sizes = [];

    spans.forEach(element => {
        const labels = element.querySelector('label');
        if (labels) {
        sizes.push(labels.innerText.trim());
        }
    });

    isNumber(sizes[0]);

    function isNumber(value) {
        const convertedNumber = +value;
        const divSizes = document.querySelector('.product-size');
        const spans = Array.from(divSizes.querySelectorAll('span'));
        const numberOrder = sizes.sort((a, b) => a - b);
        const sizesOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', 'XXXL', '3XL', '4XL'];

        if(!isNaN(convertedNumber)) {

            function compareSizes(a, b) {
            const sizeA = a.querySelector('label').textContent.trim();
            const sizeB = b.querySelector('label').textContent.trim();

            return numberOrder.indexOf(sizeA) - numberOrder.indexOf(sizeB);
            }

            spans.sort(compareSizes);
        }
        else {

            function compareSizes(a, b) {
            const sizeA = a.querySelector('label').textContent.trim();
            const sizeB = b.querySelector('label').textContent.trim();

            return sizesOrder.indexOf(sizeA) - sizesOrder.indexOf(sizeB);
            }

            spans.sort(compareSizes);
        }

        spans.forEach(span => {
        divSizes.removeChild(span);
        });

        spans.forEach(span => {
        divSizes.appendChild(span);
        });
    }

    const verifyStockProdChecked = () => {
        const valueStock = $("[name='associatedProductsBySize']:checked").attr('data-product-stock')?? {{$productt->stock}}

        if(valueStock > 3) {
            $('#rest_of').html(`{{ __("In Stock") }}: ${valueStock}`)
        } else {
            $('#rest_of').html(`{{ __("There are only") }} ${valueStock} !`)
        }

    }

    const sizeCode = () => {
        const sizeCode = document.querySelector('#size-code');
        const productCode = document.querySelector('[name="associatedProductsBySize"]:checked').getAttribute('data-product-code');
        sizeCode.textContent = productCode;
    }

    verifyStockProdChecked()
    sizeCode()

    $("[name='associatedProductsBySize']").change(()=> {
        verifyStockProdChecked()
        sizeCode()
    })
</script>
