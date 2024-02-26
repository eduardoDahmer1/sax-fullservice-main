@extends('layouts.vendor')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('My Withdraws') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('vendor-dashboard') }}">{{ __('Dashbord') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('vendor-wt-index') }}">{{ __('My Withdraws') }}</a>
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
                                                title='{{ __('Withdraw Date') }}'></i></th>
                                        <th><i class="icofont-dollar icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Amount') }}'></i></th>
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
            ajax: '{{ route('vendor-withdraw-datatables') }}',
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $gs->adminLoaderUrl }}">'
            },
            columns: [{
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'status',
                    name: 'status'
                }
            ],
            drawCallback: function(settings) {
                $(this).find('.select').niceSelect();
            },
            initComplete: function(settings, json) {
                $(".btn-area").append('<div class="col-sm-4 text-right">' +
                    '<a class="add-btn" href="{{ route('vendor-wt-create') }}">' +
                    '<i class="fas fa-plus"></i> {{ __('Withdraw Now') }}' +
                    '</a>' +
                    '</div>');
            }
        });
    </script>

    {{-- DATA TABLE --}}
@endsection
