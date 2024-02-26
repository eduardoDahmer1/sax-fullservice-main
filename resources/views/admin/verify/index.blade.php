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
    </style>
@endsection

@section('content')
    <input type="hidden" id="headerdata" value="{{ __(' VERIFICATION') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Vendor Verifications') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li><a href="javascript:;">{{ __('Vendor Verifications') }}</a></li>
                        <li>
                            <div class="action-list godropdown">
                                <select id="verification_filters" class="process select go-dropdown-toggle">
                                    @foreach ($filters as $filter => $name)
                                        <option value="{{ route('admin-vr-datatables', $filter) }}">{{ $name }}
                                        </option>
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
                                        <th>{{ __('Vendor Name') }}</th>
                                        <th><i class="icofont-email icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Vendor Email') }}'></i></th>
                                        <th>{{ __('Descriptions') }}</th>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>

    </div>

    {{-- ADD / EDIT MODAL ENDS --}}

    {{-- STATUS MODAL --}}

    <div class="modal fade" id="confirm-delete1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
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
                    <p class="text-center">{{ __('You are about to change the status.') }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-success btn-ok">{{ __('Update') }}</a>
                </div>

            </div>
        </div>
    </div>

    {{-- STATUS MODAL ENDS --}}

    {{-- GALLERY MODAL --}}

    <div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Attachments') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="top-area">
                        <div class="row">
                            <div class="col-sm-12 d-inline-block">

                                <h5> {{ __('Details') }}: <small id="detail"></small></h5>
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
                <div class="modal-footer">

                    <div class="col-sm-6 text-right">
                        <a id="verify-btn" href="javascript:;" class="btn btn-success f-btn"> <i
                                class="fas fa-check"></i>
                            {{ __('Verify') }}</a>
                    </div>
                    <div class="col-sm-6">
                        <a id="decline-btn" href="javascript:;" class="btn btn-danger f-btn"> <i
                                class="fas fa-times"></i>
                            {{ __('Decline') }}</a>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- GALLERY MODAL ENDS --}}
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
            ajax: '{{ route('admin-vr-datatables', 'all') }}',
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
                    data: 'text',
                    name: 'text'
                },
                {
                    data: 'status',
                    searchable: false,
                    orderable: false
                }
            ],
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $gs->adminLoaderUrl }}">'
            },
            drawCallback: function(settings) {
                $(this).find('.select').niceSelect();
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
            // First access - CurrentPage
            if (sessionStorage.getItem("CurrentPage") == undefined) {
                sessionStorage.setItem("CurrentPage", 0);
            }
            $(document).on('click', 'a', function(e) {
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
    </script>

    <script type="text/javascript">
        // Gallery Section Update
        $(document).on("click", ".set-gallery-verify", function() {
            var pid = $(this).find('input[type=hidden]').val();
            $('#pid').val(pid);
            $('.selected-image .row').html('');
            $.ajax({
                type: "GET",
                url: '{{ route('admin-vr-show') }}',
                data: {
                    id: pid
                },
                success: function(data) {
                    $('#detail').html(data[2]);
                    $('#verify-btn').attr('href', data[3]);
                    $('#decline-btn').attr('href', data[4]);
                    if (data[0] == 0) {
                        $('.selected-image .row').addClass('justify-content-center');
                        $('.selected-image .row').html('<h3>{{ __('No Images Found.') }}</h3>');
                    } else {
                        $('.selected-image .row').removeClass('justify-content-center');
                        $('.selected-image .row h3').remove();
                        var arr = $.map(data[1], function(el) {
                            return el
                        });
                        for (var k in arr) {
                            $('.selected-image .row').append('<div class="col-sm-6">' +
                                '<div class="img gallery-img">' +
                                '<a class="img-popup" href="' +
                                '{{ asset('storage/images/attachments') . '/' }}' + arr[k] + '">' +
                                '<img  src="' + '{{ asset('storage/images/attachments') . '/' }}' +
                                arr[k] +
                                '" alt="gallery image">' +
                                '</a>' +
                                '</div>' +
                                '</div>');
                        }
                    }
                    $('.img-popup').magnificPopup({
                        type: 'image'
                    });
                    $(document).off('focusin');
                }
            });
        });
        $('.f-btn').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                success: function(data) {
                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }
                    $('#setgallery').modal('toggle');
                    $('.alert-danger').hide();
                    $('.alert-success').show();
                    $('.alert-success p').html(data[0]);
                    table.ajax.reload();
                }
            });
        });
        // Gallery Section Update Ends
    </script>

    <script>
        $('#verification_filters').on('change', function() {
            table.ajax.url($(this).val()).load();
        });
    </script>
@endsection
