@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')

<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="pages">
                    <li>
                        <a href="{{ route('front.index') }}">
                            {{ __("Home") }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.page',$page->slug) }}">
                            {{ $page->title }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->



<section class="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="about-info">
                    <h4 class="title">
                        {{ $page->title }}
                    </h4>
                    <p>
                        {!! $page->details !!}
                    </p>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection