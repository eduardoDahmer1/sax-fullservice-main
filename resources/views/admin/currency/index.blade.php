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

        #geniustableBase_filter,
        #geniustableBase_length,
        #geniustableBase_info,
        #geniustableBase_paginate {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Currencies') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-currency-index') }}">{{ __('Currencies') }}</a>
                        </li>
                        @if (config('features.multistore'))
                            <li>
                                <div class="action-list godropdown">
                                    <select id="store_filter" class="process select go-dropdown-toggle">
                                        @foreach ($stores as $store)
                                            <option
                                                value="{{ route('admin-stores-isconfig', ['id' => $store['id'], 'redirect' => true]) }}"
                                                {{ $store['id'] == $admstore->id ? 'selected' : '' }}>{{ $store['domain'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div>
            @include('includes.admin.form-success')
        </div>
        <div class="card">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        <h4 class="heading">{{ __('Base Currency') }}</h4>
                        <p class="sub-heading">({{ __('Base currency for all calculations made in the store') }})</p>
                        <div class="table-responsiv">
                            <table id="geniustableBase" class="table table-hover dt-responsive" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th><i class="icofont-options icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Options') }}'></i></th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th><i class="icofont-dollar-true icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Sign') }}'></i></th>
                                        <th>{{ __('Value') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        <h4 class="heading">{{ __('Other Currencies') }}</h4>
                        <p class="sub-heading">({{ __('Currencies used for quotation and conversions') }})</p>
                        <div class="table-responsiv">
                            <div class="table-responsiv">
                                <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th><i class="icofont-options icofont-lg" data-toggle="tooltip"
                                                    title='{{ __('Options') }}'></i></th>
                                            <th>{{ __('Name') }} <span
                                                    class="small">({{ __('ISO Currency Code') }})</span>
                                            </th>
                                            <th>{{ __('Description') }}</th>
                                            <th><i class="icofont-dollar-true icofont-lg" data-toggle="tooltip"
                                                    title='{{ __('Sign') }}'></i></th>
                                            <th>{{ __('Quotation') }}</th>
                                            <th>{{ __('Parity') }}</th>
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
                        <p class="text-center">{{ __('You are about to delete this Currency.') }}</p>
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
        <script type="text/javascript">
            var table = $('#geniustableBase').DataTable({
                ordering: false,
                processing: false,
                serverSide: false,
                ajax: '{{ route('admin-currency-datatables-base') }}',
                columns: [{
                        data: 'action',
                    },
                    {
                        data: 'name',
                        name: 'name',
                    }, {
                        data: 'description',
                        name: 'description',
                    }, {
                        data: 'sign',
                        name: 'sign',
                    }, {
                        data: 'value',
                        name: 'value',
                    }
                ],
                language: {
                    url: '{{ $datatable_translation }}',
                    processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
                },
                drawCallback: function(settings) {
                    $(this).find('.select').niceSelect();
                },
            });

            var table = $('#geniustable').DataTable({
                stateSave: true,
                stateDuration: -1,
                ordering: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin-currency-datatables') }}',
                columns: [{
                    data: 'action',
                    searchable: false,
                    orderable: false
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'description',
                    name: 'description'
                }, {
                    data: 'sign',
                    name: 'sign'
                }, {
                    data: 'value',
                    name: 'value'
                }, {
                    data: 'parity',
                    name: 'parity'
                }],
                language: {
                    url: '{{ $datatable_translation }}',
                    processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
                },
                drawCallback: function(settings) {
                    $(this).find('.select').niceSelect();
                },
                initComplete: function(settings, json) {
                    $("#geniustable_wrapper .btn-area").append('<div class="col-sm-4 text-right">' +
                        '<a class="add-btn" data-href="{{ route('admin-currency-create') }}" data-header="{{ __('Add New Currency') }}" id="add-data" data-toggle="modal" data-target="#modal1">' +
                        '<i class="fas fa-plus"></i> {{ __('Add New Currency') }}' +
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

        <script>
            $('document').ready(function() {
                $("#store_filter").niceSelect('update');
            });
            $("#store_filter").on('change', function() {
                window.location.href = $(this).val();
            });
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
    @endsection
