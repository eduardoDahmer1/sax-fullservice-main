@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Better Rated Products') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-rated') }}">{{ __('Better Rated Products') }}</a>
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
                                        <th><i class="icofont-star icofont-lg" data-toggle="tooltip"
                                                title='{{ __('Rating') }}'></i></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($productss as $prod)
                                        <tr>

                                            <td>
                                                <a href="{{ route('front.product', $prod->slug) }}"
                                                    target="_blank">{{ mb_strlen($prod->name, 'utf-8') > 60 ? mb_substr($prod->name, 0, 60, 'utf-8') . '...' : $prod->name }}
                                                </a>
                                            </td>

                                            <td>
                                                {{ $prod->category->name }}
                                            </td>
                                            <td>

                                                {{ $prod->ratings_qty }}
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
                $("#prevdate").change(function() {
                    var sort = $("#prevdate").val();
                    window.location = "{{ url('/admin/products/rated/') }}/" + sort;
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
