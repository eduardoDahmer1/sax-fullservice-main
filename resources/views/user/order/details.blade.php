@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')
    <!-- User Dashbord Area Start -->
    <style>
        .pix {
            display: flex;
            font-size: 16px !important;
            font-weight: bold;
            align-items: center;
        }
    </style>
    <section class="user-dashbord">
        <div class="container">
            <div class="row">
                @include('includes.user-dashboard-sidebar')
                <div class="col-lg-8">
                    <div class="user-profile-details">
                        <div class="order-details">

                            <div class="process-steps-area">
                                @include('includes.order-process')

                            </div>


                            <div class="header-area">
                                <h4 class="title">
                                    {{ __('My Order Details') }}
                                </h4>
                            </div>
                            <div class="view-order-page">
                                <h3 class="order-code">{{ __('Order#') }} {{ $order->order_number }}
                                    [{{ __($order->status) }}]
                                </h3>
                                <div class="print-order text-right">
                                    <a href="{{ route('user-order-print', $order->id) }}" target="_blank"
                                        class="print-order-btn">
                                        <i class="fa fa-print"></i> {{ __('Print') }}
                                    </a>
                                </div>
                                <p class="order-date">{{ __('Order Date') }}
                                    {{ date('d-M-Y', strtotime($order->created_at)) }}
                                </p>

                                @if ($order->dp == 1)

                                    <div class="billing-add-area">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>{{ __('Billing Address') }}</h5>
                                                <address>
                                                    {{ __('Name:') }} {{ $order->customer_name }}<br>
                                                    {{ __('Email:') }} {{ $order->customer_email }}<br>
                                                    {{ $customer_doc_str }}: {{ $order->customer_document }}<br>
                                                    {{ __('Phone:') }} {{ $order->customer_phone }}<br>
                                                    {{ __('Address:') }} {{ $order->customer_address }}<br>
                                                    {{ $order->customer_address_number }}-{{ $order->customer_complement }}
                                                    {{ __('District:') }} {{ $order->customer_district }}<br>
                                                    @if ($order->order_note != null)
                                                        {{ __('Order Note') }}: {{ $order->order_note }}<br>
                                                    @endif
                                                    {{ $order->customer_city }} - {{ $order->customer_state }}<br>
                                                    {{ $order->customer_country }}<br>
                                                    {{ $order->customer_zip }}
                                                </address>
                                            </div>
                                            <div class="col-md-6">
                                                <h5>{{ __('Payment Information') }}</h5>

                                                <p>{{ __('Payment Status') }}:
                                                    {!! $order->payment_status == 'Pending'
                                                        ? "<span
                                                                                                    class='badge badge-danger'>" .
                                                            __('Unpaid') .
                                                            '</span>'
                                                        : "<span
                                                                                                    class='badge badge-success'>" .
                                                            __('Paid') .
                                                            '</span>' !!}
                                                </p>

                                                <p>{{ __('Paid Amount:') }}
                                                    {{ $order->currency_sign }}{{ number_format(
                                                        $order->pay_amount * $order->currency_value,
                                                        $order_curr->decimal_digits,
                                                        $order_curr->decimal_separator,
                                                        $order_curr->thousands_separator,
                                                    ) }}
                                                </p>
                                                <p>{{ __('Payment Method:') }} {{ $order->method }}</p>

                                                @if ($order->method != 'Cash On Delivery')
                                                    @if ($order->method == 'Stripe')
                                                        {{ $order->method }} {{ __('Charge ID:') }} <p>
                                                            {{ $order->charge_id }}</p>
                                                    @endif
                                                    {{ $order->method }} {{ __('Transaction ID:') }} <p id="ttn">
                                                        {{ $order->txnid }}</p>

                                                    <form id="tform">
                                                        <input style="display: none; width: 100%;" type="text"
                                                            id="tin"
                                                            placeholder="{{ __('Enter Transaction ID & Press Enter') }}"
                                                            required="" class="mb-3">
                                                        <input type="hidden" id="oid" value="{{ $order->id }}">

                                                        <button
                                                            style="display: none; padding: 5px 15px; height: auto; width: auto; line-height: unset;"
                                                            id="tbtn" type="submit"
                                                            class="mybtn1">{{ __('Submit') }}</button>

                                                        <a style="display: none; cursor: pointer;  padding: 5px 15px; height: auto; width: auto; line-height: unset;"
                                                            id="tc" class="mybtn1">{{ __('Cancel') }}</a>

                                                        {{-- Change 1 --}}
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="shipping-add-area">
                                        <div class="row">
                                            <div class="col-md-6">
                                                @if ($order->shipping == 'shipto')
                                                    <h5>{{ __('Shipping Address') }}</h5>
                                                    <address>
                                                        {{ __('Name:') }}
                                                        {{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name }}<br>
                                                        {{ __('Phone:') }}
                                                        {{ $order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone }}<br>
                                                        {{ __('Address:') }}
                                                        {{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}<br>
                                                        {{ $order->shipping_address_number == null ? $order->customer_address_number : $order->shipping_address_number }}
                                                        <br>
                                                        {{ $order->shipping_complement == null ? $order->customer_complement : $order->shipping_complement }}<br>
                                                        {{ $order->shipping_district == null ? $order->customer_district : $order->shipping_district }}<br>
                                                        {{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}
                                                        -
                                                        {{ $order->shipping_state == null ? $order->customer_state : $order->shipping_state }}<br>
                                                        {{ $order->shipping_country == null ? $order->customer_country : $order->shipping_country }}<br>
                                                        {{ $order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip }}
                                                    </address>
                                                @else
                                                    <h5>{{ __('PickUp Location') }}</h5>
                                                    <address>
                                                        {{ __('Address:') }} {{ $order->pickup_location }}<br>
                                                    </address>
                                                @endif

                                            </div>
                                            <div class="col-md-6">
                                                <h5>{{ __('Shipping Method') }}</h5>
                                                @if ($order->shipping == 'shipto')
                                                    <p>{{ __('Ship To Address') }}</p>
                                                @else
                                                    <p>{{ __('Pick Up') }}</p>
                                                @endif
                                                <p>

                                                    {{ $order->shipping_type }}:
                                                    {{ $order->currency_sign }}{{ number_format(
                                                        $order->shipping_cost * $order->currency_value,
                                                        $order_curr->decimal_digits,
                                                        $order_curr->decimal_separator,
                                                        $order_curr->thousands_separator,
                                                    ) }}<br>
                                                    @if ($order->puntoentrega != null)
                                                        {{ __('Delivery Point:') }} {{ $order->puntoentrega }}<br>
                                                    @endif
                                                    {{ $order->packing_type }}:
                                                    {{ $order->currency_sign }}{{ number_format(
                                                        $order->packing_cost * $order->currency_value,
                                                        $order_curr->decimal_digits,
                                                        $order_curr->decimal_separator,
                                                        $order_curr->thousands_separator,
                                                    ) }}<br>
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="billing-add-area">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>{{ __('Billing Address') }}</h5>
                                                <address>

                                                    {{ __('Name:') }} {{ $order->customer_name }}<br>
                                                    {{ __('Email:') }} {{ $order->customer_email }}<br>
                                                    {{ $customer_doc_str }}: {{ $order->customer_document }}<br>
                                                    {{ __('Phone:') }} {{ $order->customer_phone }}<br>
                                                    {{ __('Address:') }} {{ $order->customer_address }}<br>
                                                    {{ __('Number:') }} {{ $order->customer_address_number }}<br>
                                                    {{ __('Complement:') }} {{ $order->customer_complement }} <br>
                                                    {{ __('District:') }} {{ $order->customer_district }}<br>
                                                    {{ $order->customer_city }} - {{ $order->customer_state }}<br>
                                                    {{ $order->customer_country }}<br>
                                                    {{ $order->customer_zip }}
                                                    @if ($order->order_note != null)
                                                        {{ __('Order Note') }}: {{ $order->order_note }}<br>
                                                    @endif
                                                </address>
                                            </div>
                                            <div class="col-md-6">
                                                <h5>{{ __('Payment Information') }}</h5>

                                                <p>{{ __('Payment Status') }}
                                                    {!! $order->payment_status == 'Pending'
                                                        ? "<span
                                                                                                    class='badge badge-danger'>" .
                                                            __('Unpaid') .
                                                            '</span>'
                                                        : "<span
                                                                                                    class='badge badge-success'>" .
                                                            __('Paid') .
                                                            '</span>' !!}
                                                </p>

                                                <p>
                                                    @if ($order->tax != 0)
                                                        {{ __('Tax:') }} {{ $order->tax . '%' }}<br>
                                                    @endif
                                                    @if ($order->coupon_discount != 0)
                                                        {{ __('Discount:') }}
                                                        {{ $order->currency_sign }}{{ number_format(
                                                            $order->coupon_discount * $order->currency_value,
                                                            $order_curr->decimal_digits,
                                                            $order_curr->decimal_separator,
                                                            $order_curr->thousands_separator,
                                                        ) }}<br>
                                                    @endif
                                                    {{ __('Paid Amount:') }}
                                                    {{ $order->currency_sign }}{{ number_format(
                                                        $order->pay_amount * $order->currency_value,
                                                        $order_curr->decimal_digits,
                                                        $order_curr->decimal_separator,
                                                        $order_curr->thousands_separator,
                                                    ) }}<br>
                                                    @if ($order->pay42_exchange_rate)
                                                        <p style="font-size: 20px">
                                                            {{ $first_curr->sign . ' ' . __('Total to be paid:') }}
                                                            {{ $first_curr->sign }}{{ number_format(
                                                                $order->pay42_total,
                                                                $first_curr->decimal_digits,
                                                                $first_curr->decimal_separator,
                                                                $first_curr->thousands_separator,
                                                            ) }}<br>
                                                            {{ __('Pay42 exchange rate: ') . $order->pay42_exchange_rate }}
                                                        </p>
                                                    @else
                                                        {{ $first_curr->sign . ' ' . __('Total:') }}
                                                        {{ $first_curr->sign }}{{ number_format(
                                                            $order->pay_amount,
                                                            $first_curr->decimal_digits,
                                                            $first_curr->decimal_separator,
                                                            $first_curr->thousands_separator,
                                                        ) }}<br>
                                                </p>
                                @endif

                                <p>{{ __('Payment Method:') }} {{ $order->method }}</p>

                                @if ($order->method != 'Cash On Delivery')
                                    @if ($order->method == 'Stripe')
                                        {{ $order->method }} {{ __('Charge ID:') }} <p>{{ $order->charge_id }}</p>
                                    @endif
                                    {{ $order->method }} {{ __('Transaction ID:') }} <p id="ttn">
                                        {{ $order->txnid }}
                                    </p>

                                    @if ($order->method == 'Bank Deposit')
                                        <div style="margin-top: 10px; font-size: small;">
                                            @foreach ($bank_accounts as $bank_account)
                                                <ul class="list-group" style="margin-top: 10px">
                                                    <li class="list-group-item" style="padding: 5px;">
                                                        {{ strtoupper($bank_account->name) }}</li>
                                                    <li class="list-group-item">{!! nl2br(str_replace(' ', ' &nbsp;', $bank_account->info)) !!}</li>
                                                </ul>
                                            @endforeach
                                        </div>
                                    @endif

                                    <form id="tform">
                                        <input style="display: none; width: 100%;" type="text" id="tin"
                                            placeholder="{{ __('Enter Transaction ID & Press Enter') }}" required=""
                                            class="mb-3">
                                        <input type="hidden" id="oid" value="{{ $order->id }}">

                                        <button
                                            style="display: none; padding: 5px 15px; height: auto; width: auto; line-height: unset;"
                                            id="tbtn" type="submit" class="mybtn1">{{ __('Submit') }}</button>

                                        <a style="display: none; cursor: pointer;  padding: 5px 15px; height: auto; width: auto; line-height: unset;"
                                            id="tc" class="mybtn1">{{ __('Cancel') }}</a>

                                        {{-- Change 1 --}}
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    <br>
                    @if ($order->is_qrcode)
                        @if (now() < $order->pay42_due_date)
                            <div style="display:flex; justify-content:center;">
                                <img src="{{ asset(' assets/temp/' . $order->id . '/qrcode.png') }}" />
                            </div>
                            <div style="display:flex; justify-content:center;">
                                <p class="pix"> PIX QR CODE {{ __('Expire in: ') }}
                                    {{ $order->pay42_due_date . ' UTC' }}
                                </p>
                            </div>
                        @else
                            <div style="display:flex; justify-content:center;">
                                <p class="pix"> PIX QR CODE {{ __('Expired') }}</p>
                            </div>
                        @endif
                    @endif

                    @if ($order->pay42_billet)
                        <div class="col-md-12" style="display:flex; justify-content:center">
                            <a class="btn btn-success m-3" href="{{ $order->pay42_billet }}" target="_blank"><span
                                    class="heading" style="font-size: 20px"><i
                                        class="fa fa-external-link"></i>{{ __('Access billet') }} </a></span>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <h5>{{ __('Ordered Products:') }}</h5>
                        <table class="table table-bordered veiw-details-table">
                            <thead>
                                <tr>
                                    <th width="5%">{{ __('ID#') }}</th>
                                    <th width="35%">{{ __('Name') }}</th>
                                    <th width="20%">{{ __('Details') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart['items'] as $product)
                                    <tr>
                                        <td>{{ $product['item']['id'] }}</td>
                                        <td>
                                            <input type="hidden" value="{{ $product['license'] }}">

                                            @if ($product['item']['user_id'] != 0)
                                                @php
                                                    $user = App\Models\User::find($product['item']['user_id']);
                                                @endphp
                                                @if (isset($user))
                                                    <a target="_blank"
                                                        href="{{ route('front.product', $product['item']['slug']) }}">{{ mb_strlen($product['item']['name'], 'utf-8') > 30
                                                            ? mb_substr($product['item']['name'], 0, 30, 'utf-8') . '...'
                                                            : $product['item']['name'] }}</a>
                                                @else
                                                    <a target="_blank"
                                                        href="{{ route('front.product', $product['item']['slug']) }}">{{ mb_strlen($product['item']['name'], 'utf-8') > 30
                                                            ? mb_substr($product['item']['name'], 0, 30, 'utf-8') . '...'
                                                            : $product['item']['name'] }}</a>
                                                @endif
                                            @else
                                                <a target="_blank"
                                                    href="{{ route('front.product', $product['item']['slug']) }}">{{ mb_strlen($product['item']['name'], 'utf-8') > 30
                                                        ? mb_substr($product['item']['name'], 0, 30, 'utf-8') . '...'
                                                        : $product['item']['name'] }}</a>
                                            @endif
                                            @if ($product['item']['type'] != 'Physical')
                                                @if ($order->payment_status == 'Completed')
                                                    @if ($product['item']['file'] != null)
                                                        <a href="{{ route('user-order-download', ['slug' => $order->order_number, 'id' => $product['item']['id']]) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fa fa-download"></i> {{ __('Download') }}
                                                        </a>
                                                    @else
                                                        <a target="_blank" href="{{ $product['item']['link'] }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fa fa-download"></i> {{ __('Download') }}
                                                        </a>
                                                    @endif
                                                    @if ($product['license'] != '')
                                                        <a href="javascript:;" data-toggle="modal"
                                                            data-target="#confirm-delete"
                                                            class="btn btn-sm btn-info product-btn" id="license"><i
                                                                class="fa fa-eye"></i> {{ __('View License') }}</a>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td width="40%">
                                            <b>{{ __('Quantity') }}</b>: {{ $product['qty'] }} <br>
                                            @if (!empty($product['size']))
                                                <b>{{ __('Size') }}</b>:
                                                {{ $product['item']['measure'] }}{{ str_replace('-', ' ', $product['size']) }}
                                                <br>
                                            @endif
                                            @if (env('ENABLE_CUSTOM_PRODUCT') || env('ENABLE_CUSTOM_PRODUCT_NUMBER'))
                                                @if (!empty($product['customizable_name']))
                                                    <b>{{ __('Custom Name') }}</b>:
                                                    {{ $product['customizable_name'] }}<br>
                                                @endif
                                            @endif
                                            @if (env('ENABLE_CUSTOM_PRODUCT_NUMBER'))
                                                @if (!empty($product['customizable_number']))
                                                    <b>{{ __('Custom Number') }}</b>:
                                                    {{ $product['customizable_number'] }}<br>
                                                @endif
                                            @endif
                                            @if (env('ENABLE_CUSTOM_PRODUCT'))
                                                @if (!empty($product['customizable_gallery']))
                                                    <b>{{ __('Custom Gallery') }}</b>: <img
                                                        src="{{ asset(
                                                            "
                                                                                                                storage/images/thumbnails/" . $product['customizable_gallery'],
                                                        ) }}"
                                                        width="100px"><br>
                                                @endif
                                                @if (!empty($product['customizable_logo']))
                                                    <b>{{ __('Custom Logo') }}</b>: <img
                                                        src="{{ asset(
                                                            "
                                                                                                                storage/images/custom-logo/" . $product['customizable_logo'],
                                                        ) }}"
                                                        width="100px"><br>
                                                @endif
                                            @endif
                                            @if (!empty($product['color']))
                                                <div class="d-flex mt-2">
                                                    <b>{{ __('Color') }}</b>: <span id="color-bar"
                                                        style="border: 10px solid {{ $product['color'] == ''
                                                            ? "
                                                                                                                    white"
                                                            : '#' . $product['color'] }};"></span>
                                                </div>
                                            @endif

                                            @if (!empty($product['keys']))
                                                @foreach (array_combine(explode(',', $product['keys']), explode('~', $product['values'])) as $key => $value)
                                                    <b>{{ App\Models\Attribute::where('input_name', $key)->first()->name }}
                                                        : </b> {{ $value }} <br>
                                                @endforeach
                                            @endif

                                        </td>
                                        <td style="text-align: end;">
                                            {{ $order->currency_sign }}{{ number_format(
                                                $product['item']['price'] * $order->currency_value,
                                                $order_curr->decimal_digits,
                                                $order_curr->decimal_separator,
                                                $order_curr->thousands_separator,
                                            ) }}<br><small>{{ $first_curr->sign }}{{ number_format(
                                                $product['item']['price'],
                                                $first_curr->decimal_digits,
                                                $first_curr->decimal_separator,
                                                $first_curr->thousands_separator,
                                            ) }}</small>
                                        </td>
                                        <td style="text-align: end;">
                                            {{ $order->currency_sign }}{{ number_format(
                                                $product['price'] * $order->currency_value,
                                                $order_curr->decimal_digits,
                                                $order_curr->decimal_separator,
                                                $order_curr->thousands_separator,
                                            ) }}<br><small>{{ $first_curr->sign }}{{ number_format(
                                                $product['price'],
                                                $first_curr->decimal_digits,
                                                $first_curr->decimal_separator,
                                                $first_curr->thousands_separator,
                                            ) }}</small>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="edit-account-info-div">
                            <div class="form-group">
                                <a class="back-btn" href="{{ route('user-orders') }}">{{ __('Back') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
        </div>
    </section>

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('License Key') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p class="text-center">{{ __('The Licenes Key is :') }} <span id="key"></span></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script type="text/javascript">
        $('#example').dataTable({
            "ordering": false,
            'paging': false,
            'lengthChange': false,
            'searching': false,
            'ordering': false,
            'info': false,
            'autoWidth': false,
            'responsive': true
        });
    </script>
    <script>
        $(document).on("click", "#tid", function(e) {
            $(this).hide();
            $("#tc").show();
            $("#tin").show();
            $("#tbtn").show();
        });
        $(document).on("click", "#tc", function(e) {
            $(this).hide();
            $("#tid").show();
            $("#tin").hide();
            $("#tbtn").hide();
        });
        $(document).on("submit", "#tform", function(e) {
            var oid = $("#oid").val();
            var tin = $("#tin").val();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('user/json/trans') }}",
                data: {
                    id: oid,
                    tin: tin
                },
                success: function(data) {
                    $("#ttn").html(data);
                    $("#tin").val("");
                    $("#tid").show();
                    $("#tin").hide();
                    $("#tbtn").hide();
                    $("#tc").hide();
                }
            });
            return false;
        });
    </script>
    <script type="text/javascript">
        $(document).on('click', '#license', function(e) {
            var id = $(this).parent().find('input[type=hidden]').val();
            $('#key').html(id);
        });
    </script>
@endsection
