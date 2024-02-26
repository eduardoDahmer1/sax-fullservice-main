@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">@lang('Edit Language') <a class="add-btn" href="{{ route('admin-lang-index') }}"><i
                                class="fas fa-arrow-left"></i> @lang('Back')</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">@lang('Dashboard') </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-lang-index') }}">@lang('Languages') </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-lang-edit', $data->id) }}">@lang('Edit Language')</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form id="geniusform" action="{{ route('admin-lang-update', $data->id) }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row">

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">@lang('Language') *</h4>
                                            <p class="input-field">{{ $data->language }}</p>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">@lang('Locale') *</h4>
                                            <p class="input-field">{{ $data->locale }}</p>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form">
                                            <h4 class="heading">@lang('Language Direction') *</h4>
                                            <p class="input-field">
                                                {{ $data->rtl == '0' ? __('Left To Right') : __('Right To Left') }}</p>
                                        </div>
                                    </div>

                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-12">

                                        <div class="py-3 px-3 px-sm-0">

                                            {{-- FIELDS STARTS --}}

                                            <hr>

                                            <h4 class="text-center">@lang('Translations')</h4>

                                            <hr>
                                            <div class="mr-table allproduct">
                                                <div class="table-responsiv">
                                                    <div class="gocover"
                                                        style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                                                    </div>
                                                    <table id="geniustable" class="table table-hover dt-responsive"
                                                        cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:50%;">@lang('Original')</th>
                                                                <th>@lang('Translation')</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($langEdit as $key => $translation)
                                                                @php
                                                                    if (is_numeric($key)):
                                                                        continue;
                                                                    endif;
                                                                @endphp
                                                                <tr>
                                                                    <td>
                                                                        {{ $key }}
                                                                        <input type="hidden"
                                                                            name="fields[{{ $key }}][key]"
                                                                            value="{{ $key }}">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="input-field"
                                                                            name="fields[{{ $key }}][translation]"
                                                                            value="{{ !is_numeric($translation) ? $translation : '' }}">
                                                                    </td>
                                                                    <td>
                                                                        {{ !is_numeric($translation) ? $translation : '' }}
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

                                <div class="row justify-content-center">

                                    <button class="addProductSubmit-btn" type="submit">@lang('Save')</button>

                                </div>
                            </form>
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
            columnDefs: [{
                    "targets": [1],
                    "searchable": false
                },
                {
                    "targets": [2],
                    "visible": false,
                    "searchable": true
                }
            ],
            language: {
                url: '{{ $datatable_translation }}',
                processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
            },
            initComplete: function(settings, json) {
                $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                    '<a class="add-btn" id="btn-empty-search">@lang('Show Empty')</a>' +
                    '<a class="add-btn" id="btn-all-search" style="display: none;">@lang('Show All')</a></div>'
                );
                $('#btn-empty-search').on('click', function() {
                    var regex = "^$";
                    table.column(2).search(regex, true, false).draw();
                    $('#btn-empty-search').css({
                        display: "none"
                    });
                    $('#btn-all-search').css({
                        display: ""
                    });
                });

                $('#btn-all-search').on('click', function() {
                    table.column(2).search("").draw();
                    $('#btn-all-search').css({
                        display: "none"
                    });
                    $('#btn-empty-search').css({
                        display: ""
                    });
                });
            }
        });
    </script>

    {{-- DATA TABLE --}}
@endsection
