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
          <form id="aex-request-form" action="{{ route('admin-order-confirm-aex') }}" method="POST"
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
            <input type="hidden" name="id_solicitud" value="{{ $aex_request->datos->id_solicitud }}">

            <div class="row justify-content-center">
              <div class="col-lg-9">
                <div class="row justify-content-center">
                  <h4 class="heading">{{ __('AEX Delivery Information') }}</h4>
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
                <p>{{ $aex_city->denominacion }}</p>
                <input type="hidden" name="aex_destination" value="{{ $aex_destination }}">
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Main street') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="delivery_calle_principal" class="input-field"
                  value="{{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}"
                  required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Cross street') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="delivery_calle_transversal" class="input-field" value="" required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Building number') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input type="number" min=0 max=5000 name="delivery_numero_casa" class="input-field"
                  value="{{ $order->shipping_address_number == null ? $order->customer_address_number : $order->shipping_address_number }}"
                  >
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Name') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="delivery_nombre" class="input-field"
                  value="{{$order->shipping_name == null ? $order->customer_name : $order->shipping_name}}"
                  required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Document') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="delivery_documento" class="input-field"
                  value="{{ $order->customer_document }}"
                  required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('E-mail') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="delivery_email" class="input-field" type="email"
                  value="{{ $order->customer_email }}"
                  required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Telephone') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                <input name="delivery_telefono" class="input-field"
                  value="{{ $order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone }}"
                  required>
              </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-lg-3">
                <div class="left-area">
                  <h4 class="heading">{{ __('Shipping method') }}:
                  </h4>
                </div>
              </div>
              <div class="col-lg-6">
                @foreach ($aex_request->datos->condiciones as $service)
                  @if ($service->incluye_pickup == 't')
                    <div class="custom-control custom-radio custom-control-inline mb-3">
                      <input type="radio" id="radio-service-{{ $service->id_tipo_servicio }}" name="service"
                        class="custom-control-input radio-service" value="{{ $service->id_tipo_servicio }}"
                        {{ $service->id_tipo_servicio == $aex_service ? 'checked' : '' }} required>
                      <label class="custom-control-label" for="radio-service-{{ $service->id_tipo_servicio }}">
                        <div class="column">
                          <div>{{ $service->tipo_servicio }}
                            -
                            {{ $curr_pyg->sign . number_format($service->costo_flete, $curr_pyg->decimal_digits, $curr_pyg->decimal_separator, $curr_pyg->thousands_separator) }}
                            - ({{ $service->tiempo_entrega . ' ' . __('hours') }})
                            @if ($service->id_tipo_servicio == $aex_service)
                              <span class="badge badge-pill badge-primary">{{ __('Client choice') }}</span>
                            @endif
                          </div>
                          <div>
                            <small>{!! $service->descripcion !!}</small>
                          </div>
                        </div>
                      </label>
                    </div>
                    <div id="additional-div-{{ $service->id_tipo_servicio }}" class="additional-div ml-5 mb-3 column"
                      style="display: {{ $service->id_tipo_servicio != $aex_service ? 'none' : '' }};">
                      <b>{{ __('Additional') }}:</b>
                      @foreach ($service->adicionales as $additional)
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox"
                            id="additional-{{ $service->id_tipo_servicio }}-{{ $additional->id_adicional }}"
                            name="additional[{{ $service->id_tipo_servicio }}][{{ $additional->id_adicional }}]"
                            class="custom-control-input"
                            {{ $additional->obligatorio || $orderStoreSettings->is_aex_insurance ? 'checked' : '' }}
                            {{ $additional->obligatorio ? 'disabled' : '' }} value="1">
                          <label class="custom-control-label"
                            for="additional-{{ $service->id_tipo_servicio }}-{{ $additional->id_adicional }}">
                            {{ $additional->denominacion }}
                            -
                            {{ $curr_pyg->sign . number_format($additional->costo, $curr_pyg->decimal_digits, $curr_pyg->decimal_separator, $curr_pyg->thousands_separator) }}
                            {{ $additional->obligatorio ? '- ' . __('Required') : '' }}
                          </label>
                        </div>
                      @endforeach
                    </div>
                    <input type="hidden" name="incluye_envio[{{ $service->id_tipo_servicio }}]" value="{{$service->incluye_envio}}">
                    @if ($service->incluye_envio == 'f')
                      <div id="points-div-{{ $service->id_tipo_servicio }}" class="points-div ml-5 column"
                        style="display: {{ $service->id_tipo_servicio != $aex_service ? 'none' : '' }};">
                        <b>{{ __('Pickup location') }}:</b>
                        @foreach ($service->puntos_entrega as $i => $point)
                          <div class="custom-control custom-radio custom-control mb-2">
                            <input type="radio"
                              id="radio-point[{{ $service->id_tipo_servicio }}][{{ $point->id }}]"
                              name="point[{{ $service->id_tipo_servicio }}]" class="custom-control-input"
                              value="{{ $point->id }}" {{ $point->id == $order->puntoid ? 'checked' : '' }}>
                            <label class="custom-control-label"
                              for="radio-point[{{ $service->id_tipo_servicio }}][{{ $point->id }}]">
                              <div class="column">
                                <div>
                                  {{ $point->codigo_ciudad }} - {{ $point->punto_entrega }} -
                                  {{ $point->tipo }}
                                </div>
                                <div>
                                  <small>{{ $point->direccion }}</small>
                                </div>
                                <div>
                                  <small>{{ $point->telefono }}</small>
                                </div>
                                <div>
                                  <small>{{ $point->horario_atencion }}</small>
                                </div>
                              </div>
                            </label>
                          </div>
                        @endforeach
                      </div>
                    @endif
                  @endif
                @endforeach
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
  <script>
    $('.radio-service').on('change', function() {
      var serviceId = $(this).val();
      $('.points-div').hide();
      $('#points-div-' + serviceId).show();
      $('.additional-div').hide();
      $('#additional-div-' + serviceId).show();
    });

  </script>
@endsection
