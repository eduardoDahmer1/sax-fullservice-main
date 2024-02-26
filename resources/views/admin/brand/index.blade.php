@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="submit-loader">
            <img src="{{ $admstore->adminLoaderUrl }}" alt="">
        </div>
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-6">
                    <h4 class="heading">{{ __('Brands') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-brand-index') }}">{{ __('Brands') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6 col-offset-6 text-right">
                    <button class="add-btn" id="generateThumbnails" href="{{ route('admin-brand-generatethumbnails') }}"><i
                            class="fas fa-sync-alt"></i> {{ __('Update Thumbnails') }}</button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="links">
                        <li>
                            <div class="action-list godropdown">
                                <!-- Brand filter (Active and Inactive) - (With and without products) -->
                                <select id="brands_filters" class="process select go-dropdown-toggle">
                                    @foreach ($filters as $filter => $name)
                                        <option value="{{ route('admin-brand-datatables', $filter) }}">
                                            {{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
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
                                        <th width="20%">{{ __('Name') }}</th>
                                        <th><i class="icofont-basket icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Products') }}'></i></th>
                                        <th><i class="icofont-eye icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Status') }}'></i></th>
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
                    <p class="text-center">{{ __('You are about to delete this Brand.') }}</p>
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
        var table = $('#geniustable').DataTable({
            stateSave: true,
            stateDuration: -1,
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-brand-datatables') }}',
            columns: [{
                    data: 'action',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    searchable: true
                },
                {
                    data: 'products',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'status',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'slug',
                    name: 'slug',
                    visible: false,
                    searchable: true
                }
            ],
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
            },
            drawCallback: function(settings) {
                $(this).find('.select').niceSelect();

                $(".checkboxStatus").on('click', function() {
                    var id = $(this).attr("id").replace("checkbox-status-", "");
                    var name = $(this).attr('name');
                    var status = name.slice(-1);
                    var statusNovo = (status == "0") ? "1" : "0";
                    var nameNew = $(this).attr("name", name.slice(0, -1) + statusNovo);
                    $.ajax({
                        type: 'GET',
                        url: '{{ url('admin/brand/status') }}' + '/' + id + '/' + statusNovo
                    });

                });
            },
            initComplete: function(settings, json) {
                $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                    '<a class="add-btn" data-href="{{ route('admin-brand-create') }}" data-header="{{ __('Add New Brand') }}" id="add-data" data-toggle="modal" data-target="#modal1">' +
                    '<i class="fas fa-plus"></i> {{ __('Add New Brand') }}' +
                    '</a>' +
                    '</div>');
                /*
                 * Setando no Cookie a página atual
                 */
                $("#geniustable").on('page.dt', function() {
                    sessionStorage.setItem("CurrentPage", table.page());
                });
            }
        });
    </script>
    {{-- DATA TABLE ENDS --}}
    <script>
        $(document).ready(function() {
            $("#generateThumbnails").on('click', function(e) {
                e.preventDefault();
                $('#generateThumbnails').prop('disabled', true);
                if (admin_loader == 1) {
                    $('.submit-loader').show();
                }
                $.ajax({
                    type: 'GET',
                    url: $("#generateThumbnails").attr('href'),
                    success: function(data) {
                        $('#generateThumbnails').prop('disabled', false);
                        if (admin_loader == 1) {
                            $('.submit-loader').hide();

                        }
                        if (data.status && !data.alert) {
                            $.notify(data.message, 'success');
                        } else if (data.status && data.alert) {
                            $.notify(data.message, 'info');
                        } else {
                            $.notify(data.message, 'error');
                        }
                        if ((data.errors)) {
                            for (var error in data.errors) {
                                $.notify(data.errors[error], 'error');
                            }
                        }
                    }
                });
            });
            // First access - CurrentPage
            if (sessionStorage.getItem("CurrentPage") == undefined) {
                sessionStorage.setItem("CurrentPage", 0);
            }
            $(document).on('click', 'a[href]', function(e) {
                var link = jQuery(this);
                var x = '{{ Request::route()->getPrefix() }}';
                y = x.split("/");
                if (!(link.attr("data-href") || link.attr("href").indexOf("#") > -1 || link.attr("href")
                        .indexOf("javascript") > -1 || link.attr("href").indexOf(y[1]) > -1)) {
                    sessionStorage.setItem("CurrentPage", 0);
                    table.state.clear();
                }
            });
        });

        function tableRowCountReset() {
                qtde = 0;
                $("#bulk_all").prop('checked', false);
                sessionStorage.setItem("CurrentPage", 0);
                hideButtons();
        }
        function hideButtons() {
                $(".bulkeditbtn").hide();
                $(".bulkremovebtn").hide();
        }
        
        $('#brands_filters').on('change', function() {
            tableRowCountReset();
            sessionStorage.setItem('SelectedCategoriesFilter', $(this).val());
            table.ajax.url(sessionStorage.getItem('SelectedCategoriesFilter')).load();
        });

        function deleteImage(id, target, targetBtn) {
            $.ajax({
                url: '{{ route('admin-brand-delete-image') }}',
                type: 'POST',
                data: {
                    'id': id,
                    'target': target
                },
                success: function(data) {
                    if (data.status) {
                        $('#modal1 .alert-success').show();
                        $('#modal1 .alert-success p').html(data.message);
                        $("#modal1 .modal-body").scrollTop(0);
                        $("#modal1").scrollTop(0);
                        $(targetBtn).parent().parent().parent().find(".img-preview").css({
                            "background": data.noimage
                        })
                    }
                    if ((data.errors)) {
                        for (var error in data.errors) {
                            $.notify(data.errors[error], 'danger');
                        }
                    }
                }
            });
        }
    </script>
@endsection
