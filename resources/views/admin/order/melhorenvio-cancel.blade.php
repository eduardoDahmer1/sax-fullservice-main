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
              <a href="{{ route('admin-order-show', $melhorenvio_request->order_id) }}">{{ __('Order Details') }}</a>
            </li>
            <li>
              <a href="#">{{ __('Cancel Melhor Envio') }}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="add-product-content">
      <div class="row product-description">
        <div class="col-lg-12 body-area">
          <form id="melhorenvio-request-form" action="{{ route('admin-order-cancel-confirm-melhorenvio') }}" method="POST"
            enctype="multipart/form-data">
            {{ csrf_field() }}

            @include('includes.form-success')

            <input type="hidden" name="request_id" value="{{ $melhorenvio_request->id }}">
            
            <div class="row justify-content-center">
              <div class="col-lg-9">
                <div class="row justify-content-center">
                  <h4 class="heading">{{ __('Please confirm cancel') }}</h4>
                </div>
              </div>
            </div>
                        
            <span class="row justify-content-center mt-3">
              {{__("By confirming, your shipping will be canceled and the amount will be redeemed to your wallet")}}
            </span>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Description') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="description" class="input-field"
                  value="">
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
