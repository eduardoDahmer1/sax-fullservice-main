@extends('layouts.load')

@section('content')
    <div class="content-area">

        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            @include('includes.admin.form-error')
                            <form id="geniusformdata" action="{{ route('admin-subscription-create') }}" method="POST"
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
                                            @component('admin.components.input-localized', ['required' => true])
                                                @slot('name')
                                                    title
                                                @endslot
                                                @slot('placeholder')
                                                    {{ __('Enter Subscription Title') }}
                                                @endslot
                                                {{ __('Title') }} *
                                            @endcomponent
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Cost') }} *
                                                <span>({{ __('In') }} {{ $curr->name }})</span>
                                            </h4>
                                            <input type="text" class="input-field" name="price"
                                                placeholder="{{ __('Enter Subscription Cost') }}" required=""
                                                value="">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Days') }} *</h4>
                                            <input type="text" class="input-field" name="days"
                                                placeholder="{{ __('Enter Subscription Days') }}" required=""
                                                value="">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="input-form">
                                            <h4 class="heading">{{ __('Product Limitations') }}*</h4>
                                            <select id="limit" name="limit" required="">
                                                <option value="">{{ __('Select an Option') }}</option>
                                                <option value="0">{{ __('Unlimited') }}</option>
                                                <option value="1">{{ __('Limited') }}</option>
                                            </select>
                                        </div>

                                        <div class="showbox input-form" id="limits">

                                            <h4 class="heading">{{ __('Allowed Products') }} *</h4>
                                            <input type="number" min="1" class="input-field" id="allowed_products"
                                                name="allowed_products" placeholder="{{ __('Enter Allowed Products') }}"
                                                value="1">

                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="input-form">
                                            @component('admin.components.input-localized', ['type' => 'richtext'])
                                                @slot('name')
                                                    details
                                                @endslot
                                                @slot('placeholder')
                                                    {{ __('Details') }}
                                                @endslot
                                                {{ __('Details') }}
                                            @endcomponent
                                        </div>
                                    </div>


                                </div>
                                <!--FECHAMENTO TAG ROW-->





                                <div class="row justify-content-center">

                                    <button class="addProductSubmit-btn" type="submit">{{ __('Create Plan') }}</button>

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
    <script type="text/javascript">
        $("#limit").on('change', function() {
            val = $(this).val();
            if (val == 1) {
                $("#limits").show();
                $("#allowed_products").prop("required", true);
            } else {
                $("#limits").hide();
                $("#allowed_products").prop("required", false);

            }
        });
    </script>
@endsection
