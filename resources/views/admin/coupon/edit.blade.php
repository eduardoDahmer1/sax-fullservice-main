@extends('layouts.load')

@section('styles')

<link href="{{asset('assets/admin/css/jquery-ui.css')}}" rel="stylesheet" type="text/css">

@endsection

@section('content')

<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('admin-coupon-update',$data->id)}}" method="POST"
              enctype="multipart/form-data">
              {{csrf_field()}}
              <div class="row">
                <div class="col-xl-12">
                    <div class="input-form">
                        <p><small>* {{ __("indicates a required field") }}</small></p>
                    </div>
                </div>
            </div>

              <div class="row">

                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Code') }} *</h4>
                    <input type="text" class="input-field" name="code" placeholder="{{ __('Enter Code') }}" required=""
                    value="{{$data->code}}">
                  </div>
                </div>

                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Type') }} *</h4>
                    <select id="type" name="type" required="">
                      <option value="">{{ __('Choose a type') }}</option>
                      <option value="0" {{$data->type == 0 ? "selected":""}}>{{ __('Discount By Percentage') }}</option>
                      <option value="1" {{$data->type == 1 ? "selected":""}}>{{ __('Discount By Amount') }}</option>
                    </select>
                  </div>
                </div>

                <div class="col-xl-4 hidden">
                  <div class="input-form">
                    <h4 class="heading"></h4>
                    <input type="text" class="input-field less-width" name="price" placeholder="" required=""
                    value="{{$data->price}}">
                  </div>
                </div>

                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Quantity') }} *</h4>
                    <select id="times" required="">
                      <option value="0" {{$data->times == null ? "selected":""}}>{{ __('Unlimited') }}</option>
                      <option value="1" {{$data->times != null ? "selected":""}}>{{ __('Limited') }}</option>
                    </select>
                  </div>
                </div>

                <div class="col-xl-4 hidden">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Value') }} *</h4>
                    <input type="text" class="input-field less-width" name="times" placeholder="{{ __('Enter Value') }}"
                    value="{{$data->times}}">
                  </div>
                </div>

                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Start Date') }} *</h4>
                    <input type="text" class="input-field" name="start_date" id="from"
                    placeholder="{{ __('Select a date') }}" required="" value="{{$data->start_date}}">
                  </div>
                </div>

                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Minimum Value') }} </h4>
                    <input type="text" class="input-field less-width" name="minimum_value" placeholder="{{ __('Enter minimum value') }}"
                    value="{{$data->minimum_value}}">
                  </div>
                </div>

                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Maximum Value') }} </h4>
                    <input type="text" class="input-field less-width" name="maximum_value" placeholder="{{ __('Enter maximum value') }}"
                    value="{{$data->maximum_value}}">
                  </div>
                </div>

                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('End Date') }} *</h4>
                    <input type="text" class="input-field" name="end_date" id="to" placeholder="{{ __('Select a date') }}"
                    required="" value="{{$data->end_date}}">
                  </div>
                </div>

                <div class="col-xl-4 select-div">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Modelos de desconto') }} *</h4>
                    <select id="discount_type" name="discount_type" required>
                      <option value="" disabled selected hidden>{{ __('Selecione uma opção') }}</option>
                      <option value="1" @selected($data->discount_type == 1)>{{ __('Categoria') }}</option>
                      <option value="2" @selected($data->discount_type == 2)>{{ __('Marca') }}</option>
                    </select>
                  </div>
                </div>

                <div class="col-xl-4" id="category" style="display:none;">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Select Category') }} *</h4>
                    <select id="cat" name="category_id" >
                      <option value="">{{ __('Selecione uma opção') }}</option>
                      @foreach ($cats as $cat)
                        <option data-href="{{ route('admin-subcat-load', $cat->id) }}"
                                value="{{ $cat->id }}"
                                {{ $cat->id == $data->category_id ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-xl-4" id="brands" style="display:none;">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Brand') }} *</h4>
                    <select id="brand" name="brand_id" >
                      <option value="">{{ __('Selecione uma opção') }}</option>
                      @foreach ($brands as $brand)
                            <option data-href="{{ route('admin-brand-load', $brand->id) }}"
                                value="{{ $brand->id }}"
                                {{ $brand->id == $data->brand_id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                  </div>
                </div>
      

              </div> <!--FECHAMENTO TAG ROW-->

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

{{-- Coupon Function --}}
<script type="text/javascript">
  (function() {
    var val = $('#type').val();
    var selector = $('#type').parent().parent().next();
    if (val == "") {
      selector.hide();
    } else {
      if (val == 0) {
        selector.find('.heading').html('{{ __('Percentage') }} *');
        selector.find('input').attr("placeholder", "{{ __('Enter Percentage') }}").next().html('%');
        selector.css('display', 'flex');
      } else if (val == 1) {
        selector.find('.heading').html('{{ __('Amount') }} *');
        selector.find('input').attr("placeholder", "{{ __('Enter Amount') }}").next().html('$');
        selector.css('display', 'flex');
      }
    }
  })();
</script>

{{-- Coupon Type --}}
<script>
  $(document).ready(function() {
    var discountType = {{ $data->discount_type}}
    if(discountType == 1 ){
      $('#category').show();  
    }else if(discountType == 2){
      $('#brands').show();  
    }
  });
  $('#type').on('change', function() {
    var val = $(this).val();
    var selector = $(this).parent().parent().next();
    if (val == "") {
      selector.hide();
    } else {
      if (val == 0) {
        selector.find('.heading').html('{{ __('Percentage') }} *');
        selector.find('input').attr("placeholder", "{{ __('Enter Percentage') }}").next().html('%');
        selector.css('display', 'flex');
      } else if (val == 1) {
        selector.find('.heading').html('{{ __('Amount') }} *');
        selector.find('input').attr("placeholder", "{{ __('Enter Amount') }}").next().html('');
        selector.css('display', 'flex');
      }
    }
  });
</script>

{{-- Coupon Qty --}}
<script>
  (function() {
    var val = $("#times").val();
    var selector = $("#times").parent().parent().next();
    if (val == 1) {
      selector.css('display', 'flex');
    } else {
      selector.find('input').val("");
      selector.hide();
    }
  })();

  $(document).on("change", "#times", function() {
    var val = $(this).val();
    var selector = $(this).parent().parent().next();
    if (val == 1) {
      selector.show();
    } else {
      selector.find('input').val("");
      selector.hide();        
    }
  });
  
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#discount_type').on('change', function() {
      var val = $(this).val();
      var selector = $(this).closest('.select-div').next();
      var selector2 = selector.next();
      if (val === "") {
        selector.hide();
        selector2.hide();
      } else {
        if (val == 1) {
          selector2.find('select').val('');
          selector.find('.heading').html('{{ __("Select Category") }} *');
          selector.find('select').attr("placeholder", "{{ __('Select Category') }} *").next();
          selector.css('display', 'flex');
          selector2.css('display', 'none')
        } else if (val == 2) {
          selector.find('select').val('');
          selector2.find('.heading').html('{{ __("Select Brand") }} *');
          selector2.find('select').attr("placeholder", "{{ __('Select Brand') }}").next();
          selector2.css('display', 'flex');
          selector.css('display', 'none');
        }
      }
    });
  });
</script>

<script type="text/javascript">
  var dateToday = new Date();
  var dates = $("#from,#to").datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    changeYear: true,
    minDate: dateToday,
    onSelect: function(selectedDate) {
      var option = this.id == "from" ? "minDate" : "maxDate",
        instance = $(this).data("datepicker"),
        date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat,
          selectedDate, instance.settings);
      dates.not(this).datepicker("option", option, date);
    }
  });
</script>

@endsection