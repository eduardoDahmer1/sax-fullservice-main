@extends('layouts.load')

@section('content')

<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('admin-shipping-create')}}" method="POST"
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

                <div class="col-xl-6">
                  <div class="input-form">
                    @component('admin.components.input-localized',["required" => true])
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
                
                <div class="col-xl-6">
                  <div class="input-form">
                    @component('admin.components.input-localized')
                      @slot('name')
                        subtitle
                      @endslot
                      @slot('placeholder')
                        {{ __('Subtitle') }}
                      @endslot
                        {{ __('Subtitle') }}
                    @endcomponent
                  </div>
                </div>

                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Country') }}
                      <span><i class="icofont-question-circle" data-toggle="tooltip" data-placement="top" title="{{__('Here you must select a country for which this shipping method will be available. If you dont choose any, the shipping method will be available for all countries')}}"></i></span>
                    </h4>
                    <select id="country_id" name="country_id">
                      <option value="">{{ __('Select') }}</option>
                      @foreach($countries as $country)
                      <option value="{{$country->id}}">{{$country->country_name}}</option>
                      @endforeach
                    </select>
                    
                  </div>
                  
                </div>

                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('State') }}
                      <span><i class="icofont-question-circle" data-toggle="tooltip" data-placement="top" title="{{__('Here you must select a state for which this shipping method will be available. If you dont choose any, the shipping method will be available for all states')}}"></i>
                      </span>
                    </h4>
                    <select id="state_id" name="state_id" disabled>
                      <option value="">{{ __('Select Country') }}</option>
                    </select>
                  </div>
                </div>

                <div class="col-xl-4">
                  <div class="input-form">
                    <h4 class="heading">{{ __('City') }}
                      <span><i class="icofont-question-circle" data-toggle="tooltip" data-placement="top" title="{{__('Here you must select a city for which this shipping method will be available. If you dont choose any, the shipping method will be available for all cities')}}"></i></span>
                    </h4>
                    <select id="city_id" name="city_id" disabled>
                      <option value="">{{ __('Select State') }}</option>
                    </select>
                    
                  </div>
                </div>

                <div class="col-xl-12">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Shipping by Region') }}
                      <span><i class="icofont-question-circle" data-toggle="tooltip" data-placement="top" title="{{__('Here you must select whether this shipping method is working by Region or not.')}}"></i></span>
                    </h4>
                    <div class="col-lg-12 text-center">
                    <label class="switch"><input type="checkbox"class="checkbox-region" value="0"><span class="slider round"></span></label>
                    <input type="hidden" name="is_region" id="is_region">
                    </div>
                  </div>
                </div>

                <div class="col-xl-6 region">
                  <div class="input-form">
                    <h4 class="heading">{{ __('ZIP Code Start') }}
                    </h4>
                    <input name="cep_start" type="text" class="input-field cep" placeholder="{{ __('e.g 01000-000') }}">
                  </div>
                </div>

                <div class="col-xl-6 region">
                  <div class="input-form">
                    <h4 class="heading">{{ __('ZIP Code End') }}
                    </h4>
                    <input name="cep_end" type="text" class="input-field cep" placeholder="{{ __('e.g 01000-000') }}">
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Price') }}
                      <span>({{ __('In') }} {{ $sign->name }})</span>
                    </h4>
                    <input name="price" type="number" class="input-field" placeholder="{{ __('e.g 20') }}" value=""
                    min="0" step="0.01">
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Shipping Type') }} *
                      <span>({{ __('In') }} {{ $sign->name }})</span>
                    </h4>
                    <select name="shipping_type" id="shipping_type" required>
                      <option value="">{{ __('Select') }}</option>
                      <option value="Free" id="free">{{ __('Free') }}</option>
                      <option value="Fixed Price" id="fixed_price">{{ __('Fixed Price') }}</option>
                      <option value="Fixed Weight" id="weight_price">{{ __('Weight Price') }}</option>
                      <option value="Percentage Price" id="percentage_price">{{ __('Percentage Price')}}</option>
                    </select>
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="input-form">
                    <div class="alert alert-info">
                      <p>{{__('Free shipping will have no cost, so you have to insert 0 in the price field.')}} </p>
                      <p>{{__('Fixed price will have a standard value.')}} </p> 
                      <p>{{__('Price by weight calculates the value according to the weight informed in the product registration.')}}</p>
                    </div>
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="input-form">
                    <h4 class="heading"> {{ __('Price to Get Free Shipping') }}
                      <span>({{ __('In') }} {{ $sign->name }})</span>
                      <span><i class="icofont-question-circle" data-toggle="tooltip" data-placement="top" title="{{__('Here you must select a value that when you reach it in the total amount of the purchase, the freight will have no cost.')}}"></i></span>
                    </h4>
                    <input name="price_free_shipping" type="number" class="input-field" placeholder="{{ __('e.g 20') }}"
                    step="0.01" min="0">
                    
                  </div>
                </div>

                <div class="col-xl-12">
                  <div class="input-form">
                    @component('admin.components.input-localized')
                        @slot('name')
                            delivery_time
                        @endslot
                        @slot('placeholder')
                        {{ __('(10 - 12 days)') }}
                        @endslot
                        {{ __('Estimated Delivery Time') }}
                    @endcomponent
                  </div>
                </div>

              </div> <!--FECHAMENTO TAG ROW-->
              
   

             

              <div class="row justify-content-center">
    
                  <button class="addProductSubmit-btn" type="submit">{{ __('Create') }}</button>
               
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
  $(document).ready(function(){
          $('.region').hide();

          var url_atual = '<?php echo URL::to('/'); ?>';

          $('#country_id').change(function(){
              var country_id = $(this).val();
              $('#state_id').html('');
              $('#city_id').html('');
              $.post( url_atual + '/admin/shipping_prices/getStates', {
                country_id : country_id }, function(data){
                      $('#state_id').html(data);
                      $('#state_id').removeAttr('disabled');
              });
          });

          $('#state_id').change(function(){
              var state_id = $(this).val();
              $('#city_id').html('');
              $.post( url_atual + '/admin/shipping_prices/getStates', {
                state_id : state_id }, function(data){
                      $('#city_id').html(data);
                      $('#city_id').removeAttr('disabled');
              });
          });

          $(".checkbox-region").change(function(){
              if($(this).is(":checked")){
                $($('#is_region')).val("1");
                $('.region').fadeIn();
              } else{
                $($('#is_region')).val("0");
                $('.cep').val("");
                $('.region').fadeOut();
              }
          });

      });

</script>
@endsection