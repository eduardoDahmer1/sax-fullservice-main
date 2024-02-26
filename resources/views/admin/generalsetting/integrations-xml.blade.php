@extends('layouts.admin')

@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Integrations') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-integrations') }}">{{ __('Integrations') }}</a>
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
                            <div class="row">
                                <div class="col-lg-3">
                                    @include('includes.admin.partials.integration-menu')
                                </div>
                                <div class="col-lg-9">
                                    <form action="{{ route('admin-gs-update') }}" id="geniusform" method="POST"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}

                                        @include('includes.admin.form-both')

                                        {{-- Compras Paraguai --}}
                                        @if (config('features.compras_paraguai'))
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="input-form input-form-center">
                                                        <h4 class="heading">
                                                            {{ __('Compras Paraguai') }}
                                                        </h4>
                                                        <div class="action-list">
                                                            <select
                                                                class="process select droplinks {{ $admstore->is_comprasparaguai == 1 ? 'drop-success' : 'drop-danger' }}">
                                                                <option data-val="1"
                                                                    value="{{ route('admin-gs-iscomprasparaguai', 1) }}"
                                                                    {{ $admstore->is_comprasparaguai == 1 ? 'selected' : '' }}>
                                                                    {{ __('Activated') }}</option>
                                                                <option data-val="0"
                                                                    value="{{ route('admin-gs-iscomprasparaguai', 0) }}"
                                                                    {{ $admstore->is_comprasparaguai == 0 ? 'selected' : '' }}>
                                                                    {{ __('Deactivated') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                @if (config('features.multistore'))
                                                    <div class="col-xl-12">
                                                        <div class="input-form input-form-center">
                                                            <h4 class="heading">
                                                                {{ __('Store') }}
                                                            </h4>
                                                            <div class="action-list godropdown">
                                                                <select id="store_select"
                                                                    class="process select go-dropdown-toggle">
                                                                    @foreach ($stores as $store)
                                                                        <option
                                                                            value="{{ route('admin-gs-storecomprasparaguai', ['id' => $store['id']]) }}"
                                                                            {{ $store['id'] == $admstore->store_comprasparaguai ? 'selected' : '' }}>
                                                                            {{ $store['domain'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-xl-12">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Link to XML Compras Paraguai') }}
                                                        <span>({{ __('Link to XML Facebook Business') }})</span>
                                                    </h4>
                                                    <p>
                                                        <a target="__blank"
                                                            href="{{ asset('assets/files/comprasparaguai.xml') }}">{{ asset('assets/files/comprasparaguai.xml') }}</a>
                                                    </p>
                                                </div>
                                            </div>

                                            <br>
                                            <hr>
                                            <br>
                                        @endif

                                        {{-- Google & Facebook --}}
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="input-form input-form-center">
                                                    <h4 class="heading">
                                                        {{ __('Google & Facebook') }}
                                                    </h4>
                                                    <div class="action-list">
                                                        <select
                                                            class="process select droplinks {{ $admstore->is_lojaupdate == 1 ? 'drop-success' : 'drop-danger' }}">
                                                            <option data-val="1"
                                                                value="{{ route('admin-gs-islojaupdate', 1) }}"
                                                                {{ $admstore->is_lojaupdate == 1 ? 'selected' : '' }}>
                                                                {{ __('Activated') }}</option>
                                                            <option data-val="0"
                                                                value="{{ route('admin-gs-islojaupdate', 0) }}"
                                                                {{ $admstore->is_lojaupdate == 0 ? 'selected' : '' }}>
                                                                {{ __('Deactivated') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            @if (config('features.multistore'))
                                                <div class="col-xl-12">
                                                    <div class="input-form input-form-center">
                                                        <h4 class="heading">
                                                            {{ __('Store') }}
                                                        </h4>
                                                        <div class="action-list godropdown">
                                                            <select id="store_select"
                                                                class="process select go-dropdown-toggle">
                                                                @foreach ($stores as $store)
                                                                    <option
                                                                        value="{{ route('admin-gs-storecomprasparaguai', ['id' => $store['id']]) }}"
                                                                        {{ $store['id'] == $admstore->store_lojaupdate ? 'selected' : '' }}>
                                                                        {{ $store['domain'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-xl-12">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Link') }}
                                                        <span>({{ __('Link to XML Google Shopping') }})</span>
                                                    </h4>
                                                    <p>
                                                        <a target="__blank"
                                                            href="{{ asset('assets/files/googleshopping.xml') }}">{{ asset('assets/files/googleshopping.xml') }}</a>

                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-xl-12">
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Link') }}
                                                        <span>({{ __('Link to XML Facebook Business') }})</span>
                                                    </h4>
                                                    <p>
                                                        <a target="__blank"
                                                            href="{{ asset('assets/files/facebookbusiness.xml') }}">{{ asset('assets/files/facebookbusiness.xml') }}</a>
                                                    </p>
                                                </div>
                                            </div>

                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).on('change', '#store_select', function() {
            var link = $(this).val();
            $.get(link);
            $.notify("{{ __('Data Updated Successfully.') }}", "success");
        });
    </script>
@endsection
