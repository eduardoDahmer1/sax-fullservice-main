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
          <form id="melhorenvio-request-form" action="{{ route('admin-order-request-melhorenvio') }}" method="POST"
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
                  <h4 class="heading">{{ __('Please confirm service') }}</h4>
                </div>
              </div>
            </div>
            
            @foreach ($melhorenvio_rates as $service)
            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">
                  @if ($service->id == $melhorenvio_order_service)
                    <span class="badge badge-pill badge-primary">{{ __('Client choice') }}</span>
                  @endif
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
              @if(!isset($service->error))
              @php
                $value = App\Helpers\Helper::toFloat($service->custom_price) / $curr_brl->value;
                
                $value_format = $curr_brl->sign . number_format(
                  $value * $curr_brl->value,
                  $curr_brl->decimal_digits,
                  $curr_brl->decimal_separator,
                  $curr_brl->thousands_separator
                );

                $estimated_min = $service->custom_delivery_range->min;
                $estimated_max = $service->custom_delivery_range->max;
              @endphp
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input" name="selected_service"
                  value="{{$service->id}}" id="service{{$service->id}}"
                  {{($service->id == $melhorenvio_order_service)?'checked':''}} required>
                <span class="checkmark"></span>
                <label class="custom-control-label d-flex flex-column" for="service{{$service->id}}">
                  {{$service->company->name}} - {{$service->name}}: {{$value_format}}
                  <small>{{__(':min to :max days',['min'=>$estimated_min, 'max'=>$estimated_max])}}</small>
                </label>
              </div>
              @else
              <div class="custom-control custom-radio custom-control-inline">
                <label class="d-flex flex-column">{{$service->company->name}} - {{$service->name}}
                  <small>{{$service->error}}</small>
                </label>
              </div>                
              @endif
              </div>
            </div>
            @endforeach
            
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
 
@endsection
