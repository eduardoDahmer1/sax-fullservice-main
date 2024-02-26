@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')
    <div class="container mt-5 mb-5">
        @include('includes.form-success')
        <div class="card-group col-lg-6 m-auto">
            <div class="card">
                <img class="card-img-top m-auto pt-5" src="{{ asset('assets/general/pay42-logo-1.png') }}"
                    alt="Pay42 imagem logo" style="width: 80%">
                <div class="card-body">
                    <br>
                    <h5 class="card-title text-center">{{ __('Enter your card details') }}</h5>
                    <br>
                    <form action="{{ route('pay42.notify') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-10 m-auto">
                                <div class="form-group">
                                    <label>{{ __('Card number') }}</label>
                                    <input type="tel" class="form-control" name="cardNumber"
                                        placeholder="•••• •••• •••• ••••" value="{{ old('cardNumber') }}" pattern="[0-9]+"
                                        title="{{ __('Field only accepts numbers') }}">
                                    <small
                                        class="form-text text-muted">{{ __('Your data is redirected directly to Pay42.') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10 m-auto">
                                <div class="form-group mt-4 text-center">
                                    <h3>{{ __('Total Purchase') }}
                                        <span class="badge badge-light"
                                            style="border: 1px solid; color: #615993;">{{ $pedido->currency_sign }}
                                            {{ number_format((float) ($pedido->pay_amount * $pedido->currency_value), 2, '.', '') }}</span>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-10 m-auto">
                                <div class="form-group">
                                    <label>{{ __('Name printed on card') }}</label>
                                    <input type="text" class="form-control" name="cardHolderName"
                                        value="{{ old('cardHolderName') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10 m-auto">
                                <div class="form-group">
                                    <label>{{ __('Security code') }}</label>
                                    <input type="tel" class="form-control" name="securityCode" placeholder="CVC"
                                        value="{{ old('securityCode') }}" pattern="[0-9]+"
                                        title="{{ __('Field only accepts numbers') }}" maxlength="4">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10 m-auto">
                                <div class="form-group">
                                    <label>{{ __('Card expiring date') }}</label>
                                    <div class="row">
                                        <div class="col-5">
                                            <input type="tel" class="form-control" name="expirationMonth"
                                                placeholder="MM" value="{{ old('expirationMonth') }}" pattern="[0-9]+"
                                                title="{{ __('Field only accepts numbers') }}" maxlength="2">
                                        </div>
                                        /
                                        <div class="col-5">
                                            <input type="tel" class="form-control" name="expirationYear"
                                                placeholder="AA" value="{{ old('expirationYear') }}" pattern="[0-9]+"
                                                title="{{ __('Field only accepts numbers') }}" maxlength="2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10 m-auto" style="display: flex; justify-content: center;">
                                <div class="form-group">
                                    <label>{{ __('Number of installments') }}</label>
                                    <select name="installments">
                                        @for ($i = 1; $i < 13; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="Amount" value="{{ $pedido->pay_amount }}">
                        <input type="hidden" name="reference" value="{{ $pedido->order_number }}">
                        <br>
                        <div class="row">
                            <div class="col-12 m-auto text-center">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">{{ __('Confirm') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        {{ __('Integration to pay42, if you have questions call:') }} {{ '47 2122 8612' }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script></script>
@endsection
