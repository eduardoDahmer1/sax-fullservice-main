@extends('layouts.load')

@section('content')
<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('admin-shipping_prices-update',$data->id)}}" method="POST"
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
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading">{{ __('Shipping') }} *</h4>
                  </div>
                </div>
                <div class="col-sm-7">
                  <select id="shipping_id" name="shipping_id">
                    @foreach($shippings as $shipping)
                    <option value="{{ $shipping->id }}" {{$shipping->id == $data->shipping_id ? "selected":""}}>
                      {{$shipping->title}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading">{{ __('Country') }} *</h4>
                  </div>
                </div>
                <div class="col-sm-7">
                  <select id="country_id" name="country_id" required>
                    <option value="">{{ __('Select') }}</option>
                    @foreach($countries as $country)
                    @if (empty($gs->country_ship) || $gs->country_ship == $country->country_code)
                    <option value="{{ $country->id }}" {{$country->id == $data->country_id ? "selected":""}}>
                      {{ $country->country_name }} </option>
                    @endif
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading">{{ __('State') }}</h4>
                  </div>
                </div>
                <div class="col-sm-7">
                  <select id="state_id" name="state_id">
                    <option value="">{{ __('Select State') }}</option>
                    @foreach($states as $state)
                    <option value="{{ $state->id }}" {{$state->id == $data->state_id ? "selected":""}}>
                      {{ $state->name }} </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading">{{ __('City') }}</h4>
                  </div>
                </div>
                <div class="col-sm-7">
                  <select id="city_id" name="city_id">
                    <option value="">{{ __('Select City') }}</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{$city->id == $data->city_id ? "selected":""}}> {{ $city->name }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading">{{ __('Price') }}</h4>
                    <p class="sub-heading">({{ __('In') }} {{ $sign->name }})</p>
                  </div>
                </div>
                <div class="col-lg-7">
                  <input type="number" class="input-field" name="price" placeholder="{{ __('Price') }}"
                    value="{{$data->price}}" min="0" step="0.01" required>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading"> {{ __('Price to Get Free Shipping') }} </h4>
                    <p class="sub-heading">({{ __('In') }} {{ $sign->name }})</p>
                  </div>
                </div>
                <div class="col-lg-7">
                  <input name="price_free_shipping" type="number" class="input-field" placeholder="{{ __('e.g 20') }}"
                    step="1" value="{{$data->price_free_shipping}}" min="0">
                </div>
              </div>

              @component('admin.components.input-localized', ["from" => $data])
                  @slot('name')
                      delivery_time
                  @endslot
                  @slot('placeholder')
                  {{ __('(10 - 12 days)') }}
                  @endslot
                  @slot('value')
                      delivery_time
                  @endslot
                  {{ __('Estimated Delivery Time') }} *
              @endcomponent

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading">{{ __('Status') }}</h4>
                  </div>
                </div>
                <ul class="list">
                  <li>
                    <input class="" name="status" type="checkbox" id="status" value="1"
                      {{ ($data->status != null) ? 'checked' : ''}}>
                    <label for="status">{{ __('Active') }}</label>
                  </li>
                </ul>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">

                  </div>
                </div>
                <div class="col-lg-7">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
  // ----------------------------------------------- ready on document

      // ----------------------------------------------- on change

      $(document).ready(function(){

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
          
      });             

</script>

@endsection