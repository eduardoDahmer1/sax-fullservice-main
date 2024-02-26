@extends('layouts.admin')

@push('styles')
    <style media="screen">
        .special-box {
            box-shadow: 0px 1px 6px 0px rgba(208, 208, 208, 0.61);
        }
    </style>
@endpush

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h4 class="heading d-inline-block">
                        {{ __('Manage Attribute') }}
                        <a @if (request()->input('type') == 'category') href="{{ route('admin-cat-index') }}"
                        @elseif (request()->input('type') == 'subcategory')
                        href="{{ route('admin-subcat-index') }}"
                        @elseif (request()->input('type') == 'childcategory')
                        href="{{ route('admin-childcat-index') }}" @endif
                            class="add-btn"><i class="fas fa-angle-left"></i> {{ __('Back') }}</a>
                    </h4>
                    <ul class="links d-inline-block">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            @if (request()->input('type') == 'category')
                                <a href="{{ route('admin-cat-index') }}">{{ __('Categories') }} </a>
                            @endif
                            @if (request()->input('type') == 'subcategory')
                                <a href="{{ route('admin-subcat-index') }}">{{ __('Sub Categories') }} </a>
                            @endif
                            @if (request()->input('type') == 'childcategory')
                                <a href="{{ route('admin-childcat-index') }}">{{ __('Child Categories') }} </a>
                            @endif
                        </li>
                        <li>
                            @if (request()->input('type') == 'category')
                                <a href="{{ route('admin-attr-manage', [$data->id, 'type' => 'category']) }}">{{ __('Manage
                                                            Attribute') }}
                                </a>
                            @endif
                            @if (request()->input('type') == 'subcategory')
                                <a href="{{ route('admin-attr-manage', [$data->id, 'type' => 'subcategory']) }}">{{ __('Manage
                                                            Attribute') }}
                                </a>
                            @endif
                            @if (request()->input('type') == 'childcategory')
                                <a href="{{ route('admin-attr-manage', [$data->id, 'type' => 'childcategory']) }}">{{ __('Manage
                                                            Attribute') }}
                                </a>
                            @endif
                        </li>
                    </ul>

                </div>
            </div>
        </div>

        {{-- Category name --}}

        <div class="row">
            <div class="col-lg-12">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    @if ($type == 'category')
                        <a class="nav-item nav-link active" href="#" role="tab">{{ __('Category Attributes:') }}
                            {{ $data->name }}</a>
                    @endif
                    @if ($type == 'subcategory')
                        <a class="nav-item nav-link active" href="#"
                            role="tab">{{ __('Sub Category Attributes:') }}
                            {{ $data->name }}</a>
                    @endif
                    @if ($type == 'childcategory')
                        <a class="nav-item nav-link active" href="#"
                            role="tab">{{ __('Child Category Attributes:') }}
                            {{ $data->name }}</a>
                    @endif
                </div>
            </div>
        </div>

        {{-- End Category name --}}

        {{-- DataTable --}}


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
                                        <th width=50%>{{ __('Name') }}</th>
                                        <th>{{ __('Options Count') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- DataTable ends --}}
    </div>

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

    {{-- DELETE MODAL --}}

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="confirm-delete"
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
                    <p class="text-center">
                        {{ __('You are about to delete this Attribute. Everything under this attribute will be deleted') }}.
                    </p>
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

    {{-- ATTRIBUTE MODAL --}}

    <div class="modal fade" id="attribute" tabindex="-1" role="dialog" aria-labelledby="attribute" aria-hidden="true">

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

    {{-- ATTRIBUTE MODAL ENDS --}}
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#modal1").on('hidden.bs.modal', function() {
                $(this).find("form").remove();
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
                    if (!(link.attr("data-href") || link.attr("href").indexOf("#") > -1 || link
                            .attr("href").indexOf("javascript") > -1 || link.attr("href").indexOf(y[
                                1]) > -1)) {
                        sessionStorage.setItem("CurrentPage", 0);
                        table.state.clear();
                    }
                });
            });
        });
        var table = $('#geniustable').DataTable({
            stateSave: true,
            stateDuration: -1,
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-attr-datatables', ['id' => request()->route('id'), 'type' => request()->input('type')]) }}',
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
                    data: 'opt_count',
                    name: 'opt_count',
                    searchable: false,
                    orderable: false
                }
            ],
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
            },
            initComplete: function() {
                @if (request()->input('type') == 'category')
                    $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                        '<a data-href={{ route('admin-attr-createForCategory', $data->id) }} data-header="{{ __('Create Attribute') }}"  class="attribute add-btn" data-toggle="modal" data-target="#attribute"> ' +
                        '<i class="fas fa-plus"></i> {{ __('Add New Attribute') }}' +
                        '</a>' +
                        '</div>'
                    );
                @endif
                @if (request()->input('type') == 'subcategory')
                    $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                        '<a data-href={{ route('admin-attr-createForSubcategory', $data->id) }} data-header="{{ __('Create Attribute') }}"  class="attribute add-btn" data-toggle="modal" data-target="#attribute"> ' +
                        '<i class="fas fa-plus"></i> {{ __('Add New Attribute') }}' +
                        '</a>' +
                        '</div>'
                    );
                @endif
                @if (request()->input('type') == 'childcategory')
                    $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                        '<a data-href={{ route('admin-attr-createForChildcategory', $data->id) }} data-header="{{ __('Create Attribute') }}"  class="attribute add-btn" data-toggle="modal" data-target="#attribute"> ' +
                        '<i class="fas fa-plus"></i> {{ __('Add New Attribute') }}' +
                        '</a>' +
                        '</div>'
                    );
                @endif
            }
        });
    </script>
@endsection
