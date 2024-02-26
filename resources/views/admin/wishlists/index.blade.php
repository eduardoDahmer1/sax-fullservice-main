@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Wishlists') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.wishlist.index') }}">{{ __('Wishlists') }}</a>
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
                                        <th><i class="icofont-options icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Options') }}'></i></th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Quantity of products') }}</th>
                                        <th><i class="icofont-user icofont-lg" data-toggle="tooltip"
                                            title='{{ __('Customer Name') }}'></i></th>
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
@endsection

@section('scripts')
    {{-- DATA TABLE --}}

    <script type="text/javascript">
        var table = $('#geniustable').DataTable({
            stateSave: true,
            stateDuration: -1,
            searching: true,
            ordering: false,
            processing: true,
            serverSide: false,
            ajax: '{{ route('admin.wishlist.datatables') }}',
            columns: [{
                    data: 'action',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    searchable: true,
                },
                {
                    data: 'qtd',
                    name: 'qtd',
                    searchable: false,
                },
                {
                    data: 'user_name',
                    name: 'user_name',
                    searchable: true,
                },
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
                    "javascript") > -1 || link.attr("href").indexOf("wishlists") > -1 || link.attr("href").indexOf(
                    "wishlist") > -1)) {
                sessionStorage.setItem("CurrentPage", 0);
                table.state.clear();
            }
        });
    </script>
    {{-- DATA TABLE --}}
@endsection
