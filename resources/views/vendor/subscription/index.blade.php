@extends('layouts.vendor')
@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                    <h4 class="heading">{{ __("Subscription") }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('vendor-dashboard') }}">{{ __("Dashbord") }} </a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ __("Settings") }} </a>
                    </li>
                    <li>
                        <a href="{{ route('vendor-subscription-index') }}">{{ __("Subscription") }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="add-product-content">
        <div class="row">
                @foreach ($subs as $sub)
                    <div class="col-lg-5">
                        <div class="elegant-pricing-tables style-2 text-center">
                            <div class="pricing-head">
                                <h3>{{ $sub->title }}</h3>
                                @if ($sub->price == 0)
                                    <span class="price">
                                        <span class="price-digit">{{ __('Free') }}</span>
                                    </span>
                                @else
                                    <span class="price">
                                        <div>
                                            <sup>{{ $curr->sign }}</sup>
                                            <span class="price-digit">{{ $sub->price }}</span>
                                        </div>
                                        <span class="price-month">{{ $sub->days }}
                                            {{ __('Day(s)') }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="pricing-detail">
                                {!! $sub->details !!}
                            </div>
                            @if (!empty($package))
                                @if ($package->subscription_id == $sub->id)
                                @if (Carbon\Carbon::now()->format('Y-m-d') > $user->date)
                                    <small>{{ __('Expired on:') }}
                                        {{ date('d/m/Y', strtotime($user->date)) }}</small>
                                @else
                                    <small>{{ __('Ends on:') }}
                                        {{ date('d/m/Y', strtotime($user->date)) }}</small>
                                @endif<br>
                                    <a href="{{ route('user-vendor-request', $sub->id) }}"
                                        class="btn btn-default">{{ __('Renew') }}</a>
                                @else
                                    <a href="{{ route('user-vendor-request', $sub->id) }}"
                                        class="btn btn-default">{{ __('Get Started') }}</a>
                                    <br><small>&nbsp;</small>
                                @endif
                            @else
                                <a href="{{ route('user-vendor-request', $sub->id) }}"
                                    class="btn btn-default">{{ __('Get Started') }}</a>
                                <br><small>&nbsp;</small>
                            @endif
                            {{-- <a href="#" class="btn btn-default">Get Started Now</a> --}}
                        </div>
                    </div>
                @endforeach
        </div>
    </div>
</div>
@endsection