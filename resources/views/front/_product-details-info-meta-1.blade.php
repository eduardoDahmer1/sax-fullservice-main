<div class="info-meta-1">
    <ul>
      
        @if($productt->emptyStock() && !$productt->associatedProductsBySize->contains(fn($product) => $product->stock  ? true : false ))
            <li class="product-outstook">
                <p>
                    <i class="icofont-close-circled"></i>
                    {{ __("Out of Stock!") }}
                </p>
            </li>
        @else
            <li class="product-isstook">
                <p>
                    @if($gs->show_stock)
                        @if(empty($productt->size) && empty($productt->color) && empty($productt->material))
                            <i class="icofont-check-circled"></i>
                            <span id="rest_of"></span>
                        @endif

                        @if(!empty($productt->color))
                            <i class="icofont-check-circled"></i>
                            <span id="rest_of"></span>
                        @endif

                        @if(!empty($productt->material))
                            <i class="icofont-check-circled"></i>
                            <span id="rest_of"></span>
                        @endif

                        @if(!empty($productt->size))
                            <i class="icofont-check-circled"></i>
                            <span id="rest_of"></span>
                        @endif
                    @endif
                </p>
            </li>
        @endif
    

        @if($gs->is_rating == 1)
            @if (count($productt->ratings) > 0)
                <li>
                    <div class="ratings">
                        <div class="empty-stars"></div>
                        <div class="full-stars" style="width:{{App\Models\Rating::ratings($productt->id)}}%">
                        </div>
                    </div>
                </li>
                <li class="review-count">
                    <p>{{count($productt->ratings)}} {{ __("Review(s)") }}</p>
                </li>
            @endif
        @endif
        
        @if($productt->product_condition != 0)
        <li>
            <div class="{{ $productt->product_condition == 2 ? 'mybadge' :
                                'mybadge1' }}">
                {{ $productt->product_condition == 2 ? 'New' : 'Used' }}
            </div>
        </li>
        @endif
    </ul>
</div>