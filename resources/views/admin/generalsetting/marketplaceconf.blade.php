@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">Marketplace</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">Configurações</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-storeconf') }}">Marketplace</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @include('includes.admin.partials.marketplaceconf-tabs')
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form action="{{ route('admin-gs-update') }}" id="geniusform" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <div class="row justify-content-center">

                                    <div class="col-xl-4">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Affilate Service') }}
                                            </h4>

                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_affilate == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-isaffilate', 1) }}"
                                                        {{ $admstore->is_affilate == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-isaffilate', 0) }}"
                                                        {{ $admstore->is_affilate == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="input-form input-form-center">
                                            <h4 class="heading">
                                                {{ __('Vendor Registration') }} :
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $gs->reg_vendor == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-regvendor', 1) }}"
                                                        {{ $gs->reg_vendor == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-regvendor', 0) }}"
                                                        {{ $gs->reg_vendor == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Withdraw Fee') }}
                                                <span>{{ __('In') }} {{ $curr->name }}</span>
                                            </h4>
                                            <input name="withdraw_fee" type="number" class="input-field"
                                                placeholder="{{ __('Withdraw Fee') }}" step="1" min="0"
                                                value="{{ $admstore->withdraw_fee }}" required>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Withdraw Charge') }}
                                                <span>%</span>
                                            </h4>
                                            <input name="withdraw_charge" type="number" class="input-field"
                                                placeholder="{{ __('Withdraw Charge') }}" step="1" min="0"
                                                max="100" value="{{ $admstore->withdraw_charge }}" required>
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
