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
                    <h4 class="heading">{{ __('Melhor Envio Shipping') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-shipping-index') }}">{{ __('Shipping Methods') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-melhorenvioconf') }}">{{ __('Melhor Envio Shipping') }}</a>
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
        @include('includes.admin.partials.shipping-tabs')
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <div class="submit-loader">
                                <img src="{{ $gs->adminLoaderUrl }}" alt="">
                            </div>
                            <form action="{{ route('admin-gs-update-melhorenvio') }}" id="geniusform" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')

                                <input type="hidden" name="id" value="{{ $admstore->melhorenvio->id }}">

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Token') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <textarea rows="6" name="token" class="input-field" required="">{{ $admstore->melhorenvio->token }}</textarea>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                <span><i class="icofont-question-circle" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{ __('Turns on real shipping. Disable it to test.') }}"></i></span>
                                                {{ __('Production Mode') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select
                                                class="process select droplinks {{ $admstore->melhorenvio->production == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                    value="{{ route('admin-gs-melhorenvio-production', 1) }}"
                                                    {{ $admstore->melhorenvio->production == 1 ? 'selected' : '' }}>
                                                    {{ __('Activated') }}</option>
                                                <option data-val="0"
                                                    value="{{ route('admin-gs-melhorenvio-production', 0) }}"
                                                    {{ $admstore->melhorenvio->production == 0 ? 'selected' : '' }}>
                                                    {{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Calculate Insurance') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="d-flex">
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->melhorenvio->insurance ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-melhorenvio-insurance', 1) }}"
                                                        {{ $admstore->melhorenvio->insurance ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-melhorenvio-insurance', 0) }}"
                                                        {{ $admstore->melhorenvio->insurance == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                            <div class="alert alert-info ml-2 mb-0 p-2">
                                                {{ __('If the total order amount is greater than the maximum insurance amount, the service will
                                                                                                                      not be shown') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Calculate Receipt') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select
                                                class="process select droplinks {{ $admstore->melhorenvio->receipt ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                    value="{{ route('admin-gs-melhorenvio-receipt', 1) }}"
                                                    {{ $admstore->melhorenvio->receipt ? 'selected' : '' }}>
                                                    {{ __('Activated') }}</option>
                                                <option data-val="0"
                                                    value="{{ route('admin-gs-melhorenvio-receipt', 0) }}"
                                                    {{ $admstore->melhorenvio->receipt == 0 ? 'selected' : '' }}>
                                                    {{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Calculate Own Hand') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select
                                                class="process select droplinks {{ $admstore->melhorenvio->ownhand ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                    value="{{ route('admin-gs-melhorenvio-ownhand', 1) }}"
                                                    {{ $admstore->melhorenvio->ownhand ? 'selected' : '' }}>
                                                    {{ __('Activated') }}</option>
                                                <option data-val="0"
                                                    value="{{ route('admin-gs-melhorenvio-ownhand', 0) }}"
                                                    {{ $admstore->melhorenvio->ownhand == 0 ? 'selected' : '' }}>
                                                    {{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Collect not working on Melhor Envio yet --}}
                                {{-- <div class="row justify-content-center">
                <div class="col-lg-3">
                  <div class="left-area">
                    <h4 class="heading">
                      {{ __('Calculate Collect') }}:
                    </h4>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="action-list">
                    <select
                      class="process select droplinks {{ $admstore->melhorenvio->collect ? 'drop-success' : 'drop-danger' }}">
                      <option data-val="1" value="{{route('admin-gs-melhorenvio-collect',1)}}" {{ $admstore->
                        melhorenvio->collect ? 'selected' : '' }}>
                        {{ __('Activated') }}</option>
                      <option data-val="0" value="{{route('admin-gs-melhorenvio-collect',0)}}" {{ $admstore->
                        melhorenvio->collect == 0 ? 'selected' : '' }}>
                        {{ __('Deactivated') }}</option>
                    </select>
                  </div>
                </div>
              </div> --}}

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From Name') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_name" class="input-field"
                                            value="{{ $admstore->melhorenvio->from_name }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From Phone') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_phone" class="input-field"
                                            value="{{ $admstore->melhorenvio->from_phone }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From Email') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_email" class="input-field"
                                            value="{{ $admstore->melhorenvio->from_email }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From CPF') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_document" class="input-field" type="number"
                                            value="{{ $admstore->melhorenvio->from_document }}">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From Company Document') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_company_document" class="input-field"
                                            value="{{ $admstore->melhorenvio->from_company_document }}">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From State Register') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_state_register" class="input-field" maxlength="15"
                                            value="{{ $admstore->melhorenvio->from_state_register }}">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From Address') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_address" class="input-field"
                                            value="{{ $admstore->melhorenvio->from_address }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From Number') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_number" class="input-field"
                                            value="{{ $admstore->melhorenvio->from_number }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From Complement') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_complement" class="input-field"
                                            value="{{ $admstore->melhorenvio->from_complement }}">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From District') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_district" class="input-field"
                                            value="{{ $admstore->melhorenvio->from_district }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From City') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_city" class="input-field"
                                            value="{{ $admstore->melhorenvio->from_city }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From State') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <select id="from_state" name="from_state">
                                            <option value="">{{ __('Select State') }}</option>
                                            @foreach ($states as $state)
                                                <option
                                                    {{ $state->initial == $admstore->melhorenvio->from_state ? 'selected' : '' }}
                                                    value="{{ $state->initial }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From Country') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <select id="from_country" name="from_country">
                                            <option value="BR">BR</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From Postal Code') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_postal_code" class="input-field"
                                            value="{{ $admstore->melhorenvio->from_postal_code }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('From Note') }}:
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="from_note" class="input-field"
                                            value="{{ $admstore->melhorenvio->from_note }}">
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-5">
                                    <div class="col-lg-3">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="">
                                            <h4 class="heading">{{ __('Avaiable services') }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <div id="companies_area">
                                    @if (count($melhorenvio_companies) == 0)
                                        <div class="row justify-content-center">
                                            <div class="col-lg-3">
                                                <div class="left-area">

                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="">
                                                    {{ __('No companies and services found, please update') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @foreach ($melhorenvio_companies as $company)
                                        <div class="row justify-content-center">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <img src="{{ $company->picture }}" alt="{{ $company->name }}"
                                                        style="max-height:50px; max-width:180px">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row justify-content-left">
                                                    <h4 class="heading ml-3">{{ $company->name }}</h4>
                                                    @foreach ($company->services as $service)
                                                        <div class="col-lg-12 d-flex justify-content-between">
                                                            <label class="control-label"
                                                                for="service{{ $service->id }}">{{ $service->name }} |
                                                                {{ $service->type == 'express' ? __('Express') : __('Normal') }}
                                                                |
                                                                {{ __('Insurance Max:') }}
                                                                R${{ number_format($service->insurance_max, 2, ',', '.') }}
                                                            </label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="selected_services[]"
                                                                    id="service{{ $service->id }}"
                                                                    value="{{ $service->id }}"
                                                                    {{ in_array($service->id, $admstore->melhorenvio->selected_services) ? 'checked' : '' }}>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <a class="mybtn1 btn-info btn-update-melhorenvio-companies"
                                            data-href="{{ route('admin-gs-update-melhorenvio-companies') }}">
                                            <i class="fas fa-sync-alt"></i><span>
                                                {{ __('Update Companies and Services') }}</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                                    </div>
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
    <script>
        $('.btn-update-melhorenvio-companies').on('click', function(e) {
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $.ajax({
                type: "GET",
                url: $(this).attr('data-href'),
                success: function(data) {
                    if ((data.errors)) {
                        $('.alert-success').hide();
                        $('.alert-danger').show();
                        $('.alert-danger p').html(data);
                        for (var error in data.errors) {
                            $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>')
                        }
                        if (admin_loader == 1) {
                            $('.submit-loader').hide();
                        }
                    } else {
                        var companies_link = '{{ route('admin-gs-load-melhorenvio-companies') }}';
                        if (companies_link != '') {
                            $.ajax({
                                type: "GET",
                                url: companies_link,
                                success: function(data_companies) {
                                    let attrHtml = data_companies;
                                    $("#companies_area").html(attrHtml);
                                },
                                complete: function(data_companies) {
                                    $('.alert-danger').hide();
                                    $('.alert-success').show();
                                    $('.alert-success p').html(data);
                                    if (admin_loader == 1) {
                                        $('.submit-loader').hide();
                                    }
                                }
                            });
                        }
                    }
                }
            });
            return false;
        });
    </script>
@endsection
