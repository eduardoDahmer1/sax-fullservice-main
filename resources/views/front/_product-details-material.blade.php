<div class="product-attributes my-4">
    <strong for="" class="text-capitalize">
        {{ __("Material") }}:
    </strong>
    <div class="form-group mb-2">
        <select id="select-materials" class="form-control">
            @foreach($productt->material as $key => $material)
            <option value="{{ $key }}" {{ $productt->material_qty[$key] == 0 ?
                "disabled" : "" }}
                @if($productt->material_qty[$key] > 0)
                id="material-option"
                data-material-qty="{{ $productt->material_qty[$key]}}"
                data-material-name="{{ $productt->material[$key]}}"
                data-material-price="{{$productt->material_price[$key]}}"
                data-material-key="{{$key}}"
                @endif
                >
                {{ $material }}
            </option>
            <input type="hidden" id="stock"
                value="{{ isset($productt->material_qty[$key]) ? $productt->material_qty[$key] : '' }}">
            @endforeach
        </select>
        <input type="hidden" class="material" id="material_product" value="">
        <input type="hidden" class="material_qty" id="material_qty_product" value="">
        <input type="hidden" class="material_key" id="material_key_product" value="">
        <input type="hidden" class="material_price" id="material_price_product" value="">
    </div>
</div>
