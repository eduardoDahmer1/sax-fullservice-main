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
                    <h4 class="heading">{{ __('Store') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-storeconf') }}">{{ __('Store') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-storeconf') }}">{{ __('General') }}</a>
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
        <div class="add-product-content conf-gerais-loja">
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
                                    @if (config('features.lang_switcher'))
                                        <div class="col-xl-3">
                                            <div class="input-form">
                                                <h4 class="heading">
                                                    {{ __('Language selector') }} <i class="icofont-question-circle"
                                                        data-toggle="tooltip" style="display: inline-block "
                                                        data-placement="top"
                                                        title="{{ __('Visitor can select the language') }}"></i> :
                                                </h4>
                                                <div class="action-list">
                                                    <select
                                                        class="process select droplinks {{ $admstore->is_language == 1 ? 'drop-success' : 'drop-danger' }}">
                                                        <option data-val="1" value="{{ route('admin-gs-islanguage', 1) }}"
                                                            {{ $admstore->is_language == 1 ? 'selected' : '' }}>
                                                            {{ __('Activated') }}</option>
                                                        <option data-val="0" value="{{ route('admin-gs-islanguage', 0) }}"
                                                            {{ $admstore->is_language == 0 ? 'selected' : '' }}>
                                                            {{ __('Deactivated') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (config('features.currency_switcher'))
                                        <div class="col-xl-3">
                                            <div class="input-form">
                                                <h4 class="heading">
                                                    {{ __('Currency selector') }} <i class="icofont-question-circle"
                                                        data-toggle="tooltip" style="display: inline-block "
                                                        data-placement="top"
                                                        title="{{ __('Visitor can change the currency') }}"></i>:
                                                </h4>
                                                <div class="action-list">
                                                    <select
                                                        class="process select droplinks {{ $admstore->is_currency == 1 ? 'drop-success' : 'drop-danger' }}">
                                                        <option data-val="1" value="{{ route('admin-gs-iscurrency', 1) }}"
                                                            {{ $admstore->is_currency == 1 ? 'selected' : '' }}>
                                                            {{ __('Activated') }}</option>
                                                        <option data-val="0" value="{{ route('admin-gs-iscurrency', 0) }}"
                                                            {{ $admstore->is_currency == 0 ? 'selected' : '' }}>
                                                            {{ __('Deactivated') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Display quote') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->show_currency_values == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-showcurrencyvalues', 1) }}"
                                                        {{ $admstore->show_currency_values == 1 ? 'selected' : '' }}>
                                                        {{ __('Yes') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-showcurrencyvalues', 0) }}"
                                                        {{ $admstore->show_currency_values == 0 ? 'selected' : '' }}>
                                                        {{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    @if (env('ENABLE_SWITCH_HIGHLIGHT_CURRENCY'))
                                        <div class="col-xl-3">
                                            <div class="input-form">
                                                <h4 class="heading">
                                                    {{ __('Reverse Currency Highlight') }} <i
                                                        class="icofont-question-circle" data-toggle="tooltip"
                                                        style="display: inline-block " data-placement="top"
                                                        title="{{ __('Inverts the currency highlighted in the products') }}"></i>:
                                                </h4>
                                                <div class="action-list">
                                                    <select
                                                        class="process select droplinks {{ $admstore->switch_highlight_currency == 1 ? 'drop-success' : 'drop-danger' }}">
                                                        <option data-val="1"
                                                            value="{{ route('admin-gs-switchCurrenciHighlight', 1) }}"
                                                            {{ $admstore->switch_highlight_currency == 1 ? 'selected' : '' }}>
                                                            {{ __('Yes') }}</option>
                                                        <option data-val="0"
                                                            value="{{ route('admin-gs-switchCurrenciHighlight', 0) }}"
                                                            {{ $admstore->switch_highlight_currency == 0 ? 'selected' : '' }}>
                                                            {{ __('No') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Home Link On Menu') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_home == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-ishome', 1) }}"
                                                        {{ $admstore->is_home == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0" value="{{ route('admin-gs-ishome', 0) }}"
                                                        {{ $admstore->is_home == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Faq Page') }} :
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_faq == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-isfaq', 1) }}"
                                                        {{ $admstore->is_faq == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0" value="{{ route('admin-gs-isfaq', 0) }}"
                                                        {{ $admstore->is_faq == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Contact Page') }} :
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_contact == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-iscontact', 1) }}"
                                                        {{ $admstore->is_contact == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0" value="{{ route('admin-gs-iscontact', 0) }}"
                                                        {{ $admstore->is_contact == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Blog') }} :
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_blog == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-isblog', 1) }}"
                                                        {{ $admstore->is_blog == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0" value="{{ route('admin-gs-isblog', 0) }}"
                                                        {{ $admstore->is_blog == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Popup Banner') }}:
                                            </h4>

                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_popup == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-ispopup', 1) }}"
                                                        {{ $admstore->is_popup == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0" value="{{ route('admin-gs-ispopup', 0) }}"
                                                        {{ $admstore->is_popup == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Newsletter in Popup') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_newsletter_popup == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-isnewsletterpopup', 1) }}"
                                                        {{ $admstore->is_newsletter_popup == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-isnewsletterpopup', 0) }}"
                                                        {{ $admstore->is_newsletter_popup == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Show Whatsapp button') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_whatsapp == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-iswhatsapp', 1) }}"
                                                        {{ $admstore->is_whatsapp == 1 ? 'selected' : '' }}>
                                                        {{ __('Yes') }}
                                                    </option>
                                                    <option data-val="0" value="{{ route('admin-gs-iswhatsapp', 0) }}"
                                                        {{ $admstore->is_whatsapp == 0 ? 'selected' : '' }}>
                                                        {{ __('No') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Whatsapp Number') }}:
                                            </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Ex: +55(11)9999-9999') }}" name="whatsapp_number"
                                                value="{{ $admstore->whatsapp_number }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Captcha') }} <i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block "
                                                    data-placement="top"
                                                    title="{{ __('Captcha on the sign up') }}"></i>:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_capcha == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-iscapcha', 1) }}"
                                                        {{ $admstore->is_capcha == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0" value="{{ route('admin-gs-iscapcha', 0) }}"
                                                        {{ $admstore->is_capcha == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Sign Up Verification') }} <i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block "
                                                    data-placement="top" title="{{ __('Verification e-mail') }}"></i>:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_verification_email == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-is-email-verify', 1) }}"
                                                        {{ $admstore->is_verification_email == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}</option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-is-email-verify', 0) }}"
                                                        {{ $admstore->is_verification_email == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Show Staff in WhatsApp') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->team_show_whatsapp == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-team-show-whatsapp', 1) }}"
                                                        {{ $admstore->team_show_whatsapp == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-team-show-whatsapp', 0) }}"
                                                        {{ $admstore->team_show_whatsapp == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Show Staff in Header') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->team_show_header == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-team-show-header', 1) }}"
                                                        {{ $admstore->team_show_header == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-team-show-header', 0) }}"
                                                        {{ $admstore->team_show_header == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Brands') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->is_brands == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1" value="{{ route('admin-gs-is-brands', 1) }}"
                                                        {{ $admstore->is_brands == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0" value="{{ route('admin-gs-is-brands', 0) }}"
                                                        {{ $admstore->is_brands == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3">
                                        <div class="input-form">
                                            <h4 class="heading">
                                                {{ __('Show Staff in Footer') }}:
                                            </h4>
                                            <div class="action-list">
                                                <select
                                                    class="process select droplinks {{ $admstore->team_show_footer == 1 ? 'drop-success' : 'drop-danger' }}">
                                                    <option data-val="1"
                                                        value="{{ route('admin-gs-team-show-footer', 1) }}"
                                                        {{ $admstore->team_show_footer == 1 ? 'selected' : '' }}>
                                                        {{ __('Activated') }}
                                                    </option>
                                                    <option data-val="0"
                                                        value="{{ route('admin-gs-team-show-footer', 0) }}"
                                                        {{ $admstore->team_show_footer == 0 ? 'selected' : '' }}>
                                                        {{ __('Deactivated') }}
                                                    </option>
                                                </select>
                                            </div>
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
