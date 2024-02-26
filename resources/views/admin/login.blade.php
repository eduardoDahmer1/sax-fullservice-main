<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="CrowTech">
    <!-- Title -->
    <title>{{ $gs->title }}</title>
    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $gs->faviconUrl }}" />
    <!-- Bootstrap -->
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" />
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/fontawesome.css') }}">
    <!-- icofont -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/icofont.min.css') }}">
    <!-- Sidemenu Css -->
    <link href="{{ asset('assets/admin/plugins/fullside-menu/css/dark-side-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/plugins/fullside-menu/waves.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets/admin/css/plugin.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/jquery.tagit.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-coloroicker.css') }}">
    <!-- Main Css -->
    <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/responsive.css') }}" rel="stylesheet" />

    <!-- trumbowyg css -->
    <link href="{{ asset('assets/admin/css/trumbowyg-all.min.css') }}" rel="stylesheet" />
    @yield('styles')

</head>

<body>

    <!-- Login and Sign up Area Start -->
    <section class="login-signup">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login-area">
                        <div class="row justify-content-center mb-5">
                            <div class="col-md-8">
                                <img src="{{ $gs->logoUrl }}" alt="">
                            </div>
                        </div>
                        <div class="header-area">
                            <h4 class="title">{{ __('Login Now') }}</h4>
                            <p class="text">{{ __('Welcome back, please sign in below') }}</p>
                        </div>
                        <div class="login-form">
                            @include('includes.admin.form-login')
                            <form id="loginform" action="{{ route('admin.login.submit') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-input">
                                    <input type="email" name="email" class="User Name"
                                        placeholder="{{ __('Type Email Address') }}" value="" required=""
                                        autofocus>
                                    <i class="icofont-ui-user"></i>
                                </div>
                                <div class="form-input">
                                    <input type="password" id="panel_password" name="password" class="Password"
                                        placeholder="{{ __('Type Password') }}">
                                    <span toggle="#panel_password"
                                        class="fa fa-fw fa-eye field-icon toggle-panel"></span>
                                    <i class="icofont-ui-password"></i>
                                </div>
                                <div class="form-forgot-pass">
                                    <div class="left">
                                        <input type="checkbox" name="remember" id="rp"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label for="rp">{{ __('Remember Password') }}</label>
                                    </div>
                                    <div class="right">
                                        <a href="{{ route('admin.forgot') }}">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                    </div>
                                </div>
                                <input id="authdata" type="hidden" value="{{ __('Authenticating...') }}">
                                <button class="submit-btn btn-grad">{{ __('Login') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="submit-loader">
        <img src="{{ $admstore->adminLoaderUrl }}" alt="">
    </div>
    <!--Login and Sign up Area End -->

    <!-- Dashboard Core -->
    <script src="{{ asset('assets/admin/js/vendors/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendors/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
    <!-- Fullside-menu Js-->
    <script src="{{ asset('assets/admin/plugins/fullside-menu/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/fullside-menu/waves.min.js') }}"></script>

    <script src="{{ asset('assets/admin/js/plugin.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tag-it.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- Load trumbo -->
    <script src="{{ asset('assets/admin/js/trumbowyg/plugins/resizimg/resizable-resolveconflict.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery-resizable.js') }}"></script>
    <script src="{{ asset('assets/admin/js/trumbowyg/trumbowyg.min.js') }}">
        var trumbo_lang = "en";
    </script>
    @if (file_exists(public_path() . '/assets/admin/js/trumbowyg/langs/' . $current_locale . '.min.js'))
        <script>
            trumbo_lang = "{{ str_replace('-', '_', $current_locale) }}";
        </script>
        <script src="{{ asset('assets/admin/js/trumbowyg/langs/' . $current_locale . '.min.js') }}"></script>
    @endif
    <script src="{{ asset('assets/admin/js/trumbowyg/trumbowyg-all-plugins.min.js') }}"></script>
    <!-- -->
    <script src="{{ asset('assets/admin/js/load.js') }}"></script>
    <!-- Custom Js-->
    <script src="{{ asset('assets/admin/js/custom.js') }}"></script>
    <!-- AJAX Js-->
    <script type="text/javascript">
        var categoryAttr = '{{ __('Category attributes') }}';
    </script>
    <script src="{{ asset('assets/admin/js/notify.js') }}"></script>
    <script src="{{ asset('assets/admin/js/myscript.js') }}"></script>

</body>

</html>
