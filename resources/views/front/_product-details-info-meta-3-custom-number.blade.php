@if(env("ENABLE_CUSTOM_PRODUCT_NUMBER"))
<input type="hidden" id="is_customizable_number" name="is_customizable_number"
    value="{{ $productt->category->is_customizable_number }}">
@if($productt->category->is_customizable_number == 1)
<h4 class="customize-title" style="text-transform: uppercase;">
    {{__('Customize your product:')}}</h4>
<div class="mt-4 mb-4 customizable-item">
    <input type="text" class="form-control col-lg-8 mt-2" name="customizable_name" id="customizable_name" value=""
        style="margin-top: -13px;" placeholder="{{ __('Enter your name') }}">
    <input type="number" min="1" max="99" maxlength="2"
        oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null"
        class="form-control col-lg-8 mt-2" name="customizable_number" id="customizable_number" value=""
        style="margin-top: -13px;" placeholder="{{ __('Enter the number') }}">
</div>
@endif
@endif
