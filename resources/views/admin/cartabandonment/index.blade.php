@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Cart Abandonment') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-cartabandonment-index') }}">{{ __('Cart Abandonment') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('includes.admin.form-success')
                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><i class="icofont-email icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Send Email') }}'></i></th>
                                        <th>{{ __('Name') }}</th>
                                        <th><i class="icofont-envelope icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Email') }}'></i></th>
                                        <th><i class="icofont-envelope-open icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Email Sent') }}'></i></th>
                                        <th><i class="icofont-cart icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Qty') }}'></i></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">{{ __('You are about to discard this Cart Abandonment.') }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
                </div>

            </div>
        </div>
    </div>
    <div class="submit-loader">
        <img src="{{ $gs->adminLoaderUrl }}" alt="">
    </div>

    {{-- DELETE MODAL ENDS --}}
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
            ajax: '{{ route('admin-cartabandonment-datatables') }}',
            columns: [{
                    data: 'action',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'email_sent',
                    name: 'email_sent'
                },
                {
                    data: 'qty',
                    name: 'qty'
                }
            ],
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
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
    <script>
        $(document).ready(function() {
            // First access - CurrentPage
            if (sessionStorage.getItem("CurrentPage") == undefined) {
                sessionStorage.setItem("CurrentPage", 0);
            }
        });
        $(document).on('click', 'a', function(e) {
            var link = jQuery(this);
            var x = '{{ Request::route()->getPrefix() }}';
            y = x.split("/");
            if (!(link.attr("data-href") || link.attr("href").indexOf("#") > -1 || link.attr("href").indexOf(
                    "javascript") > -1 || link.attr("href").indexOf("cartabandonments") > -1 || link.attr(
                    "href").indexOf("cartabandonments") > -1)) {
                sessionStorage.setItem("CurrentPage", 0);
                table.state.clear();
            }
        });
    </script>
    {{-- DATA TABLE --}}
@endsection
