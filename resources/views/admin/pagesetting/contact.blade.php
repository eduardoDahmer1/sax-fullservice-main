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
                    <h4 class="heading">{{ __('Contact Us Page') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-ps-contact') }}">{{ __('Contact Us Page') }}</a>
                        </li>
                        @if (config('features.multistore'))
                            <li>
                                <div class="action-list godropdown">
                                    <select id="store_filter" class="process select go-dropdown-toggle">
                                        @foreach ($stores as $store)
                                            <option
                                                value="{{ route('admin-stores-isconfig', ['id' => $store['id'], 'redirect' => true]) }}"
                                                {{ $store['id'] == $admstore->pagesettings->id ? 'selected' : '' }}>
                                                {{ $store['domain'] }}
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
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form id="geniusform" action="{{ route('admin-ps-update') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row">

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['from' => $admstore->pagesettings, 'type' => 'richtext'])
                                                @slot('name')
                                                    contact_title
                                                @endslot
                                                @slot('value')
                                                    contact_title
                                                @endslot
                                                {{ __('Contact Title') }}
                                            @endcomponent
                                        </div>
                                    </div>
                                    <!--FECHAMENTO TAG COL-XL-12 -->

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['from' => $admstore->pagesettings, 'type' => 'richtext'])
                                                @slot('name')
                                                    contact_text
                                                @endslot
                                                @slot('value')
                                                    contact_text
                                                @endslot
                                                {{ __('Contact Text') }}
                                            @endcomponent
                                        </div>
                                    </div>
                                    <!--FECHAMENTO TAG COL-XL-12 -->

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['from' => $admstore->pagesettings, 'type' => 'richtext'])
                                                @slot('name')
                                                    contact_success
                                                @endslot
                                                @slot('value')
                                                    contact_success
                                                @endslot
                                                {{ __('Contact Form Success Text') }}
                                            @endcomponent
                                        </div>
                                    </div>
                                    <!--FECHAMENTO TAG COL-XL-6 -->

                                    <div class="col-xl-6">

                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Email') }}
                                            </h4>
                                            <input type="email" class="input-field" placeholder="{{ __('Enter Email') }}"
                                                name="email" value="{{ $admstore->pagesettings->email }}">
                                        </div>

                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Website') }}
                                            </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Enter Website') }}" name="site"
                                                value="{{ $admstore->pagesettings->site }}">
                                        </div>

                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Phone') }}
                                            </h4>
                                            <input type="text" class="input-field" placeholder="{{ __('Enter Phone') }}"
                                                name="phone" value="{{ $admstore->pagesettings->phone }}">
                                        </div>

                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Street Address') }}
                                            </h4>
                                            <div class="tawk-area">
                                                <textarea name="street" placeholder="{{ __('Enter Street Address') }}"> {{ $admstore->pagesettings->street }} </textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Contact Us Email Address') }}
                                            </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Contact Us Email Address') }}" name="contact_email"
                                                value="{{ $admstore->pagesettings->contact_email }}">
                                        </div>

                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Fax') }}
                                            </h4>
                                            <input type="text" class="input-field" placeholder="{{ __('Enter Fax') }}"
                                                name="fax" value="{{ $admstore->pagesettings->fax }}">
                                        </div>
                                    </div>


                                </div>
                                <!--FECHAMENTO TAG ROW-->


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
    </script>
@endsection
