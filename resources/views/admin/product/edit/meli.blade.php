@extends('layouts.admin')

@section('styles')
    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .delete-button {
            justify-content: end;
            display: flex;
            align-items: baseline;
        }
    </style>

    <link href="{{ asset('assets/admin/css/product.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/jquery.Jcrop.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/Jcrop-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/cropper.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading"> {{ __('Edit Product') }}<a class="add-btn" href="javascript:history.back();"><i
                                class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-index') }}">{{ __('Products') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-prod-edit', $data->id) }}">{{ __('Edit') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @include('includes.admin.form-error')
        @include('includes.admin.form-success')
        @include('includes.admin.partials.product-tabs')
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">

                            <div class="gocover"
                                style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form action="{{ route('admin-prod-update-meli', $data->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                @include('includes.admin.form-both')
                                <div class="title-section-form">
                                    <span>1</span>
                                    <h3>
                                        {{ __('Mercado Livre Main Data') }}
                                    </h3>
                                </div>

                                <div class="row">
                                    <!-- ROW MERCADO LIVRE -->
                                    {{-- Só pode escolher o tipo do anúncio uma vez. --}}
                                    @if (!$data->mercadolivre_id)
                                        <div class="col-12">
                                            <div class="input-form">
                                                <h4 class="heading">Tipo do Anúncio * <i class="icofont-question-circle"
                                                        data-toggle="tooltip" style="display: inline-block "
                                                        data-placement="top"
                                                        title="Escolha o tipo de anúncio a ser criado. Esta escolha pode implicar em encargos, taxas sobre a venda, entre outros. Verifique as informações específicas de cada Tipo de Anúncio na Plataforma Mercado Livre."></i>
                                                </h4>
                                                </h4>
                                                <select name="listing_type_id">
                                                    <option value="">Selecione...</option>
                                                    @foreach ($listingTypesWithDetails as $listingType)
                                                        <option
                                                            {{ $listingType['id'] == $data->mercadolivre_listing_type_id ? 'selected' : '' }}
                                                            value="{{ $listingType['id'] }}">{{ $listingType['name'] }} -
                                                            Exposição:
                                                            {{ $listingType['details']['configuration']->listing_exposure }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-12">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Mercado Livre Name') }} * <i
                                                    class="icofont-question-circle" data-toggle="tooltip"
                                                    style="display: inline-block " data-placement="top"
                                                    title="{{ __('Name to be shown at Mercado Livre Announcement') }}"></i>
                                            </h4>
                                            </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Enter Mercado Livre Name') }}" name="mercadolivre_name"
                                                required="" value="{{ $data->mercadolivre_name }}" maxlength="60">
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="input-form">
                                            <h4 class="heading">Utilizar Preço Personalizado <i
                                                    class="icofont-question-circle" data-toggle="tooltip"
                                                    style="display: inline-block " data-placement="top"
                                                    title="Escolha se este anúncio terá preço personalizado ou não. Dependendo da seleção, o preço base do Produto será utilizado para anunciá-lo.."></i>
                                            </h4>
                                            </h4>
                                            <select name="custom_price">
                                                <option value="0" {{ !$data->mercadolivre_price ? 'selected' : '' }}>
                                                    Não
                                                </option>
                                                <option value="1" {{ $data->mercadolivre_price ? 'selected' : '' }}>
                                                    Sim
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="input-form">
                                            <h4 class="heading">Preço * <i class="icofont-question-circle"
                                                    data-toggle="tooltip" style="display: inline-block "
                                                    data-placement="top"
                                                    title="Preço praticado no anúncio. Este valor sobrescreve o valor praticado do Produto em relação ao Anúncio."></i>
                                            </h4>
                                            </h4>
                                            <input type="number" min="0" step="0.01" class="input-field"
                                                placeholder="Preço do Anúncio..." name="mercadolivre_price" required=""
                                                value="{{ $data->mercadolivre_price ?? $data->price }}"
                                                {{ !$data->mercadolivre_price ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Mercado Livre Description') }} * <i
                                                    class="icofont-question-circle" data-toggle="tooltip"
                                                    style="display: inline-block " data-placement="top"
                                                    title="{{ __('Description to be shown at Mercado Livre Announcement') }}"></i>
                                            </h4>
                                            </h4>
                                            <textarea class="input-field" name="mercadolivre_description" placeholder="{{ __('Enter Mercado Livre Description') }}"
                                                cols="30" rows="10">{{ $data->mercadolivre_description }}</textarea>
                                        </div>
                                    </div>
                                    {{-- Só pode definir a Garantia do Produto uma vez. POR ENQUANTO. --}}
                                    @if ($data->mercadolivre_name)
                                        <input type="hidden" name="warranty_type_name">

                                        <div class="col-12">
                                            <div class="input-form">
                                                <h4 class="heading">Tipo de Garantia <i class="icofont-question-circle"
                                                        data-toggle="tooltip" style="display: inline-block "
                                                        data-placement="top"
                                                        title="Especifique o tipo de Garantia para este Anúncio"></i></h4>
                                                </h4>
                                                <select name="warranty_type_id" required>
                                                    <option value="">Selecione...</option>
                                                    @foreach ($warranties as $warranty)
                                                        @if ($warranty['id'] === 'WARRANTY_TYPE')
                                                            @foreach ($warranty['values'] as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ $value->id === $data->mercadolivre_warranty_type_id ? 'selected' : '' }}
                                                                    data-name="{{ $value->name }}">{{ $value->name }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-form" id="warranty_time_input_form"
                                                style="{{ $data->mercadolivre_without_warranty ? ' display: none; ' : '' }}">
                                                <h4 class="heading">Tempo de Garantia <i class="icofont-question-circle"
                                                        data-toggle="tooltip" style="display: inline-block "
                                                        data-placement="top"
                                                        title="Especifique o tempo de Garantia para este Anúncio"></i></h4>
                                                </h4>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <input type="number" step="1" min="1"
                                                            max="" class="input-field"
                                                            placeholder="Insira a Quantidade" name="warranty_time"
                                                            {{ $data->mercadolivre_without_warranty ? '' : 'required' }}
                                                            maxlength="4"
                                                            value="{{ $data->mercadolivre_warranty_time }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <select name="warranty_time_unit"
                                                            {{ $data->mercadolivre_without_warranty ? '' : 'required' }}>
                                                            <option value="">Selecione...</option>
                                                            @foreach ($warranties as $warranty)
                                                                @if ($warranty['id'] === 'WARRANTY_TIME')
                                                                    @foreach ($warranty['allowed_units'] as $allowedUnit)
                                                                        <option value="{{ $allowedUnit->id }}"
                                                                            {{ $allowedUnit->id === $data->mercadolivre_warranty_time_unit ? 'selected' : '' }}>
                                                                            {{ $allowedUnit->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($data->mercadolivre_name)
                                        <div class="col-xl-12">
                                            @if (!empty($extraData['meli_category_attributes']))
                                                <div class="input-form">
                                                    <h4 class="heading">{{ __('Datasheet') }} * <i
                                                            class="icofont-question-circle" data-toggle="tooltip"
                                                            style="display: inline-block " data-placement="top"
                                                            title="Dados variados da Ficha Técnica, dependendo da Categoria do Produto."></i>
                                                    </h4>
                                                    </h4>
                                                    <div class="row">
                                                        @foreach ($extraData['meli_category_attributes'] as $key => $attribute)
                                                            <div class="col-xl-3">

                                                                {{-- Título --}}
                                                                <h4 class="heading"
                                                                    style="font-weight:bold;padding-top:1rem;">
                                                                    {{ $attribute->name }}
                                                                    @if (isset($attribute->tooltip))
                                                                        <i class="icofont-question-circle"
                                                                            data-toggle="tooltip"
                                                                            style="display: inline-block "
                                                                            data-placement="top"
                                                                            title="{!! $attribute->tooltip !!}"></i>
                                                                    @endif
                                                                    @if (isset($attribute->hint))
                                                                        <br><small
                                                                            style="font-style: italic; font-size: 70%">
                                                                            {{ $attribute->hint }}</small>
                                                                    @endif
                                                                </h4>

                                                                {{-- Dados --}}
                                                                @if (isset($attribute->values))
                                                                    <select
                                                                        name="mercadolivre_category_attributes[{{ $key }}][{{ $attribute->name }}][value]">
                                                                        <option value="">Selecione...</option>
                                                                        @foreach ($attribute->values as $attributeValue)
                                                                            <option value="{{ $attributeValue->id }}"
                                                                                {{ $attributeValue->id === $attribute->value ? 'selected' : '' }}>
                                                                                {{ $attributeValue->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @elseif(isset($attribute->allowed_units))
                                                                    <div class="row">
                                                                        <div class="col-xl-6">
                                                                            <input type="text" class="input-field"
                                                                                placeholder="{{ __('Enter') . ' ' . $attribute->name }}"
                                                                                name="mercadolivre_category_attributes[{{ $key }}][{{ $attribute->name }}][value]"
                                                                                value="{{ $attribute->value }}">
                                                                        </div>
                                                                        <div class="col-xl-6">
                                                                            <select
                                                                                name="mercadolivre_category_attributes[{{ $key }}][{{ $attribute->name }}][allowed_unit_selected]">
                                                                                <option value="">Selecione...
                                                                                </option>
                                                                                @foreach ($attribute->allowed_units as $allowedUnit)
                                                                                    <option value="{{ $allowedUnit->id }}"
                                                                                        {{ isset($allowedUnit->selected) ? ($allowedUnit->selected ? 'selected' : '') : '' }}>
                                                                                        {{ $allowedUnit->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <input
                                                                        maxlength="{{ isset($attribute->value_max_length)
                                                                            ? $attribute->value_max_length
                                                                            : "
                                                                                                                            255" }}"
                                                                        type="text" class="input-field"
                                                                        placeholder="{{ __('Enter') . ' ' . $attribute->name }}"
                                                                        name="mercadolivre_category_attributes[{{ $key }}][{{ $attribute->name }}][value]"
                                                                        value="{{ $attribute->value }}">
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-xl-6 text-center">
                                        <div class="row justify-content-left">
                                            <div class="col-lg-12 d-flex justify-content-between">
                                                <label class="control-label"
                                                    for="update_check">{{ $data->mercadolivre_id ? 'Atualizar' : 'Enviar' }}
                                                    Anúncio ao Salvar</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="update_check" id="update_check"
                                                        value="">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-12 text-center">
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

    <div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Image Gallery') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="top-area">
                        <div class="row">
                            <div class="col-sm-6 text-right">
                                <div class="upload-img-btn">
                                    <form method="POST" enctype="multipart/form-data" id="form-gallery">
                                        {{ csrf_field() }}
                                        <input type="hidden" id="pid" name="product_id" value="">
                                        <input type="file" name="gallery[]" class="hidden" id="uploadgallery"
                                            accept="image/*" multiple>
                                        <label for="image-upload" id="prod_gallery"><i
                                                class="icofont-upload-alt"></i>{{ __('Upload File') }}</label>
                                    </form>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <a href="javascript:;" class="upload-done" data-dismiss="modal"> <i
                                        class="fas fa-check"></i> {{ __('Done') }}</a>
                            </div>
                            <div class="col-sm-12 text-center">(
                                <small>{{ __('You can upload multiple Images.') }}</small>
                                )</div>
                        </div>
                    </div>
                    @if (empty($ftp_gallery))
                        <div class="gallery-images">
                            <div class="selected-image">
                                <div class="row">

                                </div>
                            </div>
                        </div>
                    @else
                        <div class="gallery-images">
                            <div class="">
                                <div class="row">
                                    @foreach ($ftp_gallery as $ftp_image)
                                        @if ($ftp_image != $data->photo)
                                            <div class="col-sm-6">
                                                <div class="img gallery-img">
                                                    <a href="{{ $ftp_image }}">
                                                        <img src="{{ $ftp_image }}" alt="gallery image">
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Remove White Space
        function isEmpty(el) {
            return !$.trim(el.html())
        }
        // Remove White Space Ends
    </script>

    <script>
        $(document).ready(function() {
            $('select[name=custom_price]').change(function() {
                let input = $('input[name=mercadolivre_price]');
                console.log($(this).val());
                if ($(this).val() === "1") {
                    input.removeAttr('disabled');
                    return;
                }

                input.val("{{ $data->price }}");
                input.attr('disabled', true);
            });
        });
    </script>

    <script src="{{ asset('assets/admin/js/jquery.SimpleCropper.js') }}"></script>
    <script src="{{ asset('assets/admin/js/cropper.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery-cropper.js') }}"></script>

    <script>
        $(document).ready(function() {
            let html =
                `<img src="{{ filter_var($data->photo, FILTER_VALIDATE_URL) ? $data->photo : asset('storage/images/products/' . $data->photo) }}" alt="">`;
            $('.span4.cropme').html(html);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
        $('.ok').on('click', function() {
            setTimeout(
                function() {
                    var img = $('#feature_photo').val();
                    $.ajax({
                        url: '{{ route('admin-prod-upload-update', $data->id) }}',
                        type: 'POST',
                        data: {
                            'image': img
                        },
                        success: function(data) {
                            if (data.status) {
                                $('#feature_photo').val(data.file_name);
                            }
                            if ((data.errors)) {
                                for (var error in data.errors) {
                                    $.notify(data.errors[error], 'danger');
                                }
                            }
                        }
                    });
                }, 1000);
        });
    </script>

    <script>
        $('#imageSource').on('change', function() {
            var file = this.value;
            if (file == 'file') {
                $('#f-file').show();
                $('#f-link').hide();
            }
            if (file == 'link') {
                $('#f-file').hide();
                $('#f-link').show();
            }
        });

        function deleteImage(id) {
            $.ajax({
                url: '{{ route('admin-prod-delete-img') }}',
                type: 'POST',
                data: {
                    'id': id
                },
                success: function(data) {
                    if (data.status) {
                        $.notify(data.message, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 300);
                    }
                    if ((data.errors)) {
                        for (var error in data.errors) {
                            $.notify(data.errors[error], 'danger');
                        }
                    }
                }
            });
        }
    </script>

    {{-- MELI SCRIPTS --}}
    <script>
        $(document).ready(function() {
            let withoutWarrantyId = "{{ $withoutWarrantyId }}";

            $('select[name=warranty_time_unit]').change(function() {
                let warrantyTimeUnit = $(this).val();
                let inputWarrantyTime = $('input[name=warranty_time]');

                if (warrantyTimeUnit === "dias")
                    inputWarrantyTime.attr('max', '180');

                if (warrantyTimeUnit === "meses")
                    inputWarrantyTime.attr('max', '72');

                if (warrantyTimeUnit === "anos")
                    inputWarrantyTime.attr('max', '10');
            });

            $('select[name=warranty_type_id]').change(function() {
                if (!withoutWarrantyId) {
                    console.log("ERROR: Without Warranty ID is not defined.");
                    return;
                }

                let warrantyTypeName = $('select[name=warranty_type_id] option:selected').attr('data-name');

                $('input[name=warranty_type_name]').val(warrantyTypeName);

                if ($(this).val() != withoutWarrantyId && $(this).val() !== '') {
                    $('#warranty_time_input_form').fadeIn();

                    $('input[name=warranty_time]').attr('disabled', false);
                    $('input[name=warranty_time]').attr('required', true);

                    $('select[name=warranty_time_unit]').attr('disabled', false);
                    $('select[name=warranty_time_unit]').attr('required', true);
                    return;
                }

                // Desabilita os campos warranty_time e warranty_time_unit se não houver Garantia.
                $('input[name=warranty_time]').attr('disabled', true);
                $('input[name=warranty_time]').attr('required', false);

                $('select[name=warranty_time_unit]').attr('disabled', true);
                $('select[name=warranty_time_unit]').attr('required', false);
                $('#warranty_time_input_form').fadeOut();
            });
        });
    </script>

    <script src="{{ asset('assets/admin/js/product.js') }}"></script>
@endsection
