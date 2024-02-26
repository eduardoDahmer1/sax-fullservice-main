@if($productt->brand->status == 1 && $productt->brand->name !== __('Deleted'))
<div class="seller-info mt-3 mb-3">
    <div class="content">
        <div class="title">
            <a href="{{ route('front.brand', $productt->brand->slug) }}">
                <img src="{{$productt->brand->image ? asset('storage/images/brands/'.$productt->brand->image) : asset('assets/images/noimage.png') }}"
                    alt="{{$productt->brand->name}}">
        </div>
        <p class="stor-name">
            {{$productt->brand->name}}
        </p>
        </a>
    </div>
</div>
@endif
@if(!empty($productt->whole_sell_qty))
<div class="table-area wholesell-details-page">
    <h3>{{ __("Wholesell") }}</h3>
    <table class="table">
        <tr>
            <th>{{ __("Quantity") }}</th>
            <th>{{ __("Discount") }}</th>
        </tr>
        @foreach($productt->whole_sell_qty as $key => $data1)
        <tr>
            <td>{{ $productt->whole_sell_qty[$key] }}+</td>
            <td>{{ $productt->whole_sell_discount[$key] }}% {{ __("Off") }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endif
