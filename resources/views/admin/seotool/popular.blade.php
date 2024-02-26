@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Popular Products') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-popular', $val) }}">{{ __('Popular Products') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('includes.form-error')
                        @include('includes.form-success')
                        <div class="table-responsiv">
                            <table id="example" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th><i class="icofont-mouse icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Clicks') }}'></i></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($productss as $productt)
                                        <tr>

                                            <td>
                                                <a href="{{ route('front.product', $productt->product->slug) }}"
                                                    target="_blank">{{ mb_strlen($productt->product->name, 'utf-8') > 60
                                                        ? mb_substr($productt->product->name, 0, 60, 'utf-8') . '...'
                                                        : $productt->product->name }}
                                                </a>
                                            </td>

                                            <td>
                                                {{ $productt->product->category->name }}
                                            </td>
                                            <td>

                                                {{ $productt->product_count }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('scripts')
    <script type="text/javascript">
        var table = $('#example').DataTable({
            stateSave: true,
            stateDuration: -1,
            ordering: false,
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
            },
            drawCallback: function(settings) {
                $(this).find('.select').niceSelect();
            },
            initComplete: function(settings, json) {
                $(".btn-area").append('<div class="col-sm-4 text-right">' +
                    '<select class="form-control" id="prevdate">' +
                    '<option value="30" {{ $val == 30 ? 'selected' : '' }}>{{ __('Last 30 Days') }}</option>' +
                    '<option value="15" {{ $val == 15 ? 'selected' : '' }}>{{ __('Last 15 Days') }}</option>' +
                    '<option value="7" {{ $val == 7 ? 'selected' : '' }}>{{ __('Last 7 Days') }}</option>' +
                    '</select>' +
                    '</div>');
                $("#prevdate").change(function() {
                    var sort = $("#prevdate").val();
                    window.location = "{{ url('/admin/products/popular/') }}/" + sort;
                });
                /*
                 * Setando no Cookie a página atual
                 */
                $("#example").on('page.dt', function() {
                    sessionStorage.setItem("CurrentPage", table.page());
                });
            }
        });
    </script>
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
                        .indexOf("javascript") > -1 || !(link.attr("href").indexOf("page")))) {
                    sessionStorage.setItem("CurrentPage", 0);
                    table.state.clear();
                }
            });
        });
    </script>
@endsection
