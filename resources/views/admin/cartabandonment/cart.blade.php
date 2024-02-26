@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Cart Abandonment') }}<a class="add-btn" href="{{ url()->previous() }}"><i
                                class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-user-index') }}">{{ __('Cart Abandonment') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="col-lg-12">
                <div class="invoice_table">
                    <div class="mr-table">
                        <div class="table-responsive">
                            <div class="col-lg-12">
                                <h5 class="title">{{ __('Products of Cart Abandonment') }} #{{ $id }} </h5>
                            </div>
                            <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead style="border-top:1px solid rgba(0, 0, 0, 0.1) !important;">
                                    <tr>
                                        <th>{{ __('Product') }}</th>
                                        <th>{{ __('Details') }}</th>
                                        <th>{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $subtotal = 0;
                                        $tax = 0;
                                    @endphp
                                    @foreach ($cart->items as $product)
                                        <tr>
                                            <td width="50%">
                                                @if ($product['item']['user_id'] != 0)
                                                    @php
                                                        $user = App\Models\User::find($product['item']['user_id']);
                                                    @endphp
                                                    @if (isset($user))
                                                        {{ $product['item']['name'] }}
                                                    @else
                                                        {{ $product['item']['name'] }}
                                                    @endif
                                                @else
                                                    {{ $product['item']['name'] }}
                                                @endif
                                            </td>

                                            <td>
                                                @if ($product['size'])
                                                    <p>
                                                        Size : {{ str_replace('-', ' ', $product['size']) }}
                                                    </p>
                                                @endif
                                                @if ($product['color'])
                                                    <p>
                                                        {{ __('Color') }} : <span
                                                            style="margin-left: 40px; width: 20px; height: 0.2px; display: block; border: 10px solid {{ $product['color'] == ''
                                                                ? "
                                                                                                            white"
                                                                : $product['color'] }};"></span>
                                                    </p>
                                                @endif
                                                <p>
                                                    {{ __('Price') }} :
                                                    {{ $order_curr->sign }}{{ number_format(
                                                        $product['item']['price'] * $order_curr->value,
                                                        $order_curr->decimal_digits,
                                                        $order_curr->decimal_separator,
                                                        $order_curr->thousands_separator,
                                                    ) }}
                                                </p>
                                                <p>
                                                    <small>{{ $first_curr->sign . ' ' . __('Price') }} :
                                                        {{ $first_curr->sign }}{{ number_format(
                                                            $product['item']['price'],
                                                            $first_curr->decimal_digits,
                                                            $first_curr->decimal_separator,
                                                            $first_curr->thousands_separator,
                                                        ) }}</small>
                                                </p>

                                                <p>
                                                    {{ __('Qty') }} : {{ $product['qty'] }}
                                                    {{ $product['item']['measure'] }}
                                                </p>
                                            </td>

                                            <td style="text-align: end;">
                                                {{ $order_curr->sign }}{{ number_format(
                                                    $product['price'] * $order_curr->value,
                                                    $order_curr->decimal_digits,
                                                    $order_curr->decimal_separator,
                                                    $order_curr->thousands_separator,
                                                ) }}
                                                <br><small>{{ $first_curr->sign }}{{ number_format(
                                                    $product['price'],
                                                    $first_curr->decimal_digits,
                                                    $first_curr->decimal_separator,
                                                    $first_curr->thousands_separator,
                                                ) }}</small>
                                            </td>
                                            @php
                                                $subtotal += round($product['price'] * $order_curr->value, 2);
                                            @endphp

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="submit-loader">
        <img src="{{ $gs->adminLoaderUrl }}" alt="">
    </div>

    {{-- DELETE MODAL ENDS --}}
@endsection
