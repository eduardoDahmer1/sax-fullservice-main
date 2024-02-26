<div class="trending">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="section-top">
                    <h2 class="section-title">
                        {{ __("Vendors") }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($productt->category->products()->byStore()
            ->where('status','=',1)
            ->where('brand_id', '=', $productt->brand_id)
            ->where('category_id', '=', $productt->category_id)
            ->where('mpn', '=', $productt->mpn)
            ->where('id','!=', $productt->id)
            ->where('user_id', '!=', 0)
            ->orderBy('price', 'ASC')
            ->get()
            as $prod)
            @include('includes.product.slider-vendor')
            @endforeach
        </div>
    </div>
</div>
