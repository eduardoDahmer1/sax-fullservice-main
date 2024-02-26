@extends('layouts.admin')

@section('content')
  <div class="content-area">
    <div class="mr-breadcrumb">
      <div class="row">
        <div class="col-lg-12">
          <h4 class="heading">{{ __('Order Details') }} <a class="add-btn" href="javascript:history.back();">
              <i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
          <ul class="links">
            <li>
              <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
            </li>
            <li>
              <a href="{{ route('admin-order-index') }}">{{ __('Orders') }}</a>
            </li>
            <li>
              <a href="{{ route('admin-order-show', $order->id) }}">{{ __('Order Details') }}</a>
            </li>
            <li>
              <a href="#">{{ __('Request Melhor Envio') }}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="add-product-content">
      <div class="row product-description">
        <div class="col-lg-12 body-area">
          <form id="melhorenvio-request-form" action="{{ route('admin-order-cart-melhorenvio') }}" method="POST"
            enctype="multipart/form-data">
            {{ csrf_field() }}

            @include('includes.form-success')

            @if (count($order->melhorenvio_requests) > 0)
              <div class="alert alert-warning validation">
              <button type="button" class="close alert-close"><span>×</span></button>
                    <ul class="text-left">
                      {{__('This order already has an Melhor Envio request')}}
                    </ul>
              </div>
            @endif

            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="row justify-content-center">
              <div class="col-lg-9">
                <div class="row justify-content-center">
                  <h4 class="heading">{{ __('Melhor Envio Request Information') }}</h4>
                </div>
              </div>
            </div>

            <div class="row justify-content-center mt-5">
              <div class="col-lg-3">
                <div class="left-area">

                </div>
              </div>
              <div class="col-lg-6">
                <div class="">
                  <h4 class="heading">{{ __('From Information') }}
                  </h4>
                </div>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From Name') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="from[name]" class="input-field"
                  value="{{ $melhorenvio_settings->from_name }}"
                  required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From Phone') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="from[phone]" class="input-field"
                  value="{{ $melhorenvio_settings->from_phone }}"
                  {{ $selected_company != 1 ? 'required' : ''}}>
              </div>
            </div>
            
            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From Email') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="from[email]" class="input-field" type="email"
                  value="{{ $melhorenvio_settings->from_email }}">
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From CPF') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="from[document]" class="input-field"
                  value="{{ $melhorenvio_settings->from_document }}">
              </div>
            </div>
            
            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From Company Document') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="from[company_document]" class="input-field"
                  value="{{ $melhorenvio_settings->from_company_document }}"
                  {{ $selected_company != 1 ? 'required' : ''}}>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From State Register') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="from[state_register]" class="input-field" maxlength="15"
                  value="{{ $melhorenvio_settings->from_state_register }}"
                  {{ $selected_company != 1 ? 'required' : ''}}>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From Address') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="from[address]" class="input-field"
                  value="{{ $melhorenvio_settings->from_address }}" required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From Number') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="from[number]" class="input-field"
                  value="{{ $melhorenvio_settings->from_number }}" required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From Complement') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="from[complement]" class="input-field"
                  value="{{ $melhorenvio_settings->from_complement }}">
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From District') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="from[district]" class="input-field"
                  value="{{ $melhorenvio_settings->from_district }}" required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From City') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="from[city]" class="input-field"
                  value="{{ $melhorenvio_settings->from_city }}" required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From State') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <select name="from[state]" id="from_state">
                  <option value="">{{ __('Select State') }}</option>
                  @foreach($states as $state)
                  <option {{$state->initial == $melhorenvio_settings->from_state ? "selected":""}}
                      value="{{ $state->initial }}">{{$state->name}}</option>
                  @endforeach
              </select>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From Country') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <select name="from[country]">
                  <option value="BR">BR</option>
                </select>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('From Note') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="from[note]" class="input-field"
                  value="{{ $melhorenvio_settings->from_note }}">
              </div>
            </div>

            <div class="row justify-content-center mt-5">
              <div class="col-lg-3">
                <div class="left-area">

                </div>
              </div>
              <div class="col-lg-6">
                <div class="">
                  <h4 class="heading">{{ __('Recipient Information') }}
                  </h4>
                </div>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Recipient Name') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="to[name]" class="input-field"
                  value="{{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name }}"
                  required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Recipient Phone') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="to[phone]" class="input-field"
                  value="{{ $order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone }}"
                  {{ $selected_company != 1 ? 'required' : ''}}>
              </div>
            </div>
            
            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Recipient Email') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="to[email]" class="input-field" type="email"
                  value="{{ $order->shipping_email == null ? $order->customer_email : $order->shipping_email }}">
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Recipient CPF') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="to[document]" class="input-field"
                  value="{{ $order->shipping_document == null ? $order->customer_document : $order->shipping_document }}" required>
              </div>
            </div>
            
            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Recipient Address') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="to[address]" class="input-field"
                  value="{{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}" required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Recipient Number') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="to[number]" class="input-field"
                  value="{{ $order->shipping_address_number == null ? $order->customer_address_number : $order->shipping_address_number }}" required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Recipient Complement') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="to[complement]" class="input-field"
                  value="{{ $order->shipping_complement == null ? $order->customer_complement : $order->shipping_complement }}">
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Recipient District') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="to[district]" class="input-field"
                value="{{ $order->shipping_district == null ? $order->customer_district : $order->shipping_district }}" required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Recipient City') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="to[city]" class="input-field"
                  value="{{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}" required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Recipient State') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <select name="to[state]" required>
                  <option value="">{{ __('Select State') }}</option>
                  @php
                    $to_state = $order->shipping_state == null ? $order->customer_state : $order->shipping_state;
                  @endphp
                  @foreach($states as $state)
                  <option {{$state->name == $to_state ? "selected":""}}
                      value="{{ $state->initial }}">{{$state->name}}</option>
                  @endforeach
              </select>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Recipient Country') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <select name="to[country]" required>
                  <option value="BR">BR</option>
                </select>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Recipient Note') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="to[note]" class="input-field"
                  value="">
              </div>
            </div>

            <div class="row justify-content-center mt-5">
              <div class="col-lg-3">
                <div class="left-area">

                </div>
              </div>
              <div class="col-lg-6">
                <div class="">
                  <h4 class="heading">{{ __('Request Information') }}
                  </h4>
                </div>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Non Commercial') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="options[non_commercial]" class="custom-control-input"
                      id="options[non_commercial]" value="1">
                  <label class="custom-control-label" for="options[non_commercial]"></label>
                </div>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Invoice Number') }} (nf-e):
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="options[invoice][number]" class="input-field" id="invoice-number"
                  value="" {{ $selected_company != 1 ? 'required' : ''}}>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Invoice Key') }} (nf-e):
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="options[invoice][key]" class="input-field" id="invoice-key"
                  value="" {{ $selected_company != 1 ? 'required' : ''}}>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Reminder') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="options[reminder]" class="input-field"
                  value="">
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Coupon') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="coupon" class="input-field"
                  value="">
              </div>
            </div>

            <div class="row justify-content-center mt-5">
              <div class="col-lg-3">
                <div class="left-area">

                </div>
              </div>
              <div class="col-lg-6">
                <div class="">
                  <h4 class="heading">{{ __('Content Declaration') }}
                  </h4>
                </div>
              </div>
            </div>
            
            <div id="products">
              @php
                $item_index = 0;    
              @endphp
              @foreach ($cart->items as $item)
              <div id="cart_item[{{$item_index}}]" class="cart-item" data-index="{{$item_index}}">
                <div class="row justify-content-center">
                  <div class="col-lg-3">
                    <div class="left-area">
                      <h4 class="heading">{{ __('Name') }}:
                      </h4>
                    </div>
                  </div>
                  <div class="col-lg-5">
                      <input name="products[{{$item_index}}][name]" class="input-field"
                      value="{{$item['item']['name']}}">
                  </div>
                  <div class="col-lg-1">
                    <button type="button" class="btn btn-danger text-white delete-item"
                      data-index="{{$item_index}}"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="row justify-content-center">
                  <div class="col-lg-3">
                    <div class="left-area">
                      <h4 class="heading">{{ __('Quantity') }}:
                      </h4>
                    </div>
                  </div>
                  <div class="col-lg-1">
                    <input name="products[{{$item_index}}][quantity]" class="input-field" type="number" step="1"
                    value="{{$item['qty']}}">
                  </div>
                  <div class="col-lg-2">
                    <div class="left-area">
                      <h4 class="heading">{{ __('Value') }}(R$):
                      </h4>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <input name="products[{{$item_index}}][unitary_value]" class="input-field" type="number" step="0.01"
                    value="{{round($item['item']['price'] * $curr_brl->value,2)}}">
                  </div>
                  <div class="col-lg-1">
                  </div>
                </div>    
              </div>     
              @php
                  $item_index++;
              @endphp               
              @endforeach
            </div>
            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                </div>
              </div>
              <div class="col-lg-6">
                <button type="button" class="btn btn-success text-white" id="add-item"><i class="fa fa-plus"></i> {{__('Add Item')}}</button>
              </div>
            </div>

            @if ($selected_company == 2)    
            <div class="row justify-content-center mt-5">
              <div class="col-lg-3">
                <div class="left-area">

                </div>
              </div>
              <div class="col-lg-6">
                <div class="">
                  <h4 class="heading">{{ __('JadLog Agency') }}
                  </h4>
                </div>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('State') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <select name="agency_state" required id="agency_state">
                  <option value="">{{ __('Select State') }}</option>
                  @foreach ($jadlog_grouped as $key => $state)
                  <option value="{{$key}}">{{$key}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('City') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <select name="agency_city" required id="agency_city">
                  <option value="">{{ __('Select state first') }}</option>
                </select>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Agency') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <select name="agency" required id="agency">
                  <option value="">{{ __('Select city first') }}</option>
                </select>
              </div>
            </div>
            @endif

            <div class="row justify-content-center mt-3">
              <button class="mybtn1" type="submit"><i class="fas fa-chevron-right"></i>
                {{ __('Next') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Main Content Area End -->
@endsection

@section('scripts')
  <script>
    //Todo: Refactor to JS requests
    var jadlog_agencies = {
      @foreach($jadlog_grouped as $key => $state)
      "{{$key}}":{
        @foreach($state as $city_key => $city)
        "{{$city_key}}":{
          @foreach($city as $agency_key => $agency)
            "{{$agency['id']}}":{
              "id": "{{$agency['id']}}",
              "company_name": "{{$agency['company_name']}}",
              "address": "{{$agency['address']['address']}}",
              "district": "{{$agency['address']['district']}}",
            },
          @endforeach
        },
        @endforeach
      },
      @endforeach
    };
  </script>
  <script>
  @if ($selected_company == 2)  
    $('#agency_state').on('change', function() {
      var stateAbbr = $(this).val();
      var cities = jadlog_agencies[stateAbbr];
      var html = '<option value="">{{ __("Select city") }}</option>';
      Object.keys(cities).forEach(function(key) {
        html += '<option value="'+key+'">'+key+'</option>';
      });
      $("#agency_city").html(html);
      
    });
    $('#agency_city').on('change', function() {
      var stateAbbr = $('#agency_state').val();
      var city = $(this).val();
      var agencies = jadlog_agencies[stateAbbr][city];
      var html = '<option value="">{{ __("Select agency") }}</option>';
      Object.values(agencies).forEach(function(element) {
        html += '<option value="'+element['id']+'">'+element['company_name']+' - '+element['district']+' - '+element['address']+'</option>';
      });
      $("#agency").html(html);
    });
  @endif
    $('#options\\[non_commercial\\]').on('change', function() {
      var selectedCompany = '{{$selected_company}}';
      if($(this).prop("checked")){
        $('#invoice-number').prop('required',false);
        $('#invoice-key').prop('required',false);
      }else if(selectedCompany != '1'){
        $('#invoice-number').prop('required',true);
        $('#invoice-key').prop('required',true);
      }      
    });

    $('#products').on('click', '.delete-item', function(){
      var item_index = $(this).attr('data-index');
      $('#cart_item\\['+item_index+'\\]').remove();
    });
    $('#add-item').on('click', function(){
      var indexes = [];
      $('#products').find('.cart-item').each(function(){
        indexes.push(parseInt($(this).attr('data-index')));
      });
      var new_index = Math.max.apply(Math,indexes) + 1;
      var html = '' +
              '<div id="cart_item['+new_index+']" class="cart-item" data-index="'+new_index+'">' +
                '<div class="row justify-content-center">' +
                  '<div class="col-lg-3">' +
                    '<div class="left-area">' +
                      '<h4 class="heading">{{ __("Name") }}:' +
                      '</h4>' +
                    '</div>' +
                  '</div>' +
                  '<div class="col-lg-5">' +
                      '<input name="products['+new_index+'][name]" class="input-field"' +
                      'value="">' +
                  '</div>' +
                  '<div class="col-lg-1">' +
                    '<button type="button" class="btn btn-danger text-white delete-item"' +
                      'data-index="'+new_index+'"><i class="fa fa-times"></i></button>' +
                  '</div>' +
                '</div>' +
                '<div class="row justify-content-center">' +
                  '<div class="col-lg-3">' +
                    '<div class="left-area">' +
                      '<h4 class="heading">{{ __("Quantity") }}:' +
                      '</h4>' +
                    '</div>' +
                  '</div>' +
                  '<div class="col-lg-1">' +
                    '<input name="products['+new_index+'][quantity]" class="input-field" type="number" step="1"' +
                    'value="">' +
                  '</div>' +
                  '<div class="col-lg-2">' +
                    '<div class="left-area">' +
                      '<h4 class="heading">{{ __("Value") }}(R$):' +
                      '</h4>' +
                    '</div>' +
                  '</div>' +
                  '<div class="col-lg-2">' +
                    '<input name="products['+new_index+'][unitary_value]" class="input-field" type="number" step="0.01"' +
                    'value="">' +
                  '</div>' +
                  '<div class="col-lg-1">' +
                  '</div>' +
                '</div>' +    
              '</div>';
      
      $('#products').append(html);
    });

  </script>
@endsection
