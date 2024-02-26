@extends('layouts.admin')
@section('styles')
    <link href="{{ asset('assets/admin/css/product.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Product Bulk Upload') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Products') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-index') }}">{{ __('All Products') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-import') }}">{{ __('Bulk Upload') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12 p-5">

                    <div class="gocover"
                        style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                    </div>
                    <input type="hidden" name="insertCSV" value="{{ route('admin-prod-importsubmit') }}">
                    <input type="hidden" name="endImport" value="{{ route('admin-prod-endImport') }}">
                    <form id="bulkform" action="{{ route('admin-prod-prepareImport') }}" method="POST"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}

                        @include('includes.admin.form-both-unclose')

                        <div class="row" data-load="hide">
                            <div class="col-lg-12 text-right">
                                <span style="margin-top:10px;"><a class="btn btn-primary"
                                        href="{{ asset('assets/product-csv-format.csv') }}">{{ __('Download Sample CSV') }}</a></span>
                            </div>

                        </div>
                        <hr>
                        <div class="row justify-content-center" data-load="hide">
                            <div class="col-lg-12 d-flex justify-content-center text-center">
                                <div class="csv-icon">
                                    <i class="fas fa-file-csv"></i>
                                </div>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-center text-center">
                                <div class="left-area mr-4">
                                    <h4 class="heading">{{ __('Upload a File') }} *</h4>
                                </div>
                                <span class="file-btn">
                                    <input type="file" id="csvfile" name="csvfile" accept=".csv">
                                </span>

                            </div>
                            <div class="col-lg-12 mt-3 d-flex justify-content-center text-center">
                                <div class="checkbox-wrapper">
                                    <input type="checkbox" name="product_update_check" class="checkclick" id="updateCheck"
                                        value="1">
                                    <label for="updateCheck">{{ __('Update') }}</label>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="type" value="Physical">
                        <div class="row" data-load="hide">
                            <div class="col-lg-12 mt-4 text-center">
                                <button class="mybtn1 mr-5" type="submit">{{ __('Start Import') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('submit', '#bulkform', function(e) {
            e.preventDefault();
            var fd = new FormData(this);

            if (admin_loader == 1) $('.gocover').show();

            if ($('.attr-checkbox').length > 0) {
                $('.attr-checkbox').each(function() {
                    // if checkbox checked then take the value of corresponsig price input (if price input exists)
                    if ($(this).prop('checked') == true) {

                        if ($("#" + $(this).attr('id') + '_price').val().length > 0) {
                            // if price value is given
                            fd.append($("#" + $(this).attr('id') + '_price').data('name'), $("#" + $(this)
                                .attr('id') + '_price').val());
                        } else {
                            // if price value is not given then take 0
                            fd.append($("#" + $(this).attr('id') + '_price').data('name'), 0.00);
                        }

                        // $("#"+$(this).attr('id')+'_price').val(0.00);
                    }
                });
            }

            var bulkform = $(this);
            $('button.addProductSubmit-btn').prop('disabled', true);
            $('[data-load="hide"]').addClass('d-none')

            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: fd,
                contentType: false,
                cache: false,
                processData: false,
                success: (data) => {
                    if ((data.errors)) {
                        bulkform.parent().find('.alert-success').hide();
                        bulkform.parent().find('.alert-danger').show();
                        $('<li>' + data.errors.ref_code + '</li>').prependTo('.alert-danger .text-left')
                    } else {
                        window.fileName = data.fileName;
                        window.insertCount = 0;
                        window.updateCount = 0;
                        window.insertFail = 0;
                        window.message = data.message;
                        insertCSVProduct(0, data.rows)
                    }
                    if (admin_loader == 1) $('.gocover').hide();
                    $('button.addProductSubmit-btn').prop('disabled', false);
                }
            });
        });

        function insertCSVProduct(offset, rows) {
            if (offset < rows) {
                const bulkform = $('#bulkform').parent();

                var fd = new FormData();
                fd.append('_token', $('[name="_token"]').val());
                fd.append('offset', offset);
                fd.append('fileName', window.fileName);
                fd.append('bulk_form', true);
                fd.append('updateCheck', $('#updateCheck:checked').val() ? $('#updateCheck:checked').val() : 0)

                bulkform.find('.alert-success').show();
                bulkform.find('.alert-success p').html(window.message + (offset + 1) + '/' + rows);

                $.ajax({
                        method: "POST",
                        url: $("[name='insertCSV']").val(),
                        data: fd,
                        contentType: false,
                        cache: false,
                        processData: false
                    })
                    .done((data) => {
                        if ((data.errors)) {
                            window.insertFail++;
                            if (data.errors.ref_code) {
                                bulkform.find('.alert-danger').show();
                                $('<li>' + data.errors.ref_code + '</li>').prependTo('.alert-danger .text-left')
                            } else {
                                bulkform.find('.alert-danger').show();
                                $('<li>' + data.errors + '</li>').prependTo('.alert-danger .text-left')
                            }
                        }
                        if (data.bulk_store) window.insertCount++;
                        if (data.bulk_update) window.updateCount++;
                        insertCSVProduct(offset + 1, rows)
                    })
                    .fail((data) => {
                        if (data.responseJSON.error) {
                            bulkform.find('.alert-danger').show();
                            $('<li>' + data.responseJSON.error + '</li>').prependTo('.alert-danger .text-left')
                        } else {
                            const message = 'Erro interno: ' + data.responseJSON.exception + ' - ' + data.responseJSON
                                .message;
                            bulkform.find('.alert-danger').show();
                            $('<li>' + data.errors.ref_code + '</li>').prependTo('.alert-danger .text-left')
                        }
                    })
            } else {
                var fd = new FormData();
                fd.append('_token', $('[name="_token"]').val());
                fd.append('fileName', window.fileName);

                $.ajax({
                        method: 'POST',
                        url: $("[name='endImport']").val(),
                        data: fd,
                        contentType: false,
                        cache: false,
                        processData: false
                    })
                    .done((data) => {
                        const bulkform = $('#bulkform').parent();

                        bulkform.find('.alert-success').show();
                        bulkform.find('.alert-success p').html(data)
                        bulkform.find('.alert-success .insertCount').append(window.insertCount);
                        bulkform.find('.alert-success .updateCount').append(window.updateCount);
                        bulkform.find('.alert-success .errorCount').append(window.insertFail);
                    })
                    .fail((data) => {
                        const message = 'Erro interno: ' + data.responseJSON.exception + ' - ' + data.responseJSON
                            .message;
                        bulkform.find('.alert-danger').show();
                        $('<li>' + data.errors.ref_code + '</li>').prependTo('.alert-danger .text-left')
                    })
            }
        }
    </script>

    <script src="{{ asset('assets/admin/js/product.js') }}"></script>
@endsection
