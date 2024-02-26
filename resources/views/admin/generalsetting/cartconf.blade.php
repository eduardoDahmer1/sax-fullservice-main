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
                    <h4 class="heading">{{ __('Cart') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-storeconf') }}">{{ __('Store') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-cartconf') }}">{{ __('Cart') }}</a>
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
        @include('includes.admin.partials.store-tabs')
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form action="{{ route('admin-gs-update') }}" id="geniusform" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row">

                                    <div class="col-lg-4">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Use Cart') }}
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_cart == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-iscart', 1) }}"
                                                        {{ $admstore->is_cart == 1 ? 'selected' : '' }}>
                                                        {{ __('Enable') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-iscart', 0) }}"
                                                        {{ $admstore->is_cart == 0 ? 'selected' : '' }}>
                                                        {{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Guest Checkout') }} <i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block "
                                                    data-placement="top"
                                                    title="{{ __('If this option is not selected, guest must login before checkout') }}"></i>
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->guest_checkout == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-guest', 1) }}"
                                                        {{ $admstore->guest_checkout == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0" value="{{ route('admin-gs-guest', 0) }}"
                                                        {{ $admstore->guest_checkout == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Full Profile Checkout') }} <i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block "
                                                    data-placement="top"
                                                    title="{{ __('If this option is selected, users must complete their profile informations before checkout. Guest Checkout must be disabled to enable this option.') }}"></i>
                                            </h4>
                                            <div class="action-list"
                                                style="cursor: {{ $admstore->guest_checkout ? ' not-allowed;' : 'pointer;' }}">
                                                <select {{ $admstore->guest_checkout ? 'disabled' : '' }}
                                                    class="process select droplinks {{ $admstore->is_complete_profile == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-completeProfile', 1) }}"
                                                        {{ $admstore->is_complete_profile == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-completeProfile', 0) }}"
                                                        {{ $admstore->is_complete_profile == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Cart Abandonment') }}
                                                <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{ __('This option will be available only if Guest Checkout is disabled.') }}"></i></span>
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_cart_abandonment == 1 ? 'drop-success' : 'drop-danger' }}"
                                                    {{ $admstore->guest_checkout ? 'disabled' : '' }}>
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-iscartabandonment', 1) }}"
                                                        {{ $admstore->is_cart_abandonment == 1 ? 'selected' : '' }}>
                                                        {{ __('Enable') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-iscartabandonment', 0) }}"
                                                        {{ $admstore->is_cart_abandonment == 0 ? 'selected' : '' }}>
                                                        {{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Standard Checkout') }}
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_standard_checkout == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-isStandardCheckout', 1) }}"
                                                        {{ $admstore->is_standard_checkout == 1 ? 'selected' : '' }}>
                                                        {{ __('Enable') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-isStandardCheckout', 0) }}"
                                                        {{ $admstore->is_standard_checkout == 0 ? 'selected' : '' }}>
                                                        {{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Simplified Checkout') }}
                                                <i class="icofont-question-circle" data-toggle="tooltip"
                                                    style="display: inline-block " data-placement="top"
                                                    title="{{ __('If this option is active, the user cannot buy the items in a state that you do not allowed it.') }}"></i>
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_simplified_checkout == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-isSimplifiedCheckout', 1) }}"
                                                        {{ $admstore->is_simplified_checkout == 1 ? 'selected' : '' }}>
                                                        {{ __('Enable') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-isSimplifiedCheckout', 0) }}"
                                                        {{ $admstore->is_simplified_checkout == 0 ? 'selected' : '' }}>
                                                        {{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Whole Sale Max Quantity') }} *
                                            </h4>
                                            <input type="number" class="input-field"
                                                placeholder="{{ __('Whole Sale Max Quantity') }}" name="wholesell"
                                                value="{{ $admstore->wholesell }}" required="" min="0">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Simplified Checkout Number') }}
                                            </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Ex: +55(11)9999-9999') }}"
                                                name="simplified_checkout_number"
                                                value="{{ $admstore->simplified_checkout_number }}">
                                        </div>
                                    </div>



                                    <div class="col-lg-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Tax(%)') }} *
                                            </h4>
                                            <input type="text" class="input-field" placeholder="{{ __('Tax(%)') }}"
                                                name="tax" value="{{ $admstore->tax }}" required="">
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
