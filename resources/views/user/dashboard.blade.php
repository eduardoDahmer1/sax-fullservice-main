@extends('front.themes.'.env('THEME', 'theme-01').'.layout')

@section('content')
<section class="user-dashbord">
    <div class="container">
        <div class="row">

            @include('includes.user-dashboard-sidebar')

            <div class="col-lg-8">

                @include('includes.form-success')

                <div class="user-profile-details">
                    <div class="account-info">
                        <div class="header-area">
                            <h4 class="title">
                                {{ __("Account Information") }}
                            </h4>
                        </div>
                        <div class="edit-info-area"></div>
                        <div class="main-info">
                            <h5 class="title">{{ $user->name }}</h5>
                            <ul class="list">
                                <li>
                                    <p><span class="user-title">{{ __("Email") }}:</span> {{ $user->email }}</p>
                                </li>

                                @if($user->document != null)
                                <li>
                                    <p><span class="user-title">{{ $customer_doc_str }}:</span> {{ $user->document }}
                                    </p>
                                </li>
                                @endif

                                @if($user->phone != null)
                                <li>
                                    <p><span class="user-title">{{ __("Phone") }}:</span> {{ $user->phone }}</p>
                                </li>
                                @endif

                                @if($user->address != null)
                                <li>
                                    <p><span class="user-title">{{ __("Address") }}:</span> {{ $user->address }}</p>
                                </li>
                                @endif

                                @if($user->address_number != null)
                                <li>
                                    <p><span class="user-title">{{ __('Number') }}:</span> {{ $user->address_number }}
                                    </p>
                                </li>
                                @endif

                                @if($user->complement != null)
                                <li>
                                    <p><span class="user-title">{{ __('Complement') }}:</span> {{ $user->complement }}
                                    </p>
                                </li>
                                @endif

                                @if($user->district != null)
                                <li>
                                    <p><span class="user-title">{{ __('District') }}:</span> {{ $user->district }}</p>
                                </li>
                                @endif

                                @if($user->city != null)
                                <li>
                                    <p><span class="user-title">{{ __("City") }}:</span> {{ $user->city }}</p>
                                </li>
                                @endif

                                @if($user->state != null)
                                <li>
                                    <p><span class="user-title">{{ __('State') }}:</span> {{ $user->state }}</p>
                                </li>
                                @endif

                                @if($user->zip != null)
                                <li>
                                    <p><span class="user-title">{{ __("Zip") }}:</span> {{ $user->zip }}</p>
                                </li>
                                @endif

                                @if(config("features.marketplace") && $gs->is_affilate == 1)
                                <li>
                                    <p><span class="user-title">{{ __("Affiliate Bonus") }}:</span>
                                        {{ App\Models\Product::vendorConvertPrice($user->affilate_income) }}
                                    </p>
                                </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection