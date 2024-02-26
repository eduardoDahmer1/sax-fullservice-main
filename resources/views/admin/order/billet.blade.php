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
                <h4 class="heading">{{ __('Order') }} {{ $order->order_number }} <a class="add-btn"
                        href="javascript:history.back();"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('admin-order-index') }}">{{ __('Orders') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('admin-order-index') }}">{{ __('Billet') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="input-form">
                        <div class="table-responsive" style="overflow-x: hidden;">
                            <h4>{{ __('Order Details') }}</h5>
                                <h6>{{ __('Customer Name') . ': ' . $order->customer_name }}</h6>
                                <h6>{{ __('Customer Email') . ': ' . $order->customer_email }}</h6>
                                <h6>{{ __('Customer Phone') . ': ' . $order->customer_phone }}</h6>
                                <h6>{{ __('Order Number') . ': ' . $order->order_number }}</h6>
                                <h6>{{ __('PagHiper ID') . ': ' . $order->txnid }}</h6>
                                <h6>{{ __('Total') . ': ' . $order->currency_sign }}{{ number_format(
                                    $order->pay_amount * $order->currency_value,
                                    $order_curr->decimal_digits,
                                    $order_curr->decimal_separator,
                                    $order_curr->thousands_separator,
                                ) }}
                                </h6>
                                @if ($billet['status_request']['status'] == 'pending')
                                    <span
                                        class="badge badge-secondary">{{ __('Billet Status') . ': ' . __('Pending') }}</span>
                                @elseif($billet['status_request']['status'] == 'canceled')
                                    <span
                                        class="badge badge-danger">{{ __('Billet Status') . ': ' . __('Canceled') }}</span>
                                @elseif($billet['status_request']['status'] == 'paid')
                                    <span class="badge badge-success">{{ __('Billet Status') . ': ' . __('Paid') }}</span>
                                @endif
                        </div>
                    </div>
                </div>
            </div>
            @if ($billet['status_request']['status'] == 'pending')
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="input-form">
                            <a class="btn btn-success" target="_blank"
                                href="https://web.whatsapp.com/send?phone={{ $ddi }}{{ $order->customer_phone }}&text={{ $msg }}">{{ __('Re-send Billet via WhatsApp') }}</a>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <embed src="{{ $billet[' status_request']['bank_slip']['url_slip'] }}" width="100%" height="1000px"
                        type="application/pdf">
                </div>
            </div>
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
