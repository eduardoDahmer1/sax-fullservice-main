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
                        <a href="{{ route('front.privacypolicy') }}">
                            {{ __("Privacy Policy") }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->


<!-- Contact Us Area Start -->
<section class="contact-us">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="left-area">
                    <div class="contact-form">
                        <div class="gocover"
                            style="background: url({{ asset('storage/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        {!! $gs->privacy_policy !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Us Area End-->

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
    setTimeout(function() {
        $(".refresh_code").click();
    }, 10);
});
</script>
@endsection
