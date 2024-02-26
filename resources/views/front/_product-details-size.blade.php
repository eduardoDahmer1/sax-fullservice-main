<div class="product-size">
    <p class="title">{{ __("Size") }} :</p>
    <ul class="siz-list">
        @php
        $is_selected = false;
        @endphp
        @foreach($productt->size as $key => $data1)
        <li class="{{ (!$is_selected && (int)$productt->size_qty[$key] > 0) ? 'active' : '' }}">
            <span class="box {{ ($productt->size_qty[$key] == 0) ? 'disabled' : '' }}">
                {{$data1 }}
                <input type="hidden" class="size" value="{{ $data1 }}">
                <input type="hidden" class="size_qty" value="{{ $productt->size_qty[$key] }}">
                <input type="hidden" class="size_key" value="{{$key}}">
                <input type="hidden" class="size_price" value="{{ round($productt->size_price[$key] *
                                $product_curr->value * (1+($gs->product_percent / 100)),2) }}">
            </span>
        </li>
        @php
        if(!$is_selected && $productt->size_qty[$key] > 0){
        $size_qty = $productt->size_qty[$key];
        $is_selected = true;
        }
        @endphp
        <input type="hidden" id="stock" value="{{ isset($productt->size_qty[$key]) ? $productt->size_qty[$key] : '' }}">
        @endforeach
        <li>
    </ul>
</div>
