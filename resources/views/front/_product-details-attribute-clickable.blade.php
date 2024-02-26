<div class="product-attributes my-3">
    <div class="row">
        @foreach($attrArr as $attrKey => $attrVal)
        @if(array_key_exists("details_status",$attrVal) &&
        $attrVal['details_status'] == 1)
        @if ($attr_search =
        App\Models\Attribute::where('input_name',$attrKey)->first())
        <div class="col-lg-6 list-attr">
            <div class="form-group mb-2">
                <p>
                    {{ App\Models\Attribute::where('input_name',
                    $attrKey)->first()->name }}:
                </p>
                <div class="d-flex flex-wrap">
                    @if($gs->is_attr_cards)
                    @foreach ($attrVal['values'] as $optionKey => $optionVal)
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card border-dark mb-3">
                                <div class="card-header">
                                    <input type="radio" id="{{$attrKey}}{{ $optionKey }}" name="{{ $attrKey }}"
                                        class="custom-control-input product-attr" data-key="{{ $attrKey }}"
                                        data-price="{{$attrVal['prices'][$optionKey] * $product_curr->value * (1+($gs->product_percent / 100))}}"
                                        value="{{ $optionKey }}" {{ $loop->first ? "checked" : "" }}>
                                    @if($loop->count > 1)
                                    <label class="custom-control-label" for="{{$attrKey}}{{ $optionKey }}">
                                        {{App\Models\AttributeOption::find($optionVal)->name}}
                                        @if(!empty($attrVal['prices'][$optionKey]) && $attr_search->show_price == 1)
                                        {{$product_curr->sign}}
                                        {{number_format(
                                        $attrVal['prices'][$optionKey] *
                                        $product_curr->value *
                                        (1+($gs->product_percent / 100)),
                                        $product_curr->decimal_digits,
                                        $product_curr->decimal_separator,
                                        $product_curr->thousands_separator)}}
                                        @endif
                                    </label>
                                    @else
                                    <div class="attribute-normal">
                                        -
                                        {{App\Models\AttributeOption::find($optionVal)->name}}
                                    </div>
                                    @endif
                                </div>
                                <div class="card-body text-dark">
                                    <small>{{
                                        App\Models\AttributeOption::find($optionVal)->description
                                        }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    @foreach ($attrVal['values'] as $optionKey => $optionVal)
                    @if (App\Models\AttributeOption::where('id',
                    $optionVal)->first())
                    <div class="custom-control custom-radio">
                        <input type="hidden" class="keys" value="">
                        <input type="hidden" class="values" value="">
                        <input type="radio" id="{{$attrKey}}{{ $optionKey }}" name="{{ $attrKey }}"
                            class="custom-control-input product-attr" data-key="{{ $attrKey }}"
                            data-price="{{ $attrVal['prices'][$optionKey] * $product_curr->value * (1+($gs->product_percent / 100))}}"
                            value="{{ $optionKey }}" {{$loop->first ? "checked" : "" }}>
                        @if($loop->count > 1)
                        <label class="custom-control-label" for="{{$attrKey}}{{ $optionKey }}">
                            {{App\Models\AttributeOption::find($optionVal)->name}}
                            @if (!empty($attrVal['prices'][$optionKey]) &&
                            $attr_search->show_price == 1)
                            @if ($attrVal['prices'][$optionKey] >= 0)
                            +
                            @endif
                            {{$product_curr->sign}}
                            {{number_format(
                            $attrVal['prices'][$optionKey] *
                            $product_curr->value * (1+($gs->product_percent /
                            100)),
                            $product_curr->decimal_digits,
                            $product_curr->decimal_separator,
                            $product_curr->thousands_separator)}}
                            @endif
                        </label>
                        @else
                        <div class="attribute-normal">
                            {{App\Models\AttributeOption::find($optionVal)->name}}
                        </div>
                        @endif
                    </div>
                    @endif
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endif
        @endforeach
    </div>
</div>
