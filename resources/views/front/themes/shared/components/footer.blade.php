<!-- Footer Area Start -->
<footer class="footer" id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="footer-info-area">
                    <div class="footer-logo">
                        <a href="{{ route('front.index') }}" class="logo-link">
                            <img src="{{ $gs->footerLogoUrl }}" alt="">
                        </a>
                    </div>
                    <div class="text">
                        <p>
                            {!! $gs->footer !!}
                        </p>
                    </div>
                </div>
                <div class="fotter-social-links">
                    <ul>

                        @if ($socials->f_status == 1)
                            <li>
                                <a href="{{ $socials->facebook }}" class="facebook" target="_blank">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                        @endif

                        @if ($socials->i_status == 1)
                            <li>
                                <a href="{{ $socials->instagram }}" class="instagram" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                        @endif

                        @if ($socials->t_status == 1)
                            <li>
                                <a href="{{ $socials->twitter }}" class="twitter" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                        @endif

                        @if ($socials->l_status == 1)
                            <li>
                                <a href="{{ $socials->linkedin }}" class="linkedin" target="_blank">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </li>
                        @endif

                        @if ($socials->d_status == 1)
                            <li>
                                <a href="{{ $socials->dribble }}" class="dribbble" target="_blank">
                                    <i class="fab fa-dribbble"></i>
                                </a>
                            </li>
                        @endif

                        @if ($socials->y_status == 1)
                            <li>
                                <a href="{{ $socials->youtube }}" class="youtube" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="footer-widget info-link-widget">
                    <h4 class="title">
                        {{ __('Footer Links') }}
                    </h4>
                    <ul class="link-list">
                        <li>
                            <a href="{{ route('front.index') }}">
                                <i class="fas fa-angle-double-right"></i>{{ __('Home') }}
                            </a>
                        </li>

                        @foreach ($pfooter as $data)
                            <li>
                                <a href="{{ route('front.page', $data->slug) }}">
                                    <i class="fas fa-angle-double-right"></i>{{ $data->title }}
                                </a>
                            </li>
                        @endforeach

                        <li>
                            <a href="{{ route('front.contact') }}">
                                <i class="fas fa-angle-double-right"></i>{{ __('Contact Us') }}
                            </a>
                        </li>
                        @if ($gs->crow_policy)
                            <li>
                                <a href="{{ route('front.crowpolicy') }}">
                                    <i class="fas fa-angle-double-right"></i>{{ __('General Terms of Service') }}
                                </a>
                            </li>
                        @endif
                        @if ($gs->privacy_policy)
                            <li>
                                <a href="{{ route('front.privacypolicy') }}">
                                    <i class="fas fa-angle-double-right"></i>{{ __('Privacy Policy') }}
                                </a>
                            </li>
                        @endif
                        @if ($gs->bank_check)
                            <li>
                                <a href="{{ route('front.receipt') }}">
                                    <i class="fas fa-angle-double-right"></i>{{ __('Upload Order Receipt') }}
                                </a>
                            </li>
                        @endif
                        @if ($gs->team_show_footer == 1)
                            <li>
                                <a href="{{ route('front.team_member') }}">
                                    <i class="fas fa-angle-double-right"></i>{{ __('Team') }}
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div>
                    {!! $gs->copyright !!}
                </div>
            </div>
        </div>
    </div>

    <div class="copy-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="content">
                        <div class="content">
                            <p>COPYRIGHT {{ $gs->title }} © {{ date('Y') }}.
                                {{ $gs->company_document ? '| ' . $gs->document_name . ' - ' . $gs->company_document . ' |' : '' }}
                                {{ __('All Rights Reserved') }}.
                            </p>
                            @if (!config('features.disable_developed_by_us') && !config('features.marketplace'))
                                <p>{{ __('Developed By') }} <a id="agcrow"
                                        href="https://crowtech.digital/">CrowTech</a></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Area End -->
