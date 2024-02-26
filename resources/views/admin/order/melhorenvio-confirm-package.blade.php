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
                <form id="melhorenvio-request-form" action="{{ route('admin-order-select-melhorenvio-service') }}"
                    method="POST" enctype="multipart/form-data">
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
                                <h4 class="heading">{{ __('Please confirm package') }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('From Postal Code') }}:
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <input name="origin_zipcode" class="input-field" value="{{ $origin_zipcode }}" required>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Destination Postal Code') }}:
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <input name="dest_zipcode" class="input-field" value="{{ $dest_zipcode }}" required>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Height') }} (cm):
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <input name="package[height]" class="input-field" value="{{ $package->height }}" required
                                type="number" step="1">
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Width') }} (cm):
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <input name="package[width]" class="input-field" value="{{ $package->width }}" required
                                type="number" step="1">
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Length') }} (cm):
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <input name="package[length]" class="input-field" value="{{ $package->length }}" required
                                type="number" step="1">
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Weight') }} (kg):
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <input name="package[weight]" class="input-field" value="{{ $package->weight }}" required
                                type="number" step="0.001">
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Insurance value') }} (R$):
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <input name="options[insurance_value]" class="input-field"
                                value="{{ round($options->insurance_value,2) }}" required type="number" step="0.01">
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Receipt') }}:
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="options[receipt]" class="custom-control-input"
                                    id="options[receipt]" {{ $options->receipt ? "checked" : "" }} value="1">
                                <label class="custom-control-label" for="options[receipt]"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Own hand') }}:
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="options[own_hand]" class="custom-control-input"
                                    id="options[own_hand]" {{ $options->own_hand ? "checked" : "" }} value="1">
                                <label class="custom-control-label" for="options[own_hand]"></label>
                            </div>
                        </div>
                    </div>

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
