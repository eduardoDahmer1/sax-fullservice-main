@extends('layouts.vendor')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('All Orders') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('vendor-dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('vendor-order-index') }}">{{ __('Orders') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('vendor-order-index') }}">{{ __('All Orders') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('includes.form-success')
                        <div class="table-responsiv">
                            <div class="gocover"
                                style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><i class="icofont-options icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Options') }}'></i></th>
                                        <th><i class="icofont-email icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Customer Email') }}'></i></th>
                                        <th><i class="icofont-user icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Customer Name') }}'></i></th>
                                        <th><i class="icofont-numbered icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Order Number') }}'></i></th>
                                        <th><i class="icofont-cart icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Total Qty') }}'></i></th>
                                        <th><i class="icofont-dollar icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Total Cost') }}'></i></th>
                                        <th>{{ __('Payment Status') }}</th>
                                        <th>{{ __('Delivery Status') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD / EDIT MODAL --}}

    <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ $admstore->adminLoaderUrl }}" alt="">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>

    </div>

    {{-- ADD / EDIT MODAL ENDS --}}
@endsection

@section('scripts')
    {{-- DATA TABLE --}}

    <script type="text/javascript">
        var table = $('#geniustable').DataTable({
            stateSave: true,
            stateDuration: -1,
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('vendor-order-datatables') }}',
            columns: [{
                    data: 'action',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'customer_email',
                    name: 'customer_email'
                },
                {
                    data: 'customer_name',
                    name: 'customer_name',
                    searchable: true
                },
                {
                    data: 'order_number',
                    name: 'order_number'
                },
                {
                    data: 'totalQty',
                    name: 'totalQty'
                },
                {
                    data: 'pay_amount',
                    name: 'pay_amount'
                },
                {
                    data: 'payment_status',
                    name: 'payment_status'
                },
                {
                    data: 'status',
                    name: 'status'
                }
            ],
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
            },
            initComplete: function(settings, json) {
                $(document).ready(function() {
                    table.page(parseInt(sessionStorage.getItem("CurrentPage"))).draw(false);
                });
                /*
                 * Setando no Cookie a página atual
                 */
                $("#geniustable").on('page.dt', function() {
                    sessionStorage.setItem("CurrentPage", table.page());
                });
            }
        });
        $(document).on('click', 'a', function(e) {
            var link = jQuery(this);
            var x = '{{ Request::route()->getPrefix() }}';
            y = x.split("/");
            if (!(link.attr("data-href") || link.attr("href").indexOf("#") > -1 || link.attr("href").indexOf(
                    "javascript") > -1 || link.attr("href").indexOf("orders") > -1 || link.attr("href").indexOf(
                    "order") > -1)) {
                sessionStorage.setItem("CurrentPage", 0);
                table.state.clear();
            }
        });
    </script>

    {{-- DATA TABLE --}}
@endsection
