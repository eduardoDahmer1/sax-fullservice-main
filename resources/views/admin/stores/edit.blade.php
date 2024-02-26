@extends('layouts.load')

@section('content')
    <div class="content-area">

        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            @include('includes.admin.form-error')
                            <form id="geniusformdata" action="{{ route('admin-stores-update', $data->id) }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            <p><small>* {{ __('indicates a required field') }}</small></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Website Domain') }} *
                                            </h4>
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Write Your Site Domain Here') }}" name="domain"
                                                value="{{ $data->domain }}" required="">
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['required' => true, 'from' => $data])
                                                @slot('name')
                                                    title
                                                @endslot
                                                @slot('placeholder')
                                                    {{ __('Write Your Site Title Here') }}
                                                @endslot
                                                @slot('value')
                                                    title
                                                @endslot
                                                {{ __('Website Title') }} *
                                            @endcomponent
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Document Name') }} *</h4>
                                            <input type="text" class="input-field" name="document_name"
                                                placeholder="{{ __('RUC / CNPJ / CUIT') }}" required=""
                                                value="{{ $data->document_name = !null ? $data->document_name : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Company Document') }} *</h4>
                                            <input type="text" class="input-field" name="company_document"
                                                placeholder="Number" required=""
                                                value="{{ $data->company_document = !null ? $data->company_document : '' }}">
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
