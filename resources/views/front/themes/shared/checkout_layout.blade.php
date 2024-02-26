<!DOCTYPE html>

<html lang="{{ $current_locale }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="language" content="{{ $current_locale }}" />
    @if ($current_locale == 'pt-br')
        <meta name="country" content="BRA" />
        <meta name="currency" content="R$" />
    @endif


    <title>Checkout -  Sax Department - Mayor tienda de lujo en América del Sur</title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $gs->faviconUrl }}" />
        <!-- stylesheet -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('assets/front/themes/shared/assets/css/all.css') }}">
        <!-- <link rel="stylesheet"
            href="{{ asset('assets/front/themes/theme-15' . '/assets/css/theme.css') }}"> -->
        <!--Updated CSS-->
        <link rel="stylesheet"
            href="{{ asset(
                'assets/front/themes/' .
                    env('THEME', 'theme-01') .
                    '/assets/css/styles _checkout.php?' .
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

            <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/checkout/style.css') }}">

</head>

<body>
    @yield('content')

    @include('front.themes.shared.components.modal-login')
    @include('front.themes.shared.components.modal-forgot')
   
    <!-- jquery -->
    <script src="{{ asset('assets/front/themes/shared/assets/js/jquery.js') }}"></script>

    <!-- bootstrap -->
    <script src="{{ asset('assets/front/themes/shared/assets/js/bootstrap.min.js') }}"></script>


    @yield('scripts')

    @include('front.themes.shared.components.modal-simplified-checkout')
</body>

</html>
