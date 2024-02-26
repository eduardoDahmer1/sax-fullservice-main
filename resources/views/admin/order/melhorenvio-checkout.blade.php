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
          <form id="melhorenvio-request-form" action="{{ route('admin-order-checkout-melhorenvio') }}" method="POST"
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
            <input type="hidden" name="cart_uuid" value="{{ $melhorenvio_cart->id }}">
            
            <div class="row justify-content-center">
              <div class="col-lg-9">
                <div class="row justify-content-center">
                  <h4 class="heading">{{ __('Please confirm purchase') }}</h4>
                </div>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Protocol') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                {{$melhorenvio_cart->protocol}}
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Price') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                {{'R$'.number_format($melhorenvio_cart->price, 2, ',', '.')}}
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Balance') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                {{'R$'.number_format($melhorenvio_balance->balance, 2, ',', '.')}}
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Reserved') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                {{'R$'.number_format($melhorenvio_balance->reserved, 2, ',', '.')}}
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Debts') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                {{'R$'.number_format($melhorenvio_balance->debts, 2, ',', '.')}}
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Avaiable Balance') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                {{'R$'.number_format($melhorenvio_balance->balance - $melhorenvio_balance->reserved - $melhorenvio_balance->debts, 2, ',', '.')}}
              </div>
            </div>
                        
            <span class="row justify-content-center mt-3">
              ({{__("By confirming, the amount of the purchase and previous debts will be debited from the balance")}})
            </span>
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
