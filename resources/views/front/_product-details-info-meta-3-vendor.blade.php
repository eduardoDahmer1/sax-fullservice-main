<!-- Marketplace Disabled && Product is not Affiliate -->
<!-- or -->
<!-- Marketplace Enabled && Product belongs to Vendor -->
@if(( !config("features.marketplace") && $productt->product_type != "affiliate")
|| (config("features.marketplace") && $productt->user->isVendor()))

@if( !$productt->emptyStock() || $productt->associatedProductsBySize->contains(fn($product) => $product->stock  ? true : false ) )
<li class="d-block count {{ $productt->type == 'Physical' ? '' : 'd-none' }}">
    <div class="qty">
        <ul>
            <li>
                <span class="qtminus">
                    <i class="icofont-minus"></i>
                </span>
            </li>
            <li>
                <span class="qttotal">1</span>
                <input type="hidden" class="max_quantity" id="max_quantity" value="{{ $productt->max_quantity }}">
            </li>
            <li>
                <span class="qtplus">
                    <i class="icofont-plus"></i>
                </span>
            </li>
        </ul>
    </div>
</li>
@endif

@endif
