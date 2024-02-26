@php
if ($gs->switch_highlight_currency) {
$highlight = $prod->firstCurrencyPrice();
$small = $prod->showVendorPrice();
} else {
$highlight = $prod->showVendorPrice();
$small = $prod->firstCurrencyPrice();
}
@endphp

{{-- If This product belongs to vendor then apply this --}}
{{-- check If This vendor status is active --}}
<br>
<div class="col-lg-4">
    <img style="margin: 0 auto; display: flex;" width="50%"
        src="{{ $prod->user->photo ? asset('storage/images/users/'.$prod->user->photo ):asset('assets/images/noimage.png') }}" />
    <p style="color: green; font-weight: 600; text-align: center">{{ $prod->user->shop_name }}</p>
</div>
<div class="col-lg-6">
    <h4>{{ __('Address:')}} {{$prod->user->shop_address}}</h5>
        <h6>{{ __("Email") }}: {{ $prod->user->email }}</h6>
        <h6>{{ __("Phone") }}: {{ $prod->user->vendor_phone }}<h6><br>
                <a target="_blank"
                    href="{{ route('front.vendor',str_replace(' ', '-', $prod->user->shop_name)) }}"><button
                        style="margin: 0 auto; display: flex;" class="btn btn-success">{{ __("Details") }}</button></a>
</div>
<div class="col-lg-2">
    <div class="info">
        <h4 class="price">{{ $highlight }} @if ($curr->id != $scurrency->id)<small>{{ $small }}</small>@endif
        </h4>
    </div>
</div>
<div class="section-top">
</div>
