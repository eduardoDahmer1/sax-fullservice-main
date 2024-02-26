@extends('layouts.vendor')
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Withdraw Now') }} <a class="add-btn" href="{{ url()->previous() }}"><i
                                class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('vendor-dashboard') }}">{{ __('Dashbord') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('My Withdraws') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Withdraw Now') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">

                            <div class="gocover"
                                style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>

                            @include('includes.admin.form-both')
                            <form id="geniusform" class="form-horizontal" action="{{ route('vendor-wt-store') }}"
                                method="POST" enctype="multipart/form-data">

                                {{ csrf_field() }}

                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="name"><b>{{ __('Current Balance') }} :
                                            {{ App\Models\Product::vendorConvertPrice(Auth::user()->current_balance) }}</b></label>
                                </div>

                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="name">{{ __('Withdraw Amount') }} *

                                    </label>
                                    <div class="col-sm-12">
                                        <input name="amount" placeholder="{{ __(' Withdraw Amount') }}"
                                            class="form-control" type="text" value="{{ old('amount') }}" required>
                                    </div>
                                </div>

                                <di class="item form-group">
                                    <label class="control-label col-sm-12"
                                        for="name">{{ __('Additional Reference(Optional)') }}

                                    </label>
                                    <div class="col-sm-12">
                                        <textarea class="form-control" name="reference" rows="6"
                                            placeholder="{{ __('Additional Reference(Optional)') }}">{{ old('reference') }}</textarea>
                                    </div>
                                </di>

                                <div id="resp" class="col-md-12">

                                    <span class="help-block">
                                        <strong>{{ __('Withdraw Fee') }} {{ $sign->sign }} {{ $gs->withdraw_fee }}
                                            {{ __('and') }}
                                            {{ $gs->withdraw_charge }}%
                                            {{ __('will deduct from your account.') }}</strong>
                                    </span>
                                </div>

                                <hr>
                                <div class="add-product-footer">
                                    <button name="addProduct_btn" type="submit"
                                        class="mybtn1">{{ __('Withdraw') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
