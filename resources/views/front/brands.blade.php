@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')

@section('styles')
@parent
<style>
    /** scroll into anchor considering the fixed header **/
    /** Source: https://css-tricks.com/hash-tag-links-padding/#more-from-kirk-gleffe **/
    /* .section-title:target {
        margin-top: -40px;
        padding-top: 80px;
    } */

    .slider-buttom-category .single-category .left .title {
        font-size: 1rem;
        word-wrap: anywhere;
        margin-left:1em;
    }

    /* .slider-buttom-category .single-category .left {
        margin-right: inherit !important;
    } */

    .slider-buttom-category .single-category .right {
        max-height: 10em;
        filter: none !important;
    }

    .slider-buttom-category .single-category {
        align-self: stretch;
        height: 11em;
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
                        <a href="{{route('front.brands')}}">{{ __("Brands") }}</a>
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
            @if (count($brands) > 0)
            @php
            $numberLink = false;
            @endphp
            <div class="col-md-12">
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

            @foreach ($brands as $key => $brand)

            @php
            $brandStart = \App\Helpers\Helper::strNormalize(strtoupper(mb_substr($brand->name, 0, 1)));
            @endphp

            @if(is_numeric($brandStart))
            @php
            $brandStart = '#';
            @endphp
            @endif

            @if($brandStart !== $currentChar)

            @php
            $currentChar = $brandStart;
            @endphp

            <div id="title-brands" class="section-top">
                <h2 class="section-title"
                    id="brands-starting-with-{{($currentChar === '#' ? 'numbers' : $currentChar)}}">
                    {{$currentChar}}
                </h2>
            </div>
            @endif
            <div class="col-xl-4 col-md-6 sc-common-padding d-flex flex-column">
                <a href="{{route('front.brand', $brand->slug)}}" class="single-category">
                    <div class="left">
                        <h5 class="title">
                            {{ $brand->name }}
                        </h5>
                        <p class="count">
                            {{ $brand->products_count }} {{__('itens')}}
                        </p>
                    </div>
                    <div class="right">
                        <img class="imagemMarca" src="{{ $brand->thumbnail }}" alt="{{$brand->name}}">
                    </div>
                </a>
            </div>
            @endforeach
            @else
            <div class="col-lg-12">
                <div class="page-center">
                    <h4 class="text-center">{{ __("No Brands Found.") }}</h4>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
{{-- Slider buttom banner End --}}
@endsection