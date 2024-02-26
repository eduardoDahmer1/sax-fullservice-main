<!DOCTYPE html>
<html lang="{{ $current_locale }}" class="no-js">

<head>
    <meta charset="utf-8">
    <title>Em breve {{ $gs->title }}</title>
    <meta name="description" content="{{ $gs->title }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="CrowTech">

    <!-- ================= Favicons ================== -->
    <!-- Standard -->
    <link rel="icon" type="image/x-icon" href="{{ $gs->faviconUrl }}" />
    <!-- ============== Resources style ============== -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.cloudcrow.com.br/TemplatesEmBreve/Template2/html/css/style.css" />
    <!-- Modernizr runs quickly on page load to detect features -->
    <script src="https://cdn.cloudcrow.com.br/TemplatesEmBreve/Template2/html/js/modernizr.custom.js"></script>
</head>
<style>
    .img-fluide {
        width: 20em;
    }

    .padd {
        margin-left: 1em;
    }

    .wrap {
        position: fixed;
        z-index: -30;
        top: 0;
        left: 0;
        overflow: hidden;
        width: 100vw;
        height: 100vh;
        margin: auto;
        background: linear-gradient(-141deg, {{ $gs->colors }} 0%, {{ $gs->header_color }} 100%);
    }

    @media only screen and(max-width:480px) {
        .fullpagina .section#section0 .content-inside-section .container-inside {
            padding: 19rem 15px;
        }
    }

    footer {
        position: fixed;
    }

    .fullpagina .section#section0 .content-inside-section .container-inside {
        position: relative;
        display: table;
        height: 100vh;
        width: 100%;
        margin: 0 auto;
        padding: 0 100px 80px;
        text-align: left;

    }
</style>

<body>
    <div id="loading">
        <div class="loader">
            <span class="dots">.</span>
            <span class="dots">.</span>
            <span class="dots">.</span>
            <span class="dots">.</span>
            <span class="dots">.</span>
            <span class="dots">.</span>
        </div>
    </div>
    <div class="wrap">
        <canvas id="liquid"></canvas>
    </div>
    <div id="fullpage" class="fullpagina">
        <div class="section" id="section0">
            <section class="content-inside-section">
                <div class="container">
                    <div class="container-inside">
                        <div class="main-content align-center">
                            <img src="{{ $gs->footerLogoUrl }}" alt="Our company logo" class="img-fluide"
                                style="max-width:400px; max-height:400px;" />
                            <h1>
                                Em breve... <br> ...DevOps!
                            </h1>
                            <p class="on-home">Estamos trabalhando muito para oferecer a você a melhor experiência!
                            </p>
                            <br>
                            <div class="command">
                                <div class="col-12 col-xl-6 footer-nav">
                                    <ul class="on-right" style="font-size: 2em;">
                                        @php
                                            $social = App\Models\Socialsetting::find(1);
                                        @endphp

                                        @if ($social->f_status == 1)
                                            <a href="{{ $social->facebook }}" target="_blank"><i
                                                    class="fab fa-facebook-f padd"></i></a>
                                        @endif
                                        @if ($social->t_status == 1)
                                            <a href="{{ $social->twitter }}" target="_blank"><i
                                                    class="fab fa-twitter padd"></i></a>
                                        @endif
                                        @if ($social->y_status == 1)
                                            <a href="{{ $social->youtube }}" target="_blank"><i
                                                    class="fab fa-youtube padd"></i></a>
                                        @endif
                                        @if ($social->l_status == 1)
                                            <a href="{{ $social->linkedin }}" target="_blank"><i
                                                    class="fab fa-linkedin-in padd"></i></a>
                                        @endif
                                        @if ($social->i_status == 1)
                                            <a href="{{ $social->instagram }}" target="_blank"><i
                                                    class="fab fa-instagram padd"></i></a>
                                        @endif
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <footer>
        <div class="line"></div>
        <div class="row justify-content-center">
            <div class="col-md-auto footer-copyright">
                <p>© 2022 {{ $gs->title }}. Desenvolvido por <a
                        href="https://crowtech.digital/"><strong>CrowTech</strong></a></p>
            </div>

        </div>
    </footer>
    <script src="https://cdn.cloudcrow.com.br/TemplatesEmBreve/Template2/html/js/jquery.min.js"></script>
    <script src="https://cdn.cloudcrow.com.br/TemplatesEmBreve/Template2/html/js/jquery.easings.min.js"></script>
    <script src="https://cdn.cloudcrow.com.br/TemplatesEmBreve/Template2/html/js/bootstrap.min.js"></script>
    <script src="https://cdn.cloudcrow.com.br/TemplatesEmBreve/Template2/html/js/jquery.countdown.js"></script>
    <script src="https://cdn.cloudcrow.com.br/TemplatesEmBreve/Template2/html/js/jquery.fullPage.js"></script>
    <script src="https://cdn.cloudcrow.com.br/TemplatesEmBreve/Template2/html/js/liquid.js"></script>
    <script src="https://cdn.cloudcrow.com.br/TemplatesEmBreve/Template2/html/js/jquery.detect_swipe.min.js"></script>
    <script src="https://cdn.cloudcrow.com.br/TemplatesEmBreve/Template2/html/js/featherlight.js"></script>
    <script src="https://cdn.cloudcrow.com.br/TemplatesEmBreve/Template2/html/js/featherlight.gallery.js"></script>
    <script src="https://cdn.cloudcrow.com.br/TemplatesEmBreve/Template2/html/js/main.js"></script>

    <script>
        // Função que altera cor dos circulos flutuantes
        lava0 = new LavaLamp(screen.width, screen.height, 5, "{!! $gs->header_color !!}", "{!! $gs->colors !!}");
        run();
    </script>
</body>

</html>
