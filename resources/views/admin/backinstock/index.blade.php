@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Back in Stock') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-backinstock-index') }}">{{ __('Back in Stock') }}</a>
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
                                        <th><i class="icofont-envelope icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Email') }}'></i></th>
                                        <th>{{ __('Product') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
            ajax: '{{ route('admin-backinstock-datatables') }}',
            columns: [

                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'product',
                    name: 'product'
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
