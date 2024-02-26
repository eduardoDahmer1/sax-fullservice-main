@if ($gs->is_popup == 1)
    @if (isset($visited))
        <div style="display:none">
            @if (!empty($gs->popup_background))
                <img src="{{ $gs->popup_backgroundUrl }}">
            @endif
        </div>

        <!--  Starting of subscribe-pre-loader Area   -->
        <div class="subscribe-preloader-wrap" id="subscriptionForm" style="display: none;">
            <div class="popup-wrapper">
               
                    <div class="subscribePreloader__thumb">
                        @if (!empty($gs->popup_background))
                        <a href="https://shop.saxdepartment.com/category/fashion-sale-47">
                            <img src="{{ $gs->popup_backgroundUrl }}">
                        </a>
                        @endif
                        @if (!empty($gs->popup_title) || !empty($gs->popup_text) || $gs->is_newsletter_popup == 1)
                            <div class="subscribePreloader__text text-center">
                                @if (empty($gs->popup_background))
                                    <span class="preload-close" style="align-self: end; margin-top: 57px;"><i
                                            class="fas fa-times"></i></span>
                                @endif
                                @unless(empty($gs->popup_title))
                                    <h1>{{ $gs->popup_title }}</h1>
                                @endunless
                                @unless(empty($gs->popup_text))
                                    <p>{{ $gs->popup_text }}</p>
                                @endunless
                                @if ($gs->is_newsletter_popup == 1)
                                    <form action="{{ route('front.subscribe') }}" id="subscribeform" method="POST">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <input type="email" name="email"
                                                placeholder="{{ __('Enter Your Email Address') }}" required>
                                            <button id="sub-btn" type="submit">{{ __('SUBSCRIBE') }}</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        @endif
                        @if ($gs->popup_background)
                            <span class="preload-close" style="align-self: end;"><i class="fas fa-times"></i></span>
                        @endif
                    </div>
            </div>
        </div>
        <!--  Ending of subscribe-pre-loader Area   -->
    @endif
@endif
