@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Roles') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-staff-index') }}">{{ __('Staffs') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-role-index') }}">{{ __('Roles') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @include('includes.admin.partials.user-tabs')
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
                                        <th width="50%">{{ __('Permissions') }}</th>
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
                    <p class="text-center">{{ __('You are about to delete this Role.') }}</p>
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
            stateSave: 1,
            stateDuration: -1,
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-role-datatables') }}',
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
                    data: 'section',
                    name: 'section',
                    searchable: false
                }
            ],
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
            },
            drawCallback: function(settings) {
                $(this).find('.select').niceSelect();
            },
            initComplete: function(settings, json) {
                $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                    '<a class="add-btn" href="{{ route('admin-role-create') }}">' +
                    '<i class="fas fa-plus"></i> {{ __('Add New Role') }}' +
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
