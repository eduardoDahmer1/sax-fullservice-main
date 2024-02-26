<div class="content-area p-0">
    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area p-0">
                        @include('includes.admin.form-error')
                        <form id="geniusformdata" action="{{route('admin-prod-bulkedit')}}" method="POST"
                            class="form-bulk-edit" enctype="multipart/form-data">

                            <input type="hidden" name="array_id" id="array_id">

                            {{csrf_field()}}
                            @component('admin.components.input-localized')
                            @slot('name')
                            name
                            @endslot
                            @slot('placeholder')
                            {{ __('Enter Product Name') }}
                            @endslot
                            {{ __('Product Name') }}
                            @endcomponent

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Category') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select id="cat" name="category_id">
                                        <option value="">{{ __('Select Category') }}</option>
                                        @foreach($cats as $cat)
                                        <option data-href="{{ route('admin-subcat-load',$cat->id) }}"
                                            value="{{ $cat->id }}">{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Sub Category') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select id="subcat" name="subcategory_id" disabled="">
                                        <option value="">{{ __('Select Sub Category') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Child Category') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select id="childcat" name="childcategory_id" disabled="">
                                        <option value="">{{ __('Select Child Category') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Brand') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select id="brand" name="brand_id">
                                        <option value="">{{ __('Select Brand') }}</option>
                                        @foreach($brands as $brand)
                                        <option data-href="{{ route('admin-brand-load',$brand->id) }}"
                                            value="{{ $brand->id }}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="left-area">
                                        <h4 class="heading">
                                            {{ __('Select Price Change Type') }} <i class="icofont-info-circle icofont-lg" data-toggle="tooltip" title='{{ __("Select the change price type you want to apply for each selected Product. Note that for percentage values you need to insert the percentage to be added/decreased.") }}'></i>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select name="change_price_type" id="change-price-type" required>
                                        <option value="">{{ __("Select a price change type...") }}</option>
                                        <option value="none">{{ __("I do not want to change price") }}</option>
                                        <option value="set_price">{{ __("Set Fixed Price For All") }}</option>
                                        <option value="add_percentage">{{ __("Add Percentage on Price") }}</option>
                                        <option value="decrease_percentage">{{ __("Decrease Percentage on Price") }}</option>
                                        <option value="add_price">{{ __("Add Price") }}</option>
                                        <option value="decrease_price">{{ __("Decrease Price") }}</option>
                                    </select>
                                </div>
                            </div>

                            <div id="change_price_form" style="display: none;">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading" id="changePriceTextLabel">
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input name="price" type="number" class="input-field"
                                            placeholder="{{ __('e.g 20') }}" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col d-flex justify-content-center">
                                    <button class="addProductSubmit-btn" type="submit">{{ __("Save") }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        let changePriceForm = $("#change_price_form");
        let selectChangePrice = $("#change-price-type");

        selectChangePrice.change(function () {
            if($(this).val() === "" || $(this).val() === "none")
            {
                return changePriceForm.hide();
            }
            
            let changePriceText = $("#change-price-type option:selected").html();
            changePriceForm.show();
            $("#changePriceTextLabel").html(changePriceText);
        });
    });
</script>