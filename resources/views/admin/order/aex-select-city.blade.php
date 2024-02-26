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
              <a href="#">{{ __('Request AEX') }}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="add-product-content">
      <div class="row product-description">
        <div class="col-lg-12 body-area">
          <form id="aex-request-form" action="{{ route('admin-order-request-aex') }}" method="POST"
            enctype="multipart/form-data">
            {{ csrf_field() }}

            @include('includes.form-success')

            @if (!empty($aex_track))
              <div class="alert alert-warning validation">
              <button type="button" class="close alert-close"><span>×</span></button>
                    <ul class="text-left">
                      {{__('This order already has an AEX request')}}
                    </ul>
              </div>
            @endif

            <input type="hidden" name="order_id" value="{{ $order->id }}">
            
            <div class="row justify-content-center">
              <div class="col-lg-9">
                <div class="row justify-content-center">
                  <h4 class="heading">{{ __('Please confirm delivery location') }}</h4>
                </div>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Destination City') }}:</h4>
                </div>
              </div>
              <div class="col-lg-6">
                <select id="aex_destination" name="aex_destination" required>
                  <option value="">{{ __('Select City') }}</option>
                  @foreach ($aex_cities as $city)
                    <option {{ $city->codigo_ciudad == $aex_destination ? 'selected' : '' }}
                      value="{{ $city->codigo_ciudad }}">{{ $city->denominacion }} -
                      {{ $city->departamento_denominacion }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Customer address') }}:</h4>
                </div>
              </div>
              <div class="col-lg-6">
                <span>
                  {{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}
                  -
                  {{ $order->shipping_state == null ? $order->customer_state : $order->shipping_state }}
                  -
                  {{ $order->shipping_country == null ? $order->customer_country : $order->shipping_country }}
                </span>
              </div>
            </div>
            
            <div class="row justify-content-center mt-3">
              <button class="mybtn1" type="submit"><i class="fas fa-shipping-fast"></i>
                {{ __('Confirm') }}
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
 
@endsection
