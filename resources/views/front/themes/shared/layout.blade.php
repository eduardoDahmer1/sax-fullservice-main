<!DOCTYPE html>

<html lang="{{ $current_locale }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="minimal-ui, width=device-width,initial-scale=1">

    <meta name="language" content="{{ $current_locale }}" />
    @if ($current_locale == 'pt-br')
        <meta name="country" content="BRA" />
        <meta name="currency" content="R$" />
    @endif

    @if (isset($page->meta_tag) && isset($page->meta_description))
        <meta name="keywords"
            content="{{ is_array($page->meta_tag) ? implode(',', $page->meta_tag) : $page->meta_tag }}">
        <meta name="description" content="{{ $page->meta_description }}">
        <title>{{ $page->title }} - {{ $gs->title }}</title>
    @elseif(isset($blog_meta_tag))
        {{-- BLOG --}}
        <meta name="keywords" content="{{ $blog_meta_tag }}">
        <meta name="description" content="{{ $blog_meta_description }}">
        <title>{{ $blog_title }} - {{ $gs->title }}</title>
    @elseif(isset($cat))
        {{-- CATEGORY --}}
        <title>{{ ucfirst($cat->name) }} - {{ $gs->title }}</title>
        @if (stripos(strtolower($cat->photo), 'noimage.png'))
            <meta property="og:image" content="{{ $gs->logoUrl }}" />
        @else
            <meta property="og:image" content="{{ asset('storage/images/categories/' . $cat->photo) }}">
        @endif
    @elseif(isset($brand) && !isset($brands))
        {{-- BRAND --}}
        <meta property="og:title" content="{{ ucfirst($brand->name) }} - {{ $gs->title }}" />
        <meta property="og:image" content="{{ asset('storage/images/brands/' . $brand->image) }}">
        <meta name="author" content="CrowTech">
        <title>{{ $brand->name }} - {{ $gs->title }}</title>
    @elseif(isset($productt))
        {{-- PRODUCT --}}
        <meta name="keywords" content="{{ !empty($productt->meta_tag) ? implode(',', $productt->meta_tag) : '' }}">
        <meta name="description"
            content="{{ $productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description) }}">
        <meta property="og:title" content="{{ $productt->name }}" />
        <meta property="og:description"
            content="{{ $productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description) }}" />

        @if (stripos(strtolower($productt->thumbnail), 'noimage.png'))
            <meta property="og:image" content="{{ $gs->logoUrl }}" />
        @else
            <meta property="og:image" content="{{ asset('storage/images/thumbnails/' . $productt->thumbnail) }}">
        @endif

        <meta name="author" content="CrowTech">
        <title>{{ $productt->name }}</title>
    @elseif(request()->is('privacy-policy'))
        <meta property="og:image" content="{{ $gs->logoUrl }}" />
        <meta name="keywords" content="{{ $seo->meta_keys }}">
        <meta name="description" content="{{ $seo->meta_description }}">
        <meta name="author" content="CrowTech">
        <title>{{ __('Privacy Policy') }} - {{ $gs->title }}</title>
    @elseif(request()->is('terms-of-service'))
        <meta property="og:image" content="{{ $gs->logoUrl }}" />
        <meta name="keywords" content="{{ $seo->meta_keys }}">
        <meta name="description" content="{{ $seo->meta_description }}">
        <meta name="author" content="CrowTech">
        <title>{{ __('General Terms of Service') }} - {{ $gs->title }}</title>
    @elseif(request()->is('vendor-terms-of-service'))
        <meta property="og:image" content="{{ $gs->logoUrl }}" />
        <meta name="keywords" content="{{ $seo->meta_keys }}">
        <meta name="description" content="{{ $seo->meta_description }}">
        <meta name="author" content="CrowTech">
        <title>{{ __('Seller Terms of Service') }} - {{ $gs->title }}</title>
    @elseif(request()->is('policy'))
        <meta property="og:image" content="{{ $gs->logoUrl }}" />
        <meta name="keywords" content="{{ $seo->meta_keys }}">
        <meta name="description" content="{{ $seo->meta_description }}">
        <meta name="author" content="CrowTech">
        <title>{{ __('Buy & Return Policy') }} - {{ $gs->title }}</title>
    @else
        <meta property="og:image" content="{{ $gs->logoUrl }}" />
        <meta name="keywords" content="{{ $seo->meta_keys }}">
        <meta name="description" content="{{ $seo->meta_description }}">
        <meta name="author" content="CrowTech">
        <title>{{ $gs->title }}</title>
    @endif

    <!-- google tag manager -->
    {!! $seo->tag_manager_head !!}

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $gs->faviconUrl }}" />

    <!-- stylesheet crow -->
    <link rel="stylesheet" href="{{ asset('assets/front/themes/shared/assets/css/crow.css') }}">
    
    <!-- Simple LightBox -->
    <link rel="stylesheet" href="{{ asset('assets/front/themes/shared/assets/css/simple-lightbox.min.css') }}">

    @if ($slocale->rtl == '1')
        <!-- stylesheet -->
        <link rel="stylesheet" href="{{ asset('assets/front/themes/shared/assets/css/rtl/all.css') }}">
        <link rel="stylesheet"
            href="{{ asset('assets/front/themes/' . env('THEME', 'theme-01') . '/assets/css/rtl/rtl.css') }}">
        <!--Updated CSS-->
        <link rel="stylesheet"
            href="{{ asset(
                'assets/front/themes/' .
                    env('THEME', 'theme-01') .
                    '/assets/css/rtl/styles.php?' .
                    'color=' .
                    str_replace('#', '', $gs->colors) .
                    '&' .
                    'header_color=' .
                    str_replace('#', '', $gs->header_color) .
                    '&' .
                    'footer_color=' .
                    str_replace('#', '', $gs->footer_color) .
                    '&' .
                    'footer_text_color=' .
                    str_replace('#', '', $gs->footer_text_color) .
                    '&' .
                    'copyright_color=' .
                    str_replace('#', '', $gs->copyright_color) .
                    '&' .
                    'menu_color=' .
                    str_replace('#', '', $gs->menu_color) .
                    '&' .
                    'menu_hover_color=' .
                    str_replace('#', '', $gs->menu_hover_color),
            ) }}">
    @else
        <!-- stylesheet -->
        <link rel="stylesheet" href="{{ asset('assets/front/themes/shared/assets/css/all.css') }}">
        <link rel="stylesheet"
            href="{{ asset('assets/front/themes/' . env('THEME', 'theme-01') . '/assets/css/theme.css') }}">
        <!--Updated CSS-->
        <link rel="stylesheet"
            href="{{ asset(
                'assets/front/themes/' .
                    env('THEME', 'theme-01') .
                    '/assets/css/styles.php?' .
                    'theme_color_1=' .
                    str_replace('#', '', $gs->colors) .
                    '&' .
                    'theme_color_2=' .
                    str_replace('#', '', $gs->header_color) .
                    '&' .
                    'text_color_1=' .
                    str_replace('#', '', $gs->copyright_color) .
                    '&' .
                    'text_color_2=' .
                    str_replace('#', '', $gs->footer_text_color) .
                    '&' .
                    'menu_color=' .
                    str_replace('#', '', $gs->menu_color) .
                    '&' .
                    'menu_hover_color=' .
                    str_replace('#', '', $gs->menu_hover_color),
            ) }}">
    @endif

    @yield('styles')
</head>

<body>
    @auth
        <x-modal-wishlist />
    @endauth
    <!-- google tag manager -->
    {!! $seo->tag_manager_body !!}

    @include('front.themes.shared.components.preloader')
    @include('front.themes.shared.components.popup')

    @yield('before-header')

    <x-dynamic-component :component="'front.themes.' . env('THEME', 'theme-15') . '.components.header'" />

    @yield('after-header')

    @yield('content')

    @yield('before-footer')

    @includeFirst([
        'front.themes.' . env('THEME', 'theme-01') . '.components.footer',
        'front.themes.shared.components.footer',
    ])

    @yield('after-footer')

    @include('front.themes.shared.components.footer-go-top-button')

    @include('front.themes.shared.components.modal-login')
    @include('front.themes.shared.components.modal-forgot')
    @include('front.themes.shared.components.modal-vendor')
    @include('front.themes.shared.components.modal-product-quickview')
    @include('front.themes.shared.components.modal-order-tracking')
    @if ($gs->privacy_policy)
        @include('front.themes.shared.components.cookie-alert')
    @endif

    @php
        $current_locale = strtolower(str_replace('-', '_', str_replace('admin_', '', App::getLocale())));
    @endphp

    <script type="text/javascript">
        var current_locale = "{{ $current_locale }}";
        var mainurl = "{{ url('/') }}";
        var loader = '{{ $gs->is_loader }}';
        var langg = {!! json_encode(new \stdClass()) !!};
        var datatable_translation_url = "{{ $datatable_translation }}"
    </script>

    <!-- jquery -->
    <script src="{{ asset('assets/front/themes/shared/assets/js/jquery.js') }}"></script>

    <script src="{{ asset('assets/front/themes/shared/assets/js/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- popper -->
    <script src="{{ asset('assets/front/themes/shared/assets/js/popper.min.js') }}"></script>
    <!-- bootstrap -->
    <script src="{{ asset('assets/front/themes/shared/assets/js/bootstrap.min.js') }}"></script>
    <!-- plugin js-->
    <script src="{{ asset('assets/front/themes/shared/assets/js/plugin.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/jquery-number-master/jquery.number.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/jquery-number-master/jquery.number.min.js') }}"></script>

    <script src="{{ asset('assets/front/themes/shared/assets/js/xzoom.min.js') }}"></script>
    <script src="{{ asset('assets/front/themes/shared/assets/js/jquery.hammer.min.js') }}"></script>
    <script src="{{ asset('assets/front/themes/shared/assets/js/setup.js') }}"></script>

    <script src="{{ asset('assets/front/themes/shared/assets/js/toastr.js') }}"></script>

    <!-- Simple LightBox -->
    <script src="{{ asset('assets/front/themes/shared/assets/js/simple-lightbox.min.js') }}"></script>

    <!-- theme -->
    <script src="{{ asset('assets/front/themes/' . env('THEME', 'theme-01') . '/assets/js/theme.js') }}"></script>
    <!-- shared -->
    <script src="{{ asset('assets/front/themes/shared/assets/js/shared.js') }}"></script>

    <!-- Animação de aparecer ao scrolar -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1500,
            offset: 120,
        });

        var lightbox = new SimpleLightbox('.gallery-product a');
    </script>

    {!! $seo->google_analytics !!}

    {!! $seo->facebook_pixel !!}

    @if ($gs->is_talkto == 1)
        <!--Start of Tawk.to Script-->
        {!! $gs->talkto !!}
        <!--End of Tawk.to Script-->
    @endif

    @if ($gs->is_jivochat == 1)
        <!--Start of Jivochat Script-->
        {!! $gs->jivochat !!}
        <!--End of Jivochat Script-->
    @endif

    @yield('scripts')

    @include('front.themes.shared.components.footer-whatsapp-button')
    @include('front.themes.shared.components.modal-simplified-checkout')
    @include('front.themes.shared.components.modal-loading-spinner')

</body>

</html>
