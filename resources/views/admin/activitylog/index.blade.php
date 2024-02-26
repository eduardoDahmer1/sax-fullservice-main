@extends('layouts.admin')

@section('styles')
    <style>
        .mr-table .badge {
            font-weight: normal;
            color: #333;
        }

        .mr-table .badge-danger {
            background-color: #ffbbc2;
        }

        .mr-table .badge-success {
            background-color: #99d5a7;
        }

        .mr-table .badge-info {
            background-color: #97d5df;
        }

        .mr-table .badge-secondary {
            background-color: #d0d0d0;
        }
    </style>
@endsection

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Activity Log') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-activitylog-index') }}">{{ __('Activity Log') }}</a>
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
                                        <th><i class="icofont-calendar icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Date') }}'></i></th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Action') }}</th>
                                        <th>{{ __('Changed') }}</th>
                                        <th>{{ __('Responsible') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- DATA TABLE --}}

    <script type="text/javascript">
        var table = $('#geniustable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-activitylog-datatables') }}',
            columns: [{
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'log_name',
                    name: 'log_name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'subject_id',
                    name: 'subject_id'
                },
                {
                    data: 'causer_id',
                    name: 'causer_id'
                },
            ],
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
            }
        });
    </script>

    {{-- DATA TABLE --}}
@endsection
