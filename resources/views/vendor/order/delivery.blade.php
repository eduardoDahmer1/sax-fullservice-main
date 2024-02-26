@extends('layouts.load')

@section('content')

<div class="content-area">

  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{route('vendor-order-update',$data->id)}}" method="POST"
              enctype="multipart/form-data">
              {{csrf_field()}}

              <div class="row">

                <div class="col-xl-6">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Payment Status') }} *</h4>
                    <select name="payment_status" required="">
                      <option value="Pending" {{$data->payment_status == 'Pending' ? "selected":""}}>{{ __('Unpaid') }}
                      </option>
                      <option value="Completed" {{$data->payment_status == 'Completed' ? "selected":""}}>{{ __('Paid') }}
                      </option>
                    </select>
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Delivery Status') }} *</h4>
                    <select name="status" required="">
                      <option value="pending" {{ $data->status == "pending" ? "selected":"" }}>{{ __('Pending') }}
                      </option>
                      <option value="processing" {{ $data->status == "processing" ? "selected":"" }}>
                        {{ __('Processing') }}</option>
                      <option value="on delivery" {{ $data->status == "on delivery" ? "selected":"" }}>
                        {{ __('On Delivery') }}</option>
                      <option value="completed" {{ $data->status == "completed" ? "selected":"" }}>{{ __('Completed') }}
                      </option>
                      <option value="declined" {{ $data->status == "declined" ? "selected":"" }}>{{ __('Declined') }}
                      </option>
                    </select>
                  </div>
                </div>

                <div class="col-xl-12">
                  <div class="input-form">
                    @component('admin.components.input-localized',["type" => "textarea"])
                        @slot('name')
                          track_text
                        @endslot
                        @slot('placeholder')
                          {{ __('Enter Track Note Here') }}
                        @endslot
                        {{ __('Track Note') }}
                    @endcomponent
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

@endsection