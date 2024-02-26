@extends('layouts.admin')
@section('styles')
    <style>
        .zoom {
            transition: all ease .2s;
            border: 1px solid black;
        }

        .zoom:hover {

            transform: scale(1.2);
            transition: all ease .2s;
            border: 1px solid black;
        }
    </style>
@endsection
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <h4 class="heading">{{ __('Uploaded Receipt') }} <a class="add-btn" href="javascript:history.back();"><i
                            class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('admin-order-index') }}">{{ __('Orders') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('admin-order-index') }}">{{ __('Uploaded Receipt') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive" style="overflow-x: hidden;">
                        <h5>{{ __('Order Details') }}</h5>
                        <h6>{{ __('Customer Name') . ': ' . $order->customer_name }}</h6>
                        <h6>{{ __('Order Number') . ': ' . $order->order_number }}</h6>
                        <table class="table table-bordered veiw-details-table">
                            <thead>
                                <tr>
                                    <th width="20%">{{ __('ID#') }}</th>
                                    <th width="55%">{{ __('Name') }}</th>
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
                                                                class="fa fa-eye"></i>
                                                            {{ __('View License') }}</a>
                                                    @endif
                                                @endif
                                            @endif
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
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <img class="zoom" id="preview" src="{{ asset('storage/images/receipts/' . $order->receipt) }}"
                        alt="">
                </div>
            </div>
            @if ($order->payment_status == 'Pending' && $order->receipt != null)
                <br>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <button type="submit"
                            action="{{ route('admin-order-manage-receipt', ['id' => $order->id, 'action' => 'accept']) }}"
                            id="btnAccept" class="btn btn-success btn-ok btnManage">{{ __('Accept') }}</button>
                        <button type="submit"
                            action="{{ route('admin-order-manage-receipt', ['id' => $order->id, 'action' => 'reject']) }}"
                            id="btnReject" class="btn btn-danger btn-ok btnManage">{{ __('Reject') }}</button>
                        </form>
                    </div>
                </div>
            @elseif($order->payment_status == 'Completed')
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <span class="badge badge-success">{{ __('This receipt has been already accepted.') }}</span>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <span class="badge badge-danger">{{ __('There is no receipt for this order.') }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="submit-loader">
        <img src="{{ $admstore->adminLoaderUrl }}" alt="">
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".btnManage").click(function() {
                $.ajax({
                    method: "GET",
                    url: $(this).attr('action'),
                    beforeSend: function(data) {
                        $(".submit-loader").show();
                    },
                    success: function(data) {
                        $(".submit-loader").hide();
                        if ((data.errors)) {
                            $.notify("Erro!");
                        } else {
                            if (data.redirect) {
                                $.notify(data.msg, "error");
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                $.notify(data.msg, "success");
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        }
                    },
                });
            });
        });
    </script>
@endsection
