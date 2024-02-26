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

        .add-btn {
            padding-left: 20px;
            padding-right: 30px;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Top Small') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-ps-best-seller') }}">{{ __('Banners') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-sb-index') }}">{{ __('Top Small') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <ul class="links">
                        <li>
                            <div class="action-list godropdown">
                                <select id="store_filters" class="process go-dropdown-toggle">
                                    <option value="">{{ __('All Stores') }} </option>
                                    @foreach ($storesList as $store)
                                        <option value='{{ $store->id }}'>{{ $store->domain }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @include('includes.admin.partials.banner-tabs')
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
                                        <th><i class="icofont-ui-image icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Featured Image') }}'></i></th>
                                        <th>{{ __('Link') }}</th>
                                        <th>{{ __('Updated at') }}</th>
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
                    <p class="text-center">{{ __('You are about to delete this Banner.') }}</p>
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

    {{-- DELETE MODAL ENDS --}}
@endsection

@section('scripts')
    {{-- DATA TABLE --}}

    <script type="text/javascript">
        // Number of rows selected
        var qtde = 0;

        // Defined table reset manually
        function tableRowCountReset() {
            qtde = 0;
            sessionStorage.setItem("CurrentPage", 0);
        }

        var table = $('#geniustable').DataTable({
            stateSave: true,
            stateDuration: -1,
            ordering: false,
            stateLoadParams: function(settings, data) {
                // Persist Store filter selection based on SessionStorage
                var selectedStore = sessionStorage.getItem('SelectedStoreFilter');
                $("#store_filters").val(selectedStore);

            },
            stateDuration: -1,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-sb-datatables', 'TopSmall') }}',
            columns: [{
                    data: 'action',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'photo',
                    name: 'photo',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'link',
                    name: 'link'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                },
                {
                    data: 'store',
                    name: 'store_id',
                    searchable: true,
                    visible: false
                }
            ],
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
            },
            drawCallback: function(settings) {
                $('#geniustable_length').on('change', function() {
                    tableRowCountReset();
                    table.ajax.reload();
                });
            },
            initComplete: function(settings, json) {
                $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                    '<a class="add-btn" data-href="{{ route('admin-sb-create') }}" data-header="{{ __('Add New Banner') }}" id="add-data" data-toggle="modal" data-target="#modal1">' +
                    '<i class="fas fa-plus"></i> {{ __('Add New Banner') }}' +
                    '</a>' +
                    '</div>'
                );
                /*
                 * If any of the store filters are changed, the table resets completely.
                 * It also updates current SelectedStoreFilter into Session Storage, which is used to
                 * keep the selection until user leaves the scope.
                 */
                $("#store_filters").on('change', function() {
                    tableRowCountReset();
                    table.column('store_id:name').search(this.value).draw();
                    sessionStorage.setItem('SelectedStoreFilter', $(this).val());
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#store_filters').niceSelect();


            // First access - CurrentPage
            if (sessionStorage.getItem("CurrentPage") == undefined) {
                sessionStorage.setItem("CurrentPage", 0);
            }

            // First access - SelectedStoreFilter
            if (sessionStorage.getItem('SelectedStoreFilter') == undefined) {
                sessionStorage.setItem('SelectedStoreFilter', $("#store_filters").val());
            } else table.column('store_id:name').search(sessionStorage.getItem("SelectedStoreFilter")).draw();

            $(document).on('click', 'a', function(e) {
                sessionStorage.setItem('SelectedStoreFilter', $("#store_filters").val());
                var link = jQuery(this);
                if (!(link.attr("data-href") || link.attr("href").indexOf("#") > -1 || link.attr("href")
                        .indexOf("javascript") > -1)) {
                    sessionStorage.setItem("CurrentPage", 0);
                    sessionStorage.setItem('SelectedStoreFilter', $("#store_filters").find("option:first")
                        .val());
                    table.state.clear();
                }
                if (link.attr("href").indexOf("banner")) {
                    sessionStorage.setItem("CurrentPage", 0);
                    sessionStorage.setItem('SelectedStoreFilter', $("#store_filters").find("option:first")
                        .val());
                    table.state.clear();
                }
            });
        });
    </script>
    {{-- DATA TABLE ENDS --}}
@endsection
