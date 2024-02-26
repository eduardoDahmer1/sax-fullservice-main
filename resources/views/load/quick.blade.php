@php
if ($gs->switch_highlight_currency) {
$highlight = $product->firstCurrencyPrice();
$small = $product->showPrice();
} else {
$highlight = $product->showPrice();
$small = $product->firstCurrencyPrice();
}
@endphp

<div id="quick-details" class="row product-details-page py-0">
    <div class="col-lg-5">
        <div class="xzoom-container">
            <img class="quick-zoom" id="xzoom-magnific1"
                src="{{filter_var($product->photo, FILTER_VALIDATE_URL) ?$product->photo:asset('storage/images/products/'.$product->photo)}}"
                xoriginal="{{filter_var($product->photo, FILTER_VALIDATE_URL) ?$product->photo:asset('storage/images/products/'.$product->photo)}}" />
        </div>
        @if(!empty($product->whole_sell_qty))
        <div class="table-area wholesell-details-page">
            <h3>{{ __("Wholesell") }}</h3>
            <table class="table">
                <tr>
                    <th>{{ __("Quantity") }}</th>
                    <th>{{ __("Discount") }}</th>
                </tr>
                @foreach($product->whole_sell_qty as $key => $data1)
                <tr>
                    <td>{{ $product->whole_sell_qty[$key] }}+</td>
                    <td>{{ $product->whole_sell_discount[$key] }}% {{ __("Off") }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif
    </div>
    <div class="col-lg-7">
        <div class="right-area">
            <div class="product-info">
                <h4 class="product-name"><a target="_blank" href="{{ route('front.product',$product->slug) }}">{{
                        $product->name }}</a></h4>
                @if( $product->ref_code != null && ($admstore->reference_code == 1) )
                <h4><span class="badge badge-primary" style="background-color: {{$admstore->ref_color}}"> {{
                        __('Reference Code') }}: {{ $product->ref_code }}</span></h4>
                @endif
                <div class="info-meta-1">
                    <ul>
                        @if($product->type == 'Physical')
                        @if($product->emptyStock())
                        <li class="product-outstook">
                            <p>
                                <i class="icofont-close-circled"></i>
                                {{ __("Out of Stock!") }}
                            </p>
                        </li>
                        @else
                        <li class="product-isstook">
                            <p>
                                <i class="icofont-check-circled"></i>
                                {{ $gs->show_stock == 0 ? '' : $product->stock }} {{ __("In Stock") }}
                            </p>
                        </li>
                        @endif
                        @endif
                        @if($gs->is_rating == 1)
                        <li>
                            <div class="ratings">
                                <div class="empty-stars"></div>
                                <div class="full-stars" style="width:{{App\Models\Rating::ratings($product->id)}}%">
                                </div>
                            </div>
                        </li>
                        <li class="review-count">
                            <p>{{count($product->ratings)}} {{ __("Review(s)") }}</p>
                        </li>
                        @endif
                        @if($product->product_condition != 0)
                        <li>
                            <div class="{{ $product->product_condition == 2 ? 'mybadge' : 'mybadge1' }}">
                                {{ $product->product_condition == 2 ? 'New' : 'Used' }}
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="info-meta-2">
                    <ul>
                        @if($product->type == 'License')
                        @if($product->platform != null)
                        <li>
                            <p>{{ __("Platform") }}: <b>{{ $product->platform }}</b></p>
                        </li>
                        @endif
                        @if($product->region != null)
                        <li>
                            <p>{{ __("Region") }}: <b>{{ $product->region }}</b></p>
                        </li>
                        @endif
                        @if($product->licence_type != null)
                        <li>
                            <p>{{ __("License Type") }}: <b>{{ $product->licence_type }}</b></p>
                        </li>
                        @endif
                        @endif
                    </ul>
                </div>
                @if($product->show_price)
                    <div class="product-price">
                        @if($gs->show_product_prices)
                            <p class="title">{{ __("Price") }} :</p>
                        @endif
                        @if($product->promotion_price > 0 && $product->promotion_price < $product->price)
                            <span style=" font-weight: 400; text-decoration: line-through; color: #bababa;">{{ $highlight }}</span>
                            <p class="price"><span id="msizeprice">{{$curr->sign}}{{ $product->promotion_price }}</span>
                                @php
                                    $size_price_value = $product->vendorPrice() * $curr->value;
                                    $previous_price_value = $product->promotion_price * $curr->value * (1+($gs->product_percent / 100));
                                @endphp
                                <small>
                                    <del id="mpreviousprice"
                                        style="display:{{($size_price_value >= $previous_price_value)? 'none' : '' }};">{{$product->showPreviousPrice()}}
                                    </del>
                                </small>
                                <input type="hidden" id="mprevious_price_value" value="{{ round($previous_price_value,2) }}">
                                @if($curr->id != $first_curr->id)
                                    <small><span id="moriginalprice">{{ $small }}</span></small>
                                @endif
                            </p>
                        @else
                            <p class="price"><span id="msizeprice">{{ $highlight }}</span>
                                @php
                                    $size_price_value = $product->vendorPrice() * $curr->value;
                                    $previous_price_value = $product->promotion_price * $curr->value * (1+($gs->product_percent / 100));
                                @endphp
                                <small>
                                    <del id="mpreviousprice"
                                        style="display:{{($size_price_value >= $previous_price_value)? 'none' : '' }};">{{$product->showPreviousPrice()}}
                                    </del>
                                </small>
                                <input type="hidden" id="mprevious_price_value" value="{{ round($previous_price_value,2) }}">
                                @if($curr->id != $first_curr->id)
                                    <small><span id="moriginalprice">{{ $small }}</span></small>
                                @endif
                            </p>
                        @endif
                            @if($product->youtube != null)
                                <a href="{{ $product->youtube }}" class="video-play-btn mfp-iframe">
                                    <i class="fas fa-play"></i>
                                </a>
                            @endif
                    </div>
                @endif
                @if(!empty($product->size))
                <div class="mproduct-size">
                    <p class="title">{{ __("Size") }} :</p>
                    <ul class="siz-list">
                        @php
                        $is_first = true;
                        @endphp
                        @foreach($product->size as $key => $data1)
                        <li class="{{ $is_first ? 'active' : '' }}">
                            <span class="box">{{ $data1 }}
                                <input type="hidden" class="msize" value="{{ $data1 }}">
                                <input type="hidden" class="msize_qty" value="{{ $product->size_qty[$key] }}">
                                <input type="hidden" class="msize_key" value="{{$key}}">
                                <input type="hidden" class="msize_price"
                                    value="{{ round($product->size_price[$key] * $curr->value * (1+($gs->product_percent / 100)),2) }}">
                            </span>
                        </li>
                        @php
                        $is_first = false;
                        @endphp
                        @endforeach
                        <li>
                    </ul>
                </div>
                @endif
                @if(!empty($product->color))
                <div class="mproduct-color">
                    <p class="title">{{ __("Color") }} :</p>
                    <ul class="color-list">
                        @php
                        $is_first = true;
                        @endphp
                        @foreach($product->color as $key => $data1)
                        <li class="{{ $is_first ? 'active' : '' }}">
                            <span class="box" data-color="{{ $product->color[$key] }}"
                                style="background-color: {{ $product->color[$key] }}"></span>
                        </li>
                        @php
                        $is_first = false;
                        @endphp
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(!empty($product->size))
                <input type="hidden" class="product-stock" id="stock" value="{{ $product->size_qty[0] }}">
                @else
                @php
                $stck = (string)$product->stock;
                @endphp
                @if($stck != null)
                <input type="hidden" class="product-stock" value="{{ $stck }}">
                @elseif($product->type != 'Physical')
                <input type="hidden" class="product-stock" value="0">
                @else
                <input type="hidden" class="product-stock" value="">
                @endif
                @endif
                <input type="hidden" id="mproduct_price" value="{{ round($product->vendorPrice() * $curr->value,2) }}">
                <input type="hidden" id="mproduct_id" value="{{ $product->id }}">
                <input type="hidden" id="mcurr_pos" value="{{ $gs->currency_format }}">
                <input type="hidden" id="mdec_sep" value="{{ $curr->decimal_separator }}">
                <input type="hidden" id="mtho_sep" value="{{ $curr->thousands_separator }}">
                <input type="hidden" id="mdec_dig" value="{{ $curr->decimal_digits }}">
                <input type="hidden" id="mdec_sep2" value="{{ $first_curr->decimal_separator }}">
                <input type="hidden" id="mtho_sep2" value="{{ $first_curr->thousands_separator }}">
                <input type="hidden" id="mdec_dig2" value="{{ $first_curr->decimal_digits }}">
                <input type="hidden" id="mcurr_sign" value="{{ $curr->sign }}">
                <input type="hidden" id="mfirst_sign" value="{{ $first_curr->sign }}">
                <input type="hidden" id="curr_value" value="{{ $curr->value }}">
                <div class="info-meta-3">
                    <ul class="meta-list">
                        @if (!empty($product->attributes))
                        @php
                        $attrArr = json_decode($product->attributes, true);
                        // dd($attrArr);
                        @endphp
                        @endif
                        @if (!empty($attrArr))
                        <div class="product-attributes my-4">
                            <div class="row">
                                @foreach ($attrArr as $attrKey => $attrVal)
                                @if (array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1)
                                @if ($attr_search = App\Models\Attribute::where('input_name', $attrKey)->first())
                                <div class="col-lg-6">
                                    <div class="form-group mb-2">
                                        <strong for="" class="text-capitalize">{{
                                            App\Models\Attribute::where('input_name', $attrKey)->first()->name }}
                                            :</strong>
                                        <div class="">
                                            @foreach ($attrVal['values'] as $optionKey => $optionVal)
                                            @if (App\Models\AttributeOption::where('id', $optionVal)->first())
                                            <div class="custom-control custom-radio">
                                                <input type="hidden" class="keys" value="">
                                                <input type="hidden" class="values" value="">

                                                <input type="radio" id="{{$attrKey}}{{ $optionKey }}"
                                                    name="{{ $attrKey }}" class="custom-control-input mproduct-attr"
                                                    data-key="{{ $attrKey }}"
                                                    data-price="{{ $attrVal['prices'][$optionKey] * $curr->value * (1+($gs->product_percent / 100)) }}"
                                                    value="{{ $optionKey }}" {{ $loop->first ? 'checked' : '' }}>
                                                @if($loop->count > 1)
                                                <label class="custom-control-label"
                                                    for="{{$attrKey}}{{ $optionKey }}">{{
                                                    App\Models\AttributeOption::find($optionVal)->name }}
                                                    @if (!empty($attrVal['prices'][$optionKey]) &&
                                                    $attr_search->show_price == 1)
                                                    @if ($attrVal['prices'][$optionKey] >= 0)
                                                    +
                                                    @endif
                                                    {{$curr->sign}} {{number_format($attrVal['prices'][$optionKey] *
                                                    $curr->value * (1+($gs->product_percent / 100)),
                                                    $curr->decimal_digits,
                                                    $curr->decimal_separator,$curr->thousands_separator) }}
                                                    @endif
                                                </label>
                                                @else
                                                <div style="margin-left: -1.5rem">
                                                    - {{App\Models\AttributeOption::find($optionVal)->name}}
                                                </div>
                                                @endif
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if($gs->is_cart)
                        @if($product->product_type == "affiliate")
                        <li class="addtocart">
                            <a href="{{ route('affiliate.product', $product->slug) }}" target="_blank">
                                <i class="icofont-shopping-cart"></i> {{ __("Buy Now") }}</a>
                        </li>
                        @else
                        @if($product->emptyStock())
                        <li class="addtocart">
                            <a href="javascript:;" class="cart-out-of-stock">
                                <i class="icofont-close-circled"></i>
                                {{ __("Out of Stock!") }}</a>
                        </li>
                        @else
                        <li class="addtocart">
                            <a href="{{ route('front.product', $product->slug)}}">
                                <i class="icofont-list"></i>{{ __("Details") }}</a>
                        </li>
                        @endif
                        @endif
                        @endif
                        @if(Auth::guard('web')->check())
                        <li class="favorite">
                            <a href="javascript:;" class="add-to-wish"
                                data-href="{{ route('user-wishlist-add',$product->id) }}"><i
                                    class="icofont-ui-love-add"></i></a>
                        </li>
                        @else
                        <li class="favorite">
                            <a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg"><i
                                    class="icofont-ui-love-add"></i></a>
                        </li>
                        @endif
                        <li class="compare">
                            <a href="javascript:;" class="add-to-compare"
                                data-href="{{ route('product.compare.add',$product->id) }}"><i
                                    class="icofont-exchange"></i></a>
                        </li>
                    </ul>
                    @if($product->ship != null)
                    <p class="estimate-time mt-2">{{ __("Estimated Shipping Time") }}: <b> {{ $product->ship }}</b></p>
                    @endif
                    @if( $product->sku != null )
                    <p class="p-sku mt-2">
                        {{ __("Product SKU") }}: <span class="idno">{{ $product->sku }}</span>
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    @media (min-width: 1200px) {
        .xzoom-preview {
            width: 450px !important;
            height: 390px !important;
            background: white;
            position: inherit;
            z-index: 99999;
            @if($slocale->rtl=="1") right: 900px;
            @endif
        }
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
    var w = window.innerWidth;
    if( w > 575)
    {
        $('.quick-zoom, .quick-zoom-gallery').xzoom({tint: '#006699', Xoffset: 15});
        //Integration with hammer.js
        var isTouchSupported = 'ontouchstart' in window;
        if (isTouchSupported) {
            //If touch device
            $('.quick-zoom').each(function(){
                var xzoom = $(this).data('xzoom');
                xzoom.eventunbind();
            });
        $('.quick-zoom').each(function() {
            var xzoom = $(this).data('xzoom');
            $(this).hammer().on("tap", function(event) {
                event.pageX = event.gesture.center.pageX;
                event.pageY = event.gesture.center.pageY;
                var s = 1, ls;
                xzoom.eventmove = function(element) {
                    element.hammer().on('drag', function(event) {
                        event.pageX = event.gesture.center.pageX;
                        event.pageY = event.gesture.center.pageY;
                        xzoom.movezoom(event);
                        event.gesture.preventDefault();
                    });
                }
                var counter = 0;
                xzoom.eventclick = function(element) {
                    element.hammer().on('tap', function() {
                        counter++;
                        if (counter == 1) setTimeout(openmagnific,300);
                        event.gesture.preventDefault();
                    });
                }
                function openmagnific() {
                    if (counter == 2) {
                        xzoom.closezoom();
                        var gallery = xzoom.gallery().cgallery;
                        var i, images = new Array();
                        for (i in gallery) {
                            images[i] = {src: gallery[i]};
                        }
                        $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
                    } else {
                        xzoom.closezoom();
                    }
                    counter = 0;
                }
                xzoom.openzoom(event);
            });
        });
        } else {
            //If not touch device
            //Integration with fancybox plugin
            $('#xzoom-fancy').bind('click', function(event) {
                var xzoom = $(this).data('xzoom');
                xzoom.closezoom();
                $.fancybox.open(xzoom.gallery().cgallery, {padding: 0, helpers: {overlay: {locked: false}}});
                event.preventDefault();
            });
            //Integration with magnific popup plugin
            $('#xzoom-magnific1').bind('click', function(event) {
                var xzoom = $(this).data('xzoom');
                xzoom.closezoom();
                var gallery = xzoom.gallery().cgallery;
                var i, images = new Array();
                for (i in gallery) {
                    images[i] = {src: gallery[i]};
                }
                $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
                $(document).off('focusin' );
                event.preventDefault();
            });
        }
    }
    });
function formatMoney(number, decPlaces, decSep, thouSep) {
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSep = typeof decSep === "undefined" ? "." : decSep;
    thouSep = typeof thouSep === "undefined" ? "," : thouSep;
    var sign = number < 0 ? "-" : "";
    var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
    var j = (j = i.length) > 3 ? j % 3 : 0;
    return sign +
        (j ? i.substr(0, j) + thouSep : "") +
        i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
        (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
    }
//   magnific popup
$('.video-play-btn').magnificPopup({
type: 'video'
});
var sizes = "";
var size_qty = "";
var size_price = "";
var size_key = "";
var colors = "";
var mtotal = "";
var mstock = $('.product-stock').val();
var keys = "";
var values = "";
var prices = "";
$('.mproduct-attr').on('change',function(){
var total;
total = mgetAmount()+mgetSizePrice();
var previous_price = parseFloat($('#mprevious_price_value').val());
if(total >= previous_price){
  $('#mpreviousprice').hide();
}else{
  $('#mpreviousprice').show();
}
total = total.toFixed(2);
var pos = $('#mcurr_pos').val();
var sign = $('#mcurr_sign').val();
var first_sign=$("#mfirst_sign").val();
var curr_value=$("#curr_value").val();
curr_value = total/curr_value;
curr_value = curr_value.toFixed(2);
var dec_sep = $("#mdec_sep").val();
var tho_sep = $("#mtho_sep").val();
var dec_dig = $("#mdec_dig").val();
var dec_sep2 = $("#mdec_sep2").val();
var tho_sep2 = $("#mtho_sep2").val();
var dec_dig2 = $("#mdec_dig2").val();
total = formatMoney(total,dec_dig,dec_sep,tho_sep);
curr_value = formatMoney(curr_value,dec_dig2,dec_sep2,tho_sep2);
if(pos == '0')
{
$('#msizeprice').html(sign+total);
$('#moriginalprice').html(first_sign+curr_value);
}
else {
$('#msizeprice').html(total+sign);
$('#moriginalprice').html(curr_value+first_sign);
}
});
function mgetSizePrice()
{
var total = 0;
if($('.mproduct-size .siz-list li').length > 0)
{
total = parseFloat($('.mproduct-size .siz-list li.active').find('.msize_price').val());
}
return total;
}
function mgetAmount()
{
var total = 0;
var value = parseFloat($('#mproduct_price').val());
var datas = $(".mproduct-attr:checked").map(function() {
return $(this).data('price');
}).get();
var data;
for (data in datas) {
total += parseFloat(datas[data]);
}
total += value;
return total;
}
// Product Details Product Size Active Js Code
$('.mproduct-size .siz-list .box').on('click', function () {
$('.modal-total').html('1');
var parent = $(this).parent();
size_qty = $(this).find('.msize_qty').val();
size_price = $(this).find('.msize_price').val();
size_key = $(this).find('.msize_key').val();
sizes = $(this).find('.msize').val();
$('.mproduct-size .siz-list li').removeClass('active');
parent.addClass('active');
total = mgetAmount()+parseFloat(size_price);
var previous_price = parseFloat($('#mprevious_price_value').val());
if(total >= previous_price){
  $('#mpreviousprice').hide();
}else{
  $('#mpreviousprice').show();
}
stock = size_qty;
total = total.toFixed(2);
var curr_value=$("#curr_value").val();
curr_value = total/curr_value;
curr_value=curr_value.toFixed(2);
var pos = $('#mcurr_pos').val();
var sign = $('#mcurr_sign').val();
var first_sign=$("#mfirst_sign").val();
var dec_sep = $("#mdec_sep").val();
var tho_sep = $("#mtho_sep").val();
var dec_dig = $("#mdec_dig").val();
var dec_sep2 = $("#mdec_sep2").val();
var tho_sep2 = $("#mtho_sep2").val();
var dec_dig2 = $("#mdec_dig2").val();
total = formatMoney(total,dec_dig,dec_sep,tho_sep);
curr_value = formatMoney(curr_value,dec_dig2,dec_sep2,tho_sep2);
if(pos == '0')
{
$('#msizeprice').html(sign+total);
$('#moriginalprice').html(first_sign+curr_value);
}
else {
$('#msizeprice').html(total+sign);
$('#moriginalprice').html(curr_value+first_sign);
}
});
// Product Details Product Color Active Js Code
$('.mproduct-color .color-list .box').on('click', function () {
colors = $(this).data('color');
var parent = $(this).parent();
$('.mproduct-color .color-list li').removeClass('active');
parent.addClass('active');
});
$('.modal-minus').on('click', function () {
var el = $(this);
var $tselector = el.parent().parent().find('.modal-total');
total = $($tselector).text();
if (total > 1) {
  total--;
}
$($tselector).text(total);
});
$('.modal-plus').on('click', function () {
var el = $(this);
var $tselector = el.parent().parent().find('.modal-total');
total = $($tselector).text();
if(mstock != "")
{
  var stk = parseInt(mstock);
  if(total < stk)
  {
      total++;
      $($tselector).text(total);
  }
}
else {
  total++;
}
$($tselector).text(total);
});
$("#maddcrt").on("click", function(){
var qty = $('.modal-total').html();
var pid = $(this).parent().parent().parent().parent().find("#mproduct_id").val();
if($('.mproduct-attr').length > 0)
{
values = $(".mproduct-attr:checked").map(function() {
return $(this).val();
}).get();
keys = $(".mproduct-attr:checked").map(function() {
return $(this).data('key');
}).get();
prices = $(".mproduct-attr:checked").map(function() {
return $(this).data('price');
}).get();
}
$.ajax({
  type: "GET",
  url:mainurl+"/addnumcart",
  data:{id:pid,qty:qty,size:sizes,color:colors,size_qty:size_qty,size_price:size_price,size_key:size_key,keys:keys,values:values,prices:prices},
  success:function(data){
      if(data.digital) {
          toastr.error(data['digital']);
      }
      else if(data.out_stock) {
          toastr.error(data['out_stock']);
      }
      else {
          $("#cart-count").html(data[0]);
          $("#cart-items").load(mainurl+'/carts/view');
          toastr.success( __("Successfully Added To Cart"));
      }
  }
});
});
$(document).on("click", "#mqaddcrt" , function(){
      var qty = $('.modal-total').html();
      var pid = $(this).parent().parent().parent().parent().find("#mproduct_id").val();
if($('.mproduct-attr').length > 0)
{
values = $(".mproduct-attr:checked").map(function() {
return $(this).val();
}).get();
keys = $(".mproduct-attr:checked").map(function() {
return $(this).data('key');
}).get();
prices = $(".mproduct-attr:checked").map(function() {
return $(this).data('price');
}).get();
}
  window.location = mainurl+"/addtonumcart?id="+pid+"&qty="+qty+"&size="+sizes+"&color="+colors.substring(1, colors.length)+"&size_qty="+size_qty+"&size_price="+size_price+"&size_key="+size_key+"&keys="+keys+"&values="+values+"&prices="+prices;
     });
</script>
