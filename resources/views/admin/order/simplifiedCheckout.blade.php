@extends('layouts.admin')

@section('styles')
    <style type="text/css">
        .mr-breadcrumb .links .action-list li {
            display: block;
        }

        .mr-breadcrumb .links .action-list ul {
            overflow-y: auto;
            max-height: 240px;
        }

        .mr-breadcrumb .links .action-list .go-dropdown-toggle {
            padding-left: 20px;
            padding-right: 30px;
        }

        .input-field {
            padding: 15px 20px;
        }
    </style>
@endsection

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Simplified Checkout Orders') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-order-index') }}">{{ __('Orders') }}</a>
                        </li>
                        <li>
                            <div class="action-list godropdown">
                                <select id="order_simplified_filters" class="process select go-dropdown-toggle">
                                    @foreach ($filters as $filter => $name)
                                        <option value="{{ route('admin-order-simplified-datatables', $filter) }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @include('includes.admin.partials.order-tabs')
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('includes.admin.form-success')
                        <div class="table-responsiv">
                            <div class="gocover"
                                style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><i class="icofont-options icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Options') }}'></i></th>
                                        <th><i class="icofont-user icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Customer Name') }}'></i></th>
                                        <th><i class="icofont-phone-circle icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Customer Phone') }}'></i></th>
                                        <th><i class="icofont-calendar icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Customer Calendar') }}'></i></th>
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

    {{-- ORDER MODAL --}}

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ $admstore->adminLoaderUrl }}" alt="">
                </div>
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('Update Status') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">{{ __("You are about to update the order's Status.") }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-success btn-ok order-btn">{{ __('Proceed') }}</a>
                </div>

            </div>
        </div>
    </div>

    {{-- ORDER MODAL ENDS --}}

    {{-- MESSAGE MODAL --}}
    <div class="sub-categori">
        <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vendorformLabel">{{ __('Send Email') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="contact-form">
                                        <form id="emailreply">
                                            {{ csrf_field() }}
                                            <ul>
                                                <li>
                                                    <input type="email" class="input-field eml-val" id="eml"
                                                        name="to" placeholder="{{ __('Email') }} *" value=""
                                                        required="">
                                                </li>
                                                <li>
                                                    <input type="text" class="input-field" id="subj"
                                                        name="subject" placeholder="{{ __('Subject') }} *"
                                                        required="">
                                                </li>
                                                <li>
                                                    <textarea class="input-field textarea" name="message" id="msg" placeholder="{{ __('Your Message') }} *"
                                                        required=""></textarea>
                                                </li>
                                            </ul>
                                            <button class="submit-btn" id="emlsub"
                                                type="submit">{{ __('Send Email') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MESSAGE MODAL ENDS --}}

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
            stateLoadParams: function(settings, data) {

                // Persist filter selection based on SessionStorage
                var selected = sessionStorage.getItem('SelectedOrderSimplifiedFilter');
                ajax = selected;
                var ul = $(".nice-select ul");
                var span = $(".nice-select span");
                $(ul).children("li").each(function() {
                    if (selected === $(this).attr('data-value')) {
                        $(this).addClass('selected focus');
                        span.text($(this).text());
                        $(this).trigger('click');
                        $(this).parent().parent().removeClass("open").delay(100);

                    } else $(this).removeClass('selected focus');
                });
            },
            stateDuration: -1,
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-order-simplified-datatables', 'all') }}',
            columns: [{
                    data: 'action',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'customer_name',
                    name: 'customer_name'
                },
                {
                    data: 'customer_phone',
                    name: 'customer_phone'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
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
            columnDefs: [
            {
                targets: 3,
                render: function(data, type, row) {
                    return moment(data).format('DD-MMM-YYYY HH:mm:ss');
                }
            }
        ],
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
            },
            drawCallback: function(settings) {
                $(this).find('.select').niceSelect();

                /*
                 * If any of the filters are changed, the table resets completely.
                 * It also adds current SelectedOrderSimplifiedFilter to Session Storage, with is used to
                 * keep the selection until user leaves the scope.
                 */
                $('#order_simplified_filters').on('change', function() {
                    sessionStorage.setItem('SelectedOrderSimplifiedFilter', $(this).val());
                    table.ajax.url(sessionStorage.getItem('SelectedOrderSimplifiedFilter')).load();
                    sessionStorage.setItem("CurrentPage", 0);
                });
            },
            initComplete: function(settings, json) {
                /*
                 * Restoring current page via Session Storage
                 */
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
    </script>

    {{-- DATA TABLE --}}

    <script>
        $(document).ready(function() {
            // First access - SelectedOrderSimplifiedFilter
            if (sessionStorage.getItem('SelectedOrderSimplifiedFilter') == undefined) {
                sessionStorage.setItem('SelectedOrderSimplifiedFilter', $("#order_simplified_filters").val());
            } else table.ajax.url(sessionStorage.getItem('SelectedOrderSimplifiedFilter')).load();
            // First access - CurrentPage
            if (sessionStorage.getItem("CurrentPage") == undefined) {
                sessionStorage.setItem("CurrentPage", 0);
                sessionStorage.setItem('SelectedOrderSimplifiedFilter', $("#order_simplified_filters").find(
                    "option:first").val());
            }
        });
        $("#order_simplified_filters").on('change', function() {

            table.ajax.url($(this).val()).load();
        });
        $(document).on('click', 'a', function(e) {
            var link = jQuery(this);
            var x = '{{ Request::route()->getPrefix() }}';
            y = x.split("/");
            if (!(link.attr("data-href") || link.attr("href").indexOf("#") > -1 || link.attr("href").indexOf(
                    "javascript") > -1 || link.attr("href").indexOf("simplified-checkout") > -1 || link.attr(
                    "href").indexOf("order") > -1)) {
                sessionStorage.setItem("CurrentPage", 0);
                sessionStorage.setItem('SelectedOrderSimplifiedFilter', $("#order_simplified_filters").find(
                    "option:first").val());
                table.state.clear();
            }
            if (link.attr("href").indexOf("orders") > 1) {
                sessionStorage.setItem("CurrentPage", 0);
                sessionStorage.setItem('SelectedSimplifiedOrderFilter', $("#order_simplified_filters").find(
                    "option:first").val());
                table.state.clear();
            }
        });
    </script>
@endsection
