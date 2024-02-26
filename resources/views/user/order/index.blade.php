@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')
    <section class="user-dashbord">
        <div class="container">
            <div class="row">
                @include('includes.user-dashboard-sidebar')
                <div class="col-lg-8">
                    <div id="div-table" class="user-profile-details">
                        <div class="order-history">
                            <div class="header-area">
                                <h4 class="title">
                                    {{ __('Pending Receipt Orders') }}
                                </h4>
                            </div>
                            <div class="mr-table allproduct mt-4">
                                <div class="table-responsiv">
                                    <table id="example2" class="table table-hover dt-responsive" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('#Order') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Order Total') }}</th>
                                                <th>{{ __('Deposit Receipt') }}</th>
                                                <th>{{ __('View') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                @if ($order->method == 'Bank Deposit' && $order->payment_status == 'Pending' && $order->status != 'declined')
                                                    @php
                                                        $order_curr = $currencies->firstWhere('sign', $order->currency_sign);
                                                        if (empty($order_curr)) {
                                                            $order_curr = $currencies->first();
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            {{ $order->order_number }}
                                                        </td>
                                                        <td>
                                                            {{ date('d M Y', strtotime($order->created_at)) }}
                                                        </td>
                                                        <td>
                                                            {{ $order->currency_sign }}{{ number_format(
                                                                $order->pay_amount * $order->currency_value,
                                                                $order_curr->decimal_digits,
                                                                $order_curr->decimal_separator,
                                                                $order_curr->thousands_separator,
                                                            ) }}
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-warning"><a
                                                                    href="{{ route('user-upload-receipt', $order->id) }}">{{ __('Upload your receipt') }}</a></span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('user-order', $order->id) }}">
                                                                {{ __('VIEW ORDER') }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="user-profile-details">
                        <div class="order-history">
                            <div class="header-area">
                                <h4 class="title">
                                    {{ __('Purchased Items') }}
                                </h4>
                            </div>
                            <div class="mr-table allproduct mt-4">
                                <div class="table-responsiv">
                                    <table id="example" class="table table-hover dt-responsive" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('#Order') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Order Total') }}</th>
                                                <th>{{ __('Payment Status') }}</th>
                                                <th>{{ __('Delivery Status') }}</th>
                                                <th>{{ __('View') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                @php
                                                    $order_curr = $currencies->firstWhere('sign', $order->currency_sign);
                                                    if (empty($order_curr)) {
                                                        $order_curr = $currencies->first();
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{ $order->order_number }}
                                                    </td>
                                                    <td>
                                                        {{ date('d M Y', strtotime($order->created_at)) }}
                                                    </td>
                                                    <td>
                                                        {{ $order->currency_sign }}{{ number_format(
                                                            $order->pay_amount * $order->currency_value,
                                                            $order_curr->decimal_digits,
                                                            $order_curr->decimal_separator,
                                                            $order_curr->thousands_separator,
                                                        ) }}
                                                    </td>
                                                    <td>
                                                        <div class="payment-status {{ lcfirst($order->payment_status) }}">
                                                            {{ __(ucfirst($order->payment_status)) }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="order-status {{ $order->status }}">
                                                            {{ __(ucfirst($order->status)) }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('user-order', $order->id) }}">
                                                            {{ __('VIEW ORDER') }}
                                                        </a>
                                                    </td>
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
        </div>
    </section>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                ordering: false,
                processing: true,
                serverSide: false,
                language: {
                    url: '{{ $datatable_translation }}',
                    processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
                }
            });
            $("#div-table").attr("hidden", true);
            table.rows().count() > 0 ? $("#div-table").attr("hidden", false) : $("#div-table").attr("hidden", true);
        });
    </script>
@endsection
