@extends('layouts.admin')

@section('styles')
@endsection
<style>
    .card-bg {
        background-color: #2d3277 !important;
    }

    .card-border {
        border-color: #2d3277 !important;
    }

    .button-details {
        width: 105px !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }
</style>
@section('content')
    <div class="content-area">
        @include('includes.form-success')

        @if (Session::has('cache'))
            <div class="alert alert-success validation">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                <h3 class="text-center">{{ Session::get('cache') }}</h3>
            </div>
        @endif

        <div class="row row-cards-one ">
            <div class="col-md-12 col-lg-6 col-xl-4 ">
                <div class="mycard card-bg">
                    <div class="left">
                        <h5 class="title">{{ __('Orders Pending!') }} </h5>
                        <span class="number">{{ $data['pending_orders'] }}</span>
                        <a href="{{ route('admin-order-pending') }}" class="link">{{ __('View All') }}</a>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="icofont-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard card-bg">
                    <div class="left">
                        <h5 class="title">{{ __('Orders Processing!') }}</h5>
                        <span class="number">{{ $data['processing_orders'] }}</span>
                        <a href="{{ route('admin-order-processing') }}" class="link">{{ __('View All') }}</a>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="icofont-truck-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard card-bg">
                    <div class="left">
                        <h5 class="title">{{ __('Orders Completed!') }}</h5>
                        <span class="number">{{ $data['completed_orders'] }}</span>
                        <a href="{{ route('admin-order-completed') }}" class="link">{{ __('View All') }}</a>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="icofont-check-circled"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-6">
                <div class="mycard card-bg">
                    <div class="left">
                        <h5 class="title">{{ __('Total Products!') }}</h5>
                        <span class="number">{{ $data['products_total'] }}</span>
                        <a href="{{ route('admin-prod-index') }}" class="link">{{ __('View All') }}</a>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="icofont-cart-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-6">
                <div class="mycard card-bg">
                    <div class="left">
                        <h5 class="title">{{ __('Total Customers!') }}</h5>
                        <span class="number">{{ $data['customers_total'] }}</span>
                        <a href="{{ route('admin-user-index') }}" class="link">{{ __('View All') }}</a>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="icofont-users-alt-5"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row row-cards-one">
            <div class="col-md-6 col-xl-4">
                <div class="card c-info-box-area">
                    <div class="c-info-box box1 card-border">
                        <p>{{ $data['last_month_customers'] }}</p>
                    </div>
                    <div class="c-info-box-content ">
                        <h6 class="title">{{ __('New Customers') }}</h6>
                        <p class="text">{{ __('Last 30 Days') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card c-info-box-area">
                    <div class="c-info-box box3 card-border">
                        <p>{{ $data['last_month_orders'] }}</p>
                    </div>
                    <div class="c-info-box-content">
                        <h6 class="title">{{ __('Total Sales') }}</h6>
                        <p class="text">{{ __('Last 30 days') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card c-info-box-area">
                    <div class="c-info-box box4 card-border">
                        <p>{{ $data['completed_orders'] }}</p>
                    </div>
                    <div class="c-info-box-content">
                        <h6 class="title">{{ __('Total Sales') }}</h6>
                        <p class="text">{{ __('All Time') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cards-one">

            <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <h5 class="card-header">{{ __('Recent Order(s)') }}</h5>
                    <div class="card-body">

                        <div class="my-table-responsiv">
                            <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>

                                        <th>{{ __('Order Number') }}</th>
                                        <th>{{ __('Order Date') }}</th>
                                    </tr>
                                    @foreach ($data['recent_orders'] as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ date('Y-m-d', strtotime($order->created_at)) }}</td>
                                            <td>
                                                <div class="action-list "><a
                                                        href="{{ route('admin-order-show', $order->id) }} "
                                                        class="button-details"><i class="fas fa-eye"></i>
                                                        {{ __('Details') }}</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <h5 class="card-header">{{ __('Recent Customer(s)') }}</h5>
                    <div class="card-body">

                        <div class="my-table-responsiv">
                            <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Customer Email') }}</th>
                                        <th>{{ __('Joined') }}</th>
                                    </tr>
                                    @foreach ($data['recent_customers'] as $customer)
                                        <tr>
                                            <td>{{ $customer->customer_email }}</td>
                                            <td>{{ $customer->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cards-one">

            <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <h5 class="card-header">{{ __('Popular Product(s)') }}</h5>
                    <div class="card-body">

                        <div class="table-responsiv  dashboard-home-table">
                            <table id="poproducts" class="table table-hover dt-responsive" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Featured Image') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['popular_products'] as $product)
                                        <tr>
                                            <td><img
                                                    src="{{ filter_var($product->photo, FILTER_VALIDATE_URL) ? $product->photo : asset('storage/images/products/' . $product->photo) }}">
                                            </td>
                                            <td>{{ mb_strlen(strip_tags($product->name), 'utf-8') > 50
                                                ? mb_substr(strip_tags($product->name), 0, 50, 'utf-8') . '...'
                                                : strip_tags($product->name) }}
                                            </td>
                                            <td>{{ $product->category->name }}
                                                @if (isset($product->subcategory))
                                                    <br>
                                                    {{ $product->subcategory->name }}
                                                @endif
                                                @if (isset($product->childcategory))
                                                    <br>
                                                    {{ $product->childcategory->name }}
                                                @endif
                                            </td>
                                            <td>{{ $product->type }}</td>

                                            <td> {{ $product->showPrice() }} </td>

                                            <td>
                                                <div class="action-list"><a
                                                        href="{{ route('admin-prod-edit', $product->id) }}"><i
                                                            class="fas fa-eye"></i> {{ __('Details') }}</a>
                                                </div>
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

        <div class="row row-cards-one">

            <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <h5 class="card-header">{{ __('Recent Product(s)') }}</h5>
                    <div class="card-body">

                        <div class="table-responsiv dashboard-home-table">
                            <table id="pproducts" class="table table-hover dt-responsive" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Featured Image') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['recent_products'] as $recent_product)
                                        <tr>
                                            <td><img
                                                    src="{{ filter_var($recent_product->photo, FILTER_VALIDATE_URL) ? $recent_product->photo : asset('storage/images/products/' . $recent_product->photo) }}">
                                            </td>
                                            <td>{{ mb_strlen(strip_tags($recent_product->name), 'utf-8') > 50
                                                ? mb_substr(strip_tags($recent_product->name), 0, 50, 'utf-8') . '...'
                                                : strip_tags($recent_product->name) }}
                                            </td>
                                            <td>{{ $recent_product->category->name }}
                                                @if (isset($recent_product->subcategory))
                                                    <br>
                                                    {{ $recent_product->subcategory->name }}
                                                @endif
                                                @if (isset($recent_product->childcategory))
                                                    <br>
                                                    {{ $recent_product->childcategory->name }}
                                                @endif
                                            </td>
                                            <td>{{ $recent_product->type }}</td>
                                            <td> {{ $recent_product->showPrice() }} </td>
                                            <td>
                                                <div class="action-list"><a
                                                        href="{{ route('admin-prod-edit', $recent_product->id) }}"><i
                                                            class="fas fa-eye"></i> {{ __('Details') }}</a>
                                                </div>
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

        <div class="row row-cards-one">

            <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <h5 class="card-header">{{ __('Total Sales in Last 30 Days') }}</h5>
                    <div class="card-body">

                        <canvas id="lineChart"></canvas>

                    </div>
                </div>

            </div>

        </div>
    @endsection

    @section('scripts')
        <script language="JavaScript">
            displayLineChart();

            function displayLineChart() {
                var data = {
                    labels: [
                        {!! $days !!}
                    ],
                    datasets: [{
                        label: "Prime and Fibonacci",
                        fillColor: "#3dbcff",
                        strokeColor: "#0099ff",
                        pointColor: "rgba(220,220,220,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [
                            {!! $sales !!}
                        ]
                    }]
                };
                var ctx = document.getElementById("lineChart").getContext("2d");
                var options = {
                    responsive: true
                };
                var lineChart = new Chart(ctx).Line(data, options);
            }
        </script>

        <script type="text/javascript">
            $('#poproducts').dataTable({
                language: {
                    url: '{{ $datatable_translation }}',
                    processing: '<img src="{{ $gs->adminLoaderUrl }}">'
                },
                "ordering": false,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'info': false,
                'autoWidth': false,
                'responsive': true,
                'paging': false
            });
        </script>


        <script type="text/javascript">
            $('#pproducts').dataTable({
                language: {
                    url: '{{ $datatable_translation }}',
                    processing: '<img src="{{ $gs->adminLoaderUrl }}">'
                },
                "ordering": false,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'info': false,
                'autoWidth': false,
                'responsive': true,
                'paging': false
            });
        </script>
    @endsection
