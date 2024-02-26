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
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Privacy Policy') }} </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Privacy Policy') }} </a>
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
                                style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form id="geniusform" action="{{ route('admin-gs-update') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row">

                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['from' => $gs, 'type' => 'richtext'])
                                                @slot('name')
                                                    privacy_policy
                                                @endslot
                                                @slot('value')
                                                    privacy_policy
                                                @endslot
                                                {{ __('Privacy Policy') }} <i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block " data-placement="top"
                                                    title="{{ __('Text that will appear on the Privacy Policy page') }}"></i>
                                            @endcomponent
                                        </div>
                                    </div>

                                </div>

                                <div class="row justify-content-center">

                                    <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>

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
    <script>
        $('document').ready(function() {
            $("#store_filter").niceSelect('update');
        });

        $("#store_filter").on('change', function() {
            window.location.href = $(this).val();
        });

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@endsection
