@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')

@section('styles')
@parent
<style>
    /** scroll into anchor considering the fixed header **/
    /** Source: https://css-tricks.com/hash-tag-links-padding/#more-from-kirk-gleffe **/
    .section-title:target {
        margin-top: -40px;
        padding-top: 80px;
    }

    .slider-buttom-category .single-category .left .title {
        font-size: 1rem;
        word-wrap: anywhere;
    }

    .slider-buttom-category .single-category .left {
        margin-right: inherit !important;
    }

    .slider-buttom-category .single-category .right {
        max-width: 80px;
        filter: none !important;
    }

    .slider-buttom-category .single-category {
        align-self: stretch;
    }

    .item .info {
        margin-top: 10px;
    }

    .item:hover .info {
        top: -5px;
    }

    .marcas-page #title-brands {
        padding-left: 20px;
    }

    @media (min-width:768px){
        .marcas-page #title-brands {
            margin-left: 20px;
            padding-left: 0;
        }
    }

</style>
@endsection
@section('content')
<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="pages">
                    <li>
                        <a href="{{route('front.index')}}">{{ __("Home") }}</a>
                    </li>
                    <li>
                        <a href="{{route('front.stores')}}">{{ __("Stores") }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->
{{-- Slider buttom Category Start --}}
<section class="slider-buttom-category marcas-page">
    <div class="container">
        <div class="row">
            
            @if (count($vendor_stores) > 0)
            @php
            $numberLink = false;
            @endphp
            <div class="col">
                <ul class="nav nav-pills">
                    @foreach($chars as $char)
                    @if(is_numeric($char))

                    @if(!$numberLink)
                    <li class="nav-item m-1">
                        <a class="nav-link border" href="#brands-starting-with-numbers">#</a>
                    </li>

                    @php
                    $numberLink = true;
                    @endphp

                    @endif

                    @continue
                    @endif
                    <li class="nav-item m-1">
                        <a class="nav-link border" href="#brands-starting-with-{{$char}}">{{$char}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>

            @php
            $currentChar = '';
            @endphp
            @foreach ($vendor_stores as $key => $store)

            @php
            $vendor_storestart = \App\Helpers\Helper::strNormalize(strtoupper(mb_substr($store->shop_name, 0, 1)));
            @endphp

            @if(is_numeric($vendor_storestart))
            @php
            $vendor_storestart = '#';
            @endphp
            @endif

            @if($vendor_storestart !== $currentChar)

            @php
            $currentChar = $vendor_storestart;
            @endphp

            <div id="title-brands" class="section-top">
                <h2 class="section-title"
                    id="brands-starting-with-{{($currentChar === '#' ? 'numbers' : $currentChar)}}">
                    {{$currentChar}}
                </h2>
            </div>
            @endif
            <div class="col-xl-2 col-lg-3 col-md-4 sc-common-padding d-flex flex-column">
                <a href="{{ route('front.vendor',str_replace(' ', '-', $store->shop_name)) }}" class="single-category">
                    <div class="left">
                        <h5 class="title">
                            {{ $store->shop_name }}
                        </h5>
                    </div>
                    <div class="right">
                        <img src="{{ $store->vendorPhoto }}" alt="{{$store->shop_name}}">
                    </div>
                </a>
            </div>
            @endforeach
            @else
            <div class="col-lg-12">
                <div class="page-center">
                    <h4 class="text-center">{{ __("No Stores Found.") }}</h4>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
{{-- Slider buttom banner End --}}
@endsection