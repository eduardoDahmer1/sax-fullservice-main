<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="CrowTech">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>{{ $gs->title }}</title>
    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $gs->favicon }}" />
    <!-- Bootstrap -->
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" />
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/fontawesome.css') }}">
    <!-- icofont -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/ionicons.min.css') }}">

    <!-- Sidemenu Css -->
    <link href="{{ asset('assets/admin/plugins/fullside-menu/css/dark-side-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/plugins/fullside-menu/waves.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets/admin/css/plugin.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets/admin/css/jquery.tagit.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-coloroicker.css') }}">
    <!-- trumbowyg css -->
    <link href="{{ asset('assets/admin/css/trumbowyg-all.css') }}" rel="stylesheet" />
    <!-- Main Css -->

    <!-- stylesheet -->
    @if (DB::table('admin_languages')->where('is_default', '=', 1)->first()->rtl == 1)
        <link href="{{ asset('assets/admin/css/rtl/style.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/rtl/custom.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/rtl/responsive.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/common.css') }}" rel="stylesheet" />
    @else
        <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/custom.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/responsive.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/css/common.css') }}" rel="stylesheet" />
    @endif

    @yield('styles')

</head>

<body>
    <div class="page">
        <div class="page-main">
            <!-- Header Menu Area Start -->
            <div class="header">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <div class="menu-toggle-button">
                            <a class="nav-link" href="javascript:;" id="sidebarCollapse">
                                <div class="my-toggl-icon">
                                    <span class="bar1"></span>
                                    <span class="bar2"></span>
                                    <span class="bar3"></span>
                                </div>
                            </a>
                        </div>

                        <div class="right-eliment">
                            <ul class="list">
                                <li class="bell-area">
                                    <div id="url_adminstore">
                                        <div>{{ $admstore->title }}</div>
                                        <div>{{ $admstore->domain }}</div>
                                    </div>

                                </li>
                                <li class="bell-area">
                                    <a id="notf_conv" class="dropdown-toggle-1" target="_blank"
                                        href="{{ route('front.index') }}">
                                        <i class="ion-ios-world"></i>
                                    </a>

                                </li>
                                <li class="bell-area">
                                    <a id="notifications" class="dropdown-toggle-1" href="javascript:;">
                                        <i class="ion-android-notifications"></i>
                                        <span data-href="{{ route('admin.notifications-count') }}"
                                            id="notifications-count">{{ $notifications_count }}</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdownmenu-wrapper"
                                            data-href="{{ route('admin.notifications-show') }}"
                                            id="notifications-show">
                                        </div>
                                    </div>
                                </li>

                                <li class="login-profile-area">
                                    <a class="dropdown-toggle-1" href="javascript:;">
                                        <div class="user-img">
                                            <img src="{{ Auth::guard('admin')->user()->photoUrl }}" alt="">
                                        </div>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdownmenu-wrapper">
                                            <ul>
                                                <h5>{{ __('Welcome!') }}</h5>
                                                <li>
                                                    <a href="{{ route('admin.profile') }}"><i class="fas fa-user"></i>
                                                        {{ __('Edit Profile') }}</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.password') }}"><i class="fas fa-cog"></i>
                                                        {{ __('Change Password') }}</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.logout') }}"><i
                                                            class="fas fa-power-off"></i> {{ __('Logout') }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header Menu Area End -->
            <div class="wrapper">
                <!-- Side Menu Area Start -->
                <nav id="sidebar" class="nav-sidebar">
                    <ul class="list-unstyled components" id="accordion">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="wave-effect active"><i
                                    class="ion-pie-graph"></i>{{ __('Dashboard') }}</a>
                        </li>

                        @include('includes.admin.roles.super')

                    </ul>
                </nav>
                <!-- Main Content Area Start -->
                @yield('content')
                <!-- Main Content Area End -->
            </div>
        </div>
    </div>

    @php
        $curr = \App\Models\Currency::where('is_default', '=', 1)->first();
    @endphp
    <script type="text/javascript">
        var mainurl = "{{ url('/') }}";
        var admin_loader = {{ $gs->is_admin_loader }};
        var whole_sell = {{ $gs->wholesell }};
        var getattrUrl = '{{ route('admin-prod-getattributes') }}';
        var curr = {!! json_encode($curr) !!};
        // console.log(curr);
    </script>


    <!-- Dashboard Core -->
    <script src="{{ asset('assets/admin/js/vendors/jquery-3.3.1.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var url = "{{route('admin.notifications-show')}}";
                $.ajax({
                    url: '/admin/notifications/show',
                    method: 'GET',
                });
            });
     </script>
    <script src="{{ asset('assets/admin/js/vendors/vue.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendors/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
    <!-- Fullside-menu Js-->
    <script src="{{ asset('assets/admin/plugins/fullside-menu/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/fullside-menu/waves.min.js') }}"></script>

    <script src="{{ asset('assets/admin/js/plugin.js') }}"></script>
    <script src="{{ asset('assets/admin/js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tag-it.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/notify.js') }}"></script>

    <script src="{{ asset('assets/admin/js/jquery.canvasjs.min.js') }}"></script>
    <!-- Load trumbo -->
    <script src="{{ asset('assets/admin/js/trumbowyg/plugins/resizimg/resizable-resolveconflict.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery-resizable.js') }}"></script>
    <script src="{{ asset('assets/admin/js/trumbowyg/trumbowyg.min.js') }}">
        var trumbo_lang = "en";
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
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

    <script src="{{ asset('assets/admin/js/myscript.js') }}"></script>

    @yield('scripts')

    @if ($gs->is_admin_loader == 0)
        <style>
            div#geniustable_processing {
                display: none !important;
            }
        </style>
    @endif

</body>

</html>
