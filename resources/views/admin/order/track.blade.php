@extends('layouts.load')
@section('content')
    {{-- ADD ORDER TRACKING --}}

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        <div class="gocover"
                            style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <input type="hidden" id="track-store" value="{{ route('admin-order-track-store') }}">
                        <form id="trackform" action="{{ route('admin-order-track-store') }}" method="POST"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @include('includes.admin.form-both')

                            <input type="hidden" name="order_id" value="{{ $order->id }}">

                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="input-form">
                                        @component('admin.components.input-localized', ['required' => true, 'type' => 'textarea'])
                                            @slot('name')
                                                title
                                            @endslot
                                            @slot('placeholder')
                                                {{ __('Title') }}
                                            @endslot
                                            {{ __('Title') }} *
                                        @endcomponent
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="input-form">
                                        @component('admin.components.input-localized', ['type' => 'textarea'])
                                            @slot('name')
                                                text
                                            @endslot
                                            @slot('placeholder')
                                                {{ __('Details') }}
                                            @endslot
                                            {{ __('Details') }}
                                        @endcomponent
                                    </div>
                                </div>

                            </div>



                            <div class="row justify-content-center">
                                <button class="addProductSubmit-btn" id="track-btn"
                                    type="submit">{{ __('ADD') }}</button>
                                <button class="addProductSubmit-btn ml=3 d-none" id="cancel-btn"
                                    type="button">{{ __('Cancel') }}</button>
                                <input type="hidden" id="add-text" value="{{ __('ADD') }}">
                                <input type="hidden" id="edit-text" value="{{ __('UPDATE') }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <h5 class="text-center">{{ __('TRACKING DETAILS') }}</h5>

    <hr>

    {{-- ORDER TRACKING DETAILS --}}

    <div class="content-area no-padding">
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">

                            <div class="table-responsive show-table ml-3 mr-3">
                                <table class="table" id="track-load"
                                    data-href={{ route('admin-order-track-load', $order->id) }}>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Details') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Time') }}</th>
                                        <th>{{ __('Options') }}</th>
                                    </tr>
                                    @foreach ($order->tracks as $track)
                                        <tr data-id="{{ $track->id }}">
                                            <td width="30%" class="t-title">
                                                <span data-locale="{{ $lang->locale }}">{{ $track->title }}</span>
                                                @foreach ($locales as $loc)
                                                    @if ($loc->locale === $lang->locale)
                                                        @continue
                                                    @endif
                                                    <span data-locale="{{ $loc->locale }}"
                                                        class="d-none">{{ $track->translate($loc->locale)->title ?? $track->title }}</span>
                                                @endforeach
                                            </td>
                                            <td width="30%" class="t-text">
                                                <span data-locale="{{ $lang->locale }}">{{ $track->text ?? '' }}</span>
                                                @foreach ($locales as $loc)
                                                    @if ($loc->locale === $lang->locale)
                                                        @continue
                                                    @endif
                                                    <span data-locale="{{ $loc->locale }}"
                                                        class="d-none">{{ $track->translate($loc->locale)->text ?? $track->text }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ date('Y-m-d', strtotime($track->created_at)) }}</td>
                                            <td>{{ date('h:i:s:a', strtotime($track->created_at)) }}</td>
                                            <td>
                                                <div class="action-list">
                                                    <a data-href="{{ route('admin-order-track-update', $track->id) }}"
                                                        class="track-edit"> <i
                                                            class="fas fa-edit"></i>{{ __('Edit') }}</a>
                                                    <a href="javascript:;"
                                                        data-href="{{ route('admin-order-track-delete', $track->id) }}"
                                                        class="track-delete"><i class="fas fa-trash-alt"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
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
        // ORDER TRACKING STARTS

        $(document).on('click', '.track-edit', function() {
            $('[name="{{ $lang->locale }}[title]"]').focus();
            var title = $(this).parent().parent().parent().find('.t-title');
            var text = $(this).parent().parent().parent().find('.t-text');

            $('[name="{{ $lang->locale }}[title]"]').val(title.find('[data-locale="{{ $lang->locale }}"]')
        .text());
            $('[name="{{ $lang->locale }}[text]"]').val(text.find('[data-locale="{{ $lang->locale }}"]').text());

            @foreach ($locales as $loc)
                @if ($loc->locale === $lang->locale)
                    @continue
                @endif
                $('[name="{{ $loc->locale }}[title]"]').val(title.find('[data-locale="{{ $loc->locale }}"]')
                    .text());
                $('[name="{{ $loc->locale }}[text]"]').val(text.find('[data-locale="{{ $loc->locale }}"]')
                .text());
            @endforeach

            $('#track-btn').text($('#edit-text').val());
            $('#trackform').prop('action', $(this).data('href'));
            $('#cancel-btn').removeClass('d-none');

        });

        $(document).on('click', '#cancel-btn', function() {

            $(this).addClass('d-none');
            $('#track-btn').text($('#add-text').val());
            $('[name="{{ $lang->locale }}[title]"]').val('');
            $('[name="{{ $lang->locale }}[text]"]').val('');

            @foreach ($locales as $loc)
                @if ($loc->locale === $lang->locale)
                    @continue
                @endif
                $('[name="{{ $loc->locale }}[title]"]').val('');
                $('[name="{{ $loc->locale }}[text]"]').val('');
            @endforeach

            $('#trackform').prop('action', $('#track-store').val());
        });


        $(document).on('click', '.track-delete', function() {
            $(this).parent().parent().parent().remove();
            $.get($(this).data('href'), function(data, status) {
                $('#trackform .alert-success').show();
                $('#trackform .alert-success p').html(data);
            });

        });

        // ORDER TRACKING ENDS
    </script>
@endsection
