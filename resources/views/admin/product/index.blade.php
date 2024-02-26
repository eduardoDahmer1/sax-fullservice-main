@extends('layouts.admin')

@section('styles')
    <style>
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
                    <h4 class="heading">
                        {{ __('Products') }}
                    </h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="links">
                                <li>
                                    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin-prod-index') }}">{{ __('Products') }} </a>
                                </li>
                            </ul>
                        </div>
                        @if ($gs->ftp_folder)
                            <div class="col-lg-6 col-offset-6 text-right">
                                <button class="add-btn" id="generateThumbnails"
                                    href="{{ route('admin-prod-btngeneratethumbnails') }}"><i class="fas fa-sync-alt"></i>
                                    {{ __('Update Thumbnails') }}</button>
                            </div>
                        @endif
                    </div>
                    <div class="row">

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="links">
                                <li>
                                    <div class="action-list godropdown">
                                        <select id="product_filters" class="process select go-dropdown-toggle">
                                            @foreach ($filters as $filter => $name)
                                                <option value="{{ route('admin-prod-datatables', $filter) }}">
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                                <li>
                                    <div class="action-list godropdown">
                                        <select id="brand_filters" class="process go-dropdown-toggle">
                                            <option value="">{{ __('All Brands') }}</option>
                                            @foreach ($brands as $brand)
                                                <option value='{{ $brand->id }}'>{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                                <li>
                                    <div class="action-list godropdown">
                                        <select id="category_filters" class="process go-dropdown-toggle">
                                            <option value="">{{ __('All Categories') }}</option>
                                            @foreach ($cats as $category)
                                                <option value='{{ $category->id }}'>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
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
                                <li>
                                    <a class="mybtn1 bulkeditbtn add-btn" data-href="{{ route('admin-prod-bulkedit') }}"
                                        data-header="{{ __('Edit Multiple') }}" data-toggle="modal"
                                        data-target="#bulk_edit_modal" style="display: none;">
                                        <i class="fas fa-edit"></i><span
                                            class="remove-mobile">{{ __('Edit Multiple') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="mybtn1 bulkremovebtn add-btn"
                                        data-href="{{ route('admin-prod-bulkdelete') }}" data-toggle="modal"
                                        data-target="#confirm-bulkdelete" style="display: none;">
                                        <i class="fas fa-trash-alt"></i><span
                                            class="remove-mobile">{{ __('Remove Multiple') }}</span>
                                    </a>
                                </li>
                            </ul>

                            @if ($gs->is_comprasparaguai)
                                <a class="mybtn1 btn-info btn-comprasparaguai add-btn"
                                    data-href="{{ route('admin-prod-comprasparaguai') }}">
                                    <i class="fas fa-sync-alt"></i><span
                                        class="remove-mobile">{{ __('Compras Paraguai') }}</span>
                                </a>
                            @endif

                            @if ($gs->is_lojaupdate)
                                <a class="mybtn1 btn-info btn-comprasparaguai add-btn"
                                    data-href="{{ route('admin-prod-updateloja') }}">
                                    <i class="fas fa-sync-alt"></i><span
                                        class="remove-mobile">{{ __('Google & Facebook') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-area">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="submit-loader">
                            <img src="{{ $gs->adminLoaderUrl }}" alt="">
                        </div>
                        <div class="mr-table allproduct">

                            @include('includes.admin.form-success')
                            @include('includes.admin.form-error')

                            <div class="table-responsiv">
                                <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th width="2%">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" id="bulk_all" name="bulk_all"
                                                        class="custom-control-input" id="bulk_all">
                                                    <label class="custom-control-label" for="bulk_all"></label>
                                                </div>
                                            </th>
                                            <th><i class="icofont-ui-image icofont-lg" data-toggle="tooltip"
                                                    title='{{ __('Photo') }}'></i></th>
                                            <th width="15%"><i class="icofont-options icofont-lg" data-toggle="tooltip"
                                                    title='{{ __('Options') }}'></i></th>
                                            <th width="20%">{{ __('Name') }}</th>
                                            <th >{{ __('Size') }}</th>
                                            <th>{{ __('Tags') }}</th>
                                            <th><i class="fa fa-th-large fa-lg" data-toggle="tooltip"
                                                    title='{{ __('Stock') }}'></i></th>
                                            <th><i class="icofont-dollar icofont-lg" data-toggle="tooltip"
                                                    title='{{ __('Price') }}'></i></th>
                                            <th><i class="icofont-eye icofont-lg" data-toggle="tooltip"
                                                    title='{{ __('Status') }}'></i></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- HIGHLIGHT MODAL --}}

        <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="modal2"
            aria-hidden="true">

            <div class="modal-dialog highlight" role="document">
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
                        <div id="submit-button">

                        </div>
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- HIGHLIGHT ENDS --}}

        {{-- CATALOG MODAL --}}

        <div class="modal fade" id="catalog-modal" tabindex="-1" role="dialog" aria-labelledby="modal1"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __('Update Status') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <p class="text-center">{{ __('You are about to change the status of this Product.') }}</p>
                        <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <a class="btn btn-success btn-ok">{{ __('Proceed') }}</a>
                    </div>

                </div>
            </div>
        </div>

        {{-- CATALOG MODAL ENDS --}}

        {{-- DELETE MODAL --}}

        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1"
            aria-hidden="true">
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
                        <p class="text-center">{{ __('You are about to delete this Product.') }}</p>
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

        {{-- BULK DELETE MODAL --}}

        <div class="modal fade" id="confirm-bulkdelete" tabindex="-1" role="dialog" aria-labelledby="bulkdeletemodal"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __('Confirm Bulk Delete') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <p class="text-center">{{ __('You are about to delete these Products.') }}</p>
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

        {{-- BULK DELETE MODAL ENDS --}}

        {{-- COPY MODAL --}}

        <div class="modal fade" id="confirm-copy" tabindex="-1" role="dialog" aria-labelledby="modal1"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __('Confirm Copy') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <p class="text-center">{{ __('You are about to copy this Product.') }}</p>
                        <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <a class="btn btn-success btn-ok copy-ajax">{{ __('Copy') }}</a>
                    </div>

                </div>
            </div>
        </div>

        {{-- COPY MODAL ENDS --}}

        {{-- GALLERY MODAL --}}

        <div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Image Gallery') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="top-area">
                            <div class="row">
                                <div class="col-sm-6 text-right">
                                    <div class="upload-img-btn">
                                        <form method="POST" enctype="multipart/form-data" id="form-gallery">
                                            {{ csrf_field() }}
                                            <input type="hidden" id="pid" name="product_id" value="">
                                            <input type="file" name="gallery[]" class="hidden" id="uploadgallery"
                                                accept="image/*" multiple>
                                            <label for="image-upload" id="prod_gallery"><i
                                                    class="icofont-upload-alt"></i>{{ __('Upload File') }}</label>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <a href="javascript:;" class="upload-done" data-dismiss="modal"> <i
                                            class="fas fa-check"></i> {{ __('Done') }}</a>
                                </div>
                                <div class="col-sm-12 text-center">(
                                    <small>{{ __('You can upload multiple Images') }}.</small> )
                                </div>
                            </div>
                        </div>
                        <div class="gallery-images">
                            <div class="selected-image">
                                <div class="row">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- GALLERY MODAL ENDS --}}

        {{-- GALLERY MODAL ENDS --}}

        {{-- FASTEDIT MODAL --}}

        <div class="modal fade" id="fast_edit_modal" tabindex="-1" role="dialog" aria-labelledby="modaledit"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="submit-loader">
                        <img src="{{ $gs->adminLoaderUrl }}" alt="">
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
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- FASTEDIT ENDS --}}

        {{-- BULKEDIT MODAL --}}

        <div class="modal fade" id="bulk_edit_modal" tabindex="-1" role="dialog" aria-labelledby="modaledit"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="submit-loader">
                        <img src="{{ $gs->adminLoaderUrl }}" alt="">
                    </div>
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Multiple Edit') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- BULKEDIT ENDS --}}
    @endsection



    @section('scripts')
        <script>
            $('.btn-comprasparaguai').on('click', function(e) {
                if (admin_loader == 1) {
                    $('.submit-loader').show();
                }
                $.ajax({
                    type: "GET",
                    url: $(this).attr('data-href'),
                    success: function(data) {
                        if ((data.errors)) {
                            $('.alert-success').hide();
                            $('.alert-danger').show();
                            $('.alert-danger p').html(data);
                            for (var error in data.errors) {
                                $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>')
                            }
                        } else {
                            $('.alert-danger').hide();
                            $('.alert-success').show();
                            $('.alert-success p').html(data);
                        }
                        if (admin_loader == 1) {
                            $('.submit-loader').hide();
                        }
                    }
                });
                return false;
            });
        </script>
        <script>
            // Fast Edit
            $(document).on('click', '.fasteditbtn', function() {
                if (admin_loader == 1) {
                    $('.submit-loader').show();
                }
                $('#fast_edit_modal').find('.modal-title').html($(this).attr('data-header'));
                $('#fast_edit_modal .modal-content .modal-body').html('').load($(this).attr('data-href'), function(
                    response, status,
                    xhr) {
                    if (status == "success") {
                        if (admin_loader == 1) {
                            $('.submit-loader').hide();
                        }
                    }
                });
            });
        </script>
        <script>
            // Bulk Edit
            $(document).on('click', '.bulkeditbtn', function() {
                if (admin_loader == 1) {
                    $('.submit-loader').show();
                }
                $('#bulk_edit_modal .modal-content .modal-body').html('').load($(this).attr('data-href'), function(
                    response, status,
                    xhr) {
                    if (status == "success") {
                        if (admin_loader == 1) {
                            $('.submit-loader').hide();
                        }
                        $("#array_id").val(
                            $(".product-bulk-check").map(function() {
                                if ($(this).is(':checked')) {
                                    return this.value;
                                }
                            }).get().join()
                        );
                    }
                });
            });
        </script>
        <script>
            //--- Bulk Delete
            $('#confirm-bulkdelete').on('show.bs.modal', function(e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            });
            $('#confirm-bulkdelete .btn-ok').on('click', function(e) {
                if (admin_loader == 1) {
                    $('.submit-loader').show();
                }
                var array_id = $(".product-bulk-check").map(function() {
                    if ($(this).is(':checked')) {
                        return this.value;
                    }
                }).get().join();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: $(this).attr('href'),
                    data: {
                        'array_id': array_id
                    },
                    success: function(data) {
                        if ((data.errors)) {
                            $('.alert-success').hide();
                            $('.alert-danger').show();
                            $('.alert-danger p').html(data);
                            for (var error in data.errors) {
                                $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>')
                            }
                        } else {
                            $('.alert-danger').hide();
                            $('.alert-success').show();
                            $('.alert-success p').html(data);
                        }
                        $('#confirm-bulkdelete').modal('toggle');
                        table.ajax.reload();
                        if (admin_loader == 1) {
                            $('.submit-loader').hide();
                        }
                    }
                });
                return false;
            });
        </script>
        {{-- DATA TABLE --}}


        <script>
            // Defines hide and show functions, using Bulk Edit Buttons
            function hideButtons() {
                $(".bulkeditbtn").hide();
                $(".bulkremovebtn").hide();
            }

            function showButtons() {
                $(".bulkeditbtn").show();
                $(".bulkremovebtn").show();
            }

            // Number of rows selected
            var qtde = 0;

            // Defined table reset manually
            function tableRowCountReset() {
                qtde = 0;
                $("#bulk_all").prop('checked', false);
                sessionStorage.setItem("CurrentPage", 0);
                hideButtons();
            }

            var ajax = '{{ route('admin-prod-datatables', 'all') }}';

            var table = $('#geniustable').DataTable({
                stateSave: true,
                stateLoadParams: function(settings, data) {

                    // Persist Product filter selection based on SessionStorage
                    var selected = sessionStorage.getItem('SelectedProductFilter');
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

                    // Persist Brand filter selection based on SessionStorage
                    var selectedBrand = sessionStorage.getItem('SelectedBrandFilter');
                    $("#brand_filters").val(selectedBrand);

                    // Persist Category filter selection based on SessionStorage
                    var selectedCategory = sessionStorage.getItem('SelectedCategoryFilter');
                    $("#category_filters").val(selectedCategory);

                    // Persist Store filter selection based on SessionStorage
                    var selectedStore = sessionStorage.getItem('SelectedStoreFilter');
                    $("#store_filters").val(selectedStore);

                },
                stateDuration: -1,
                searching: true,
                ordering: false,
                processing: true,
                serverSide: true,
                ajax: ajax,
                columns: [{
                        data: 'bulk',
                        name: 'bulk',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'photo',
                        name: 'photo',
                        render: function(data) {
                            return '<img src="' + data + '" class="avatar" width="50" height="50"/>';
                        }
                    },
                    {
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
                        data: 'product_size',
                        name: 'size',
                        searchable: true
                    },
                    {
                        data: 'features',
                        name: 'features',
                        searchable: true
                    },
                    {
                        data: 'stock',
                        name: 'stock',
                        searchable: false
                    },
                    {
                        data: 'price',
                        name: 'price',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'featured',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'ref_code',
                        name: 'ref_code',
                        searchable: true,
                        visible: false
                    },
                    {
                        data: 'id',
                        name: 'id',
                        searchable: true,
                        visible: false
                    },
                    {
                        data: 'sku',
                        name: 'sku',
                        searchable: true,
                        visible: false
                    },
                    {
                        data: 'brand',
                        name: 'brand_id',
                        searchable: true,
                        visible: false
                    },
                    {
                        data: 'category',
                        name: 'category_id',
                        searchable: true,
                        visible: false
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

                    /*
                     * This function checks whether it has to show or hide "Edit/Remove Many" buttons
                     * considering #bulk_all's state.
                     * It also updates the row count to the maximum, either 10, 25, 50 or 100.
                     */
                    $('#bulk_all').on('change', function() {
                        if ($(this).is(':checked')) {
                            qtde = table.data().count();
                            $('.product-bulk-check').prop('checked', true);
                            if (qtde > 1) showButtons();
                        } else {
                            $('.product-bulk-check').prop('checked', false);
                            hideButtons();
                            qtde = 0;
                        }
                    });

                    /*
                     * This function checks whether it has to show or hide "Edit/Remove Many" buttons
                     * considering the number of rows selected. If the number is greater than 1,
                     * the buttons are showed. It also has a verify if-else to detect whether all
                     * the rows are selected manually or not.
                     * If the row count does not matches maximum row count, #bulkall will be setted to false.
                     * Therefore it covers all of possibilities to row count crash or
                     * keep increasing without treatment.
                     */
                    $(".product-bulk-check").on('click', function() {
                        ($(this).prop("checked") == true) ? qtde++ : qtde--;
                        if (qtde > 1) {
                            showButtons();
                        }
                        if (qtde < table.data().count()) {
                            $("#bulk_all").prop('checked', false);
                            if (qtde < 2) {
                                hideButtons();
                            }
                        } else if (qtde == table.data().count()) {
                            $("#bulk_all").prop('checked', true);
                        }
                    });

                    /*
                     * What happens when Status Switch is changed state.
                     * Cannot be moved outta here
                     */
                    $(".checkboxStatus").on('click', function() {
                        var id = $(this).attr("id").replace("checkbox-status-", "");
                        var name = $(this).attr('name');
                        var status = name.slice(-1);
                        var statusNovo = (status == "0") ? "1" : "0";
                        var nameNew = $(this).attr("name", name.slice(0, -1) + statusNovo);
                        $.ajax({
                            type: 'GET',
                            url: '{{ url('admin/products/status') }}' + '/' + id + '/' + statusNovo
                        });

                    });

                    /*
                     * What happens when Featured Switch is changed state.
                     * Cannot be moved outta here
                     */

                    $(".checkboxFeatured").on('click', function() {
                        var id = $(this).attr("id").replace("checkbox-featured-", "");
                        var name = $(this).attr('name');
                        var featured = name.slice(-1);
                        var featuredNovo = (featured == "0") ? "1" : "0";
                        var nameNew = $(this).attr("name", name.slice(0, -1) + featuredNovo);
                        $.ajax({
                            type: 'GET',
                            url: '{{ url('admin/products/featured') }}' + '/' + id + '/' +
                                featuredNovo
                        });
                    });

                    /*
                     * If the table length is changed, it just resets the table.
                     */
                    $('#geniustable_length').on('change', function() {
                        tableRowCountReset();
                        table.ajax.reload();
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
                     * Appending "Add New Product" button
                     */
                    $('.btn-area').append('<div class="col-sm-4 table-contents">' +
                        '<a class="add-btn" href="{{ route('admin-prod-physical-create') }}">' +
                        '<i class="fas fa-plus"></i> <span class="remove-mobile">{{ __('Add New Product') }}<span>' +
                        '</a>' +
                        '</div>');

                    /*
                     * Setando no Cookie a página atual
                     */
                    $("#geniustable").on('page.dt', function() {
                        sessionStorage.setItem("CurrentPage", table.page());
                    });


                    /*
                     * If any of the product filters are changed, the table resets completely.
                     * It also updates current SelectedProductFilter into Session Storage, which is used to
                     * keep the selection until user leaves the scope.
                     */
                    $('#product_filters').on('change', function() {
                        tableRowCountReset();
                        sessionStorage.setItem('SelectedProductFilter', $(this).val());
                        table.ajax.url(sessionStorage.getItem('SelectedProductFilter')).load();

                    });

                    /*
                     * If any of the brand filters are changed, the table resets completely.
                     * It also updates current SelectedBrandFilter into Session Storage, which is used to
                     * keep the selection until user leaves the scope.
                     */
                    $("#brand_filters").on('change', function() {
                        tableRowCountReset();
                        table.column('brand_id:name').search(this.value).draw();
                        sessionStorage.setItem('SelectedBrandFilter', $(this).val());
                    });

                    /*
                     * If any of the category filters are changed, the table resets completely.
                     * It also updates current SelectedCategoryFilter into Session Storage, which is used to
                     * keep the selection until user leaves the scope.
                     */
                    $("#category_filters").on('change', function() {
                        tableRowCountReset();
                        table.column('category_id:name').search(this.value).draw();
                        sessionStorage.setItem('SelectedCategoryFilter', $(this).val());
                    });

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
                                $.notify("Erro!", 'error');
                            }
                            if ((data.errors)) {
                                for (var error in data.errors) {
                                    $.notify(data.errors[error], 'error');
                                }
                            }
                        }
                    });
                });

                $('#brand_filters').niceSelect();
                $('#product_filters').niceSelect();
                $('#category_filters').niceSelect();
                $('#store_filters').niceSelect();

                /*
                 * First access for SessionStorage. It just checks if it's undefined, then already restores respective status.
                 */

                // First access - CurrentPage
                if (sessionStorage.getItem("CurrentPage") == undefined) {
                    sessionStorage.setItem("CurrentPage", 0);
                }

                // First access - SelectedProductFilter
                if (sessionStorage.getItem('SelectedProductFilter') == undefined) {
                    sessionStorage.setItem('SelectedProductFilter', $("#product_filters").val());
                } else table.ajax.url(sessionStorage.getItem('SelectedProductFilter')).load();
                // First access - SelectedBrandFilter
                if (sessionStorage.getItem('SelectedBrandFilter') == undefined) {
                    sessionStorage.setItem('SelectedBrandFilter', $("#brand_filters").val());
                } else table.column('brand_id:name').search(sessionStorage.getItem("SelectedBrandFilter")).draw();
                // First access - SelectedCategoryFilter
                if (sessionStorage.getItem('SelectedCategoryFilter') == undefined) {
                    sessionStorage.setItem('SelectedCategoryFilter', $("#category_filters").val());
                } else table.column('category_id:name').search(sessionStorage.getItem("SelectedCategoryFilter")).draw();
                // First access - SelectedStoreFilter
                if (sessionStorage.getItem('SelectedStoreFilter') == undefined) {
                    sessionStorage.setItem('SelectedStoreFilter', $("#store_filters").val());
                } else table.column('store_id:name').search(sessionStorage.getItem("SelectedStoreFilter")).draw();



                $(document).on('click', 'a', function(e) {
                    sessionStorage.setItem('SelectedProductFilter', $("#product_filters").val());
                    sessionStorage.setItem('SelectedBrandFilter', $("#brand_filters").val());
                    sessionStorage.setItem('SelectedCategoryFilter', $("#category_filters").val());
                    sessionStorage.setItem('SelectedStoreFilter', $("#store_filters").val());
                    var link = jQuery(this);
                    var x = '{{ Request::route()->getPrefix() }}';
                    y = x.split("/");
                    if (!(link.attr("data-href") || link.attr("href").indexOf("#") > -1 || link.attr("href")
                            .indexOf("javascript") > -1 || link.attr("href").indexOf(y[1]) > -1)) {
                        sessionStorage.setItem("CurrentPage", 0);
                        sessionStorage.setItem('SelectedProductFilter', $("#product_filters").find(
                            "option:first").val());
                        sessionStorage.setItem('SelectedBrandFilter', $("#brand_filters").find("option:first")
                            .val());
                        sessionStorage.setItem('SelectedCategoryFilter', $("#category_filters").find(
                            "option:first").val());
                        sessionStorage.setItem('SelectedStoreFilter', $("#store_filters").find("option:first")
                            .val());
                        table.state.clear();
                    }
                });
            });
        </script>
        <script>
            // Gallery Section Update
            $(document).on('click', '.set-gallery-product', function() {
                var pid = $(this).find('input[type=hidden]').val();
                $('#pid').val(pid);
                $('.selected-image .row').html('');
                $.ajax({
                    type: 'GET',
                    url: '{{ route('admin-gallery-show') }}',
                    data: {
                        id: pid
                    },
                    success: function(data) {
                        if (data[0] == 0) {
                            $('.selected-image .row').addClass('justify-content-center');
                            $('.selected-image .row').html('<h3>{{ __('No Images Found.') }}</h3>');
                        } else {
                            if (data[2]) {
                                $('.selected-image .row').removeClass('justify-content-center');
                                $('.selected-image .row h3').remove();
                                for (var k in data[2]) {
                                    $('.selected-image .row').append('<div class="col-sm-6">' +
                                        '<div class="img gallery-img">' +
                                        '<span class="remove-img"><i class="fas fa-times"></i>' +
                                        '<input type="hidden" value="' + data[2][k]['id'] + '">' +
                                        '</span>' +
                                        '<a href="' + data[2][k] + '" target="_blank">' +
                                        '<img src="' + data[2][k] + '" alt="gallery image">' +
                                        '</a>' +
                                        '</div>' +
                                        '</div>');
                                }
                            }
                        }
                        $('.selected-image .row').removeClass('justify-content-center');
                        $('.selected-image .row h3').remove();
                        var arr = $.map(data[1], function(el) {
                            return el
                        });
                        for (var k in arr) {
                            $('.selected-image .row').append('<div class="col-sm-6">' +
                                '<div class="img gallery-img">' +
                                '<span class="remove-img"><i class="fas fa-times"></i>' +
                                '<input type="hidden" value="' + arr[k]['id'] + '">' +
                                '</span>' +
                                '<a href="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                arr[k][
                                    'photo'
                                ] + '" target="_blank">' +
                                '<img src="' +
                                '{{ asset('storage/images/galleries') . '/' }}' +
                                arr[k][
                                    'photo'
                                ] + '" alt="gallery image">' +
                                '</a>' +
                                '</div>' +
                                '</div>');
                        }
                    }
                });
            });
            $(document).on('click', '.remove-img', function() {
                var id = $(this).find('input[type=hidden]').val();
                $(this).parent().parent().remove();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('admin-gallery-delete') }}',
                    data: {
                        id: id
                    }
                });
            });
            $(document).on('click', '#prod_gallery', function() {
                $('#uploadgallery').click();
            });
            $('#uploadgallery').change(function() {
                $('#form-gallery').submit();
            });
            $(document).on('submit', '#form-gallery', function() {
                $.ajax({
                    url: '{{ route('admin-gallery-store') }}',
                    method: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data != 0) {
                            $('#uploadgallery').val(null);
                            $('.selected-image .row').removeClass('justify-content-center');
                            $('.selected-image .row h3').remove();
                            var arr = $.map(data, function(el) {
                                return el
                            });
                            for (var k in arr) {
                                $('.selected-image .row').append('<div class="col-sm-6">' +
                                    '<div class="img gallery-img">' +
                                    '<span class="remove-img"><i class="fas fa-times"></i>' +
                                    '<input type="hidden" value="' + arr[k]['id'] + '">' +
                                    '</span>' +
                                    '<a href="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                    arr[
                                        k][
                                        'photo'
                                    ] + '" target="_blank">' +
                                    '<img src="' + '{{ asset('storage/images/galleries') . '/' }}' +
                                    arr[
                                        k][
                                        'photo'
                                    ] + '" alt="gallery image">' +
                                    '</a>' +
                                    '</div>' +
                                    '</div>');
                            }
                        }
                    }
                });
                return false;
            });
            // Gallery Section Update Ends
        </script>

        <script>
            // Fix jquery ui datepicker select month and year not working inside ajax modal.
            // Source: https://stackoverflow.com/a/21088713/2465086
            var enforceModalFocusFn = $.fn.modal.Constructor.prototype._enforceFocus;
            $.fn.modal.Constructor.prototype._enforceFocus = function() {};
        </script>
        <script>
            // Fix jquery ui datepicker not initializing in every modal open
            // Source: https://stackoverflow.com/a/25164481/2465086
            $('#modal2').on('hidden.bs.modal', function() {
                $("#ui-datepicker-div").remove();
            });
        </script>
    @endsection
