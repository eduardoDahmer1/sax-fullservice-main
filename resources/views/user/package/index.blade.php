@extends('front.themes.'.env('THEME', 'theme-01').'.layout')
@section('content')
    <section class="user-dashbord">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="user-profile-details">
                        <div class="row justify-content-start">
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
                                                <a href="javascript:;"
                                                    class="btn btn-default">{{ __('Current Plan') }}</a>
                                                <br>
                                                @if (Carbon\Carbon::now()->format('Y-m-d') > $user->date)
                                                    <small class="hover-white">{{ __('Expired on:') }}
                                                        {{ date('d/m/Y', strtotime($user->date)) }}</small>
                                                @else
                                                    <small class="hover-white">{{ __('Ends on:') }}
                                                        {{ date('d/m/Y', strtotime($user->date)) }}</small>
                                                @endif
                                                <a href="{{ route('user-vendor-request', $sub->id) }}"
                                                    class="hover-white"><u>{{ __('Renew') }}</u></a>
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
            </div>
        </div>
    </section>

@endsection
