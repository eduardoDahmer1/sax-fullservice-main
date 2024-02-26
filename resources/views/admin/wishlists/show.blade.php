@extends('layouts.admin')

@section('styles')
    <style type="text/css">
        .table-responsive {
            overflow-x: hidden;
        }
    </style>
@endsection

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Customer Details') }} <a class="add-btn" href="javascript:history.back();"><i
                                class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.wishlist.index') }}">{{ __('Wishlists') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.wishlist.show', $wishlistGroup->id) }}">{{ __('Details') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content customar-details-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <h2 class="h3 font-weight-bold">{{__('Wishlist')}}</h2>
                                    <div class="table-responsive show-table">
                                        <table class="table">
                                            <tr>
                                                <th>{{ __('ID#') }}:</th>
                                                <td>{{ $wishlistGroup->id }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Name') }}:</th>
                                                <td>{{ $wishlistGroup->name }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-0 col-md-2"></div>
                                <div class="col-md-4 col-12">
                                    <h2 class="h3 font-weight-bold">{{__('User')}}</h2>
                                    <div class="table-responsive show-table">
                                        <table class="table">
                                            <tr>
                                                <th>{{ __('ID#') }}:</th>
                                                <td>{{ $wishlistGroup->user->id }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Name') }}:</th>
                                                <td>{{ $wishlistGroup->user->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Email') }}:</th>
                                                <td>{{ $wishlistGroup->user->email }}</td>
                                            </tr>
                                            @if ( $wishlistGroup->user->phone)
                                                <tr>
                                                    <th>{{ __('Phone') }}:</th>
                                                    <td>{{ $wishlistGroup->user->phone }}</td>
                                                </tr>
                                            @endif
                                            @if ($wishlistGroup->user->country)
                                                <tr>
                                                    <th>{{ __('Country') }}:</th>
                                                    <td>{{ $wishlistGroup->user->country }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>

                                @if ($wishlistGroup->wishlists->count())
                                    <div class="col-12">
                                        <h2 class="h3 font-weight-bold">{{__('Products')}}</h2>
                                        <div class="table-responsive show-table">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Photo') }}</th>
                                                        <th>{{ __('ID#') }}</th>
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Price') }}</th>
                                                        <th>{{ __('Stock') }}</th>
                                                        <th>{{ __('Brand') }}</th>
                                                        <th>{{ __('Category') }}</th>
                                                        <th>{{ __('SKU') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($wishlistGroup->wishlists as $wishlist)
                                                        <tr>
                                                            <td><img width="250" src="{{$wishlist->product->image}}"></td>
                                                            <td>{{ $wishlist->product->id }}</td>
                                                            <td>
                                                                <a class="d-inline name" href="{{route('front.product', $wishlist->product->slug)}}">
                                                                    {{ $wishlist->product->name }}
                                                                </a>
                                                            </td>
                                                            <td>{{ $wishlist->product->firstCurrencyPrice() }}</td>
                                                            <td>{{ $wishlist->product->stock }}</td>
                                                            <td>{{ $wishlist->product->brand->name }}</td>
                                                            <td>{{ $wishlist->product->category->name }}</td>
                                                            <td>{{ $wishlist->product->sku }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection