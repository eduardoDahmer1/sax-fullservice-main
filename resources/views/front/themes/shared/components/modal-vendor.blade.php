<!-- VENDOR LOGIN MODAL -->
@if (config('features.marketplace') && $gs->reg_vendor == 1)
    <div class="modal fade" id="vendor-login" tabindex="-1" role="dialog" aria-labelledby="vendor-login-Title"
        aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" style="transition: .5s;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <nav class="comment-log-reg-tabmenu">
                        <div class="nav nav-tabs" id="nav-tab1" role="tablist">
                            <a class="nav-item nav-link login active" id="nav-log-tab11" data-toggle="tab"
                                href="#nav-log11" role="tab" aria-controls="nav-log" aria-selected="true">
                                {{ __('Vendor Login') }}
                            </a>
                            <a class="nav-item nav-link" id="nav-reg-tab11" data-toggle="tab" href="#nav-reg11"
                                role="tab" aria-controls="nav-reg" aria-selected="false">
                                {{ __('Vendor Registration') }}
                            </a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-log11" role="tabpanel"
                            aria-labelledby="nav-log-tab">
                            <div class="login-area">
                                <div class="login-form signin-form">

                                    @include('includes.admin.form-login')

                                    <form class="mloginform" action="{{ route('user.login.submit') }}" method="POST">
                                        {{ csrf_field() }}
                                        <div class="form-input">
                                            <input type="email" name="email"
                                                placeholder="{{ __('Type Email Address') }}" required>
                                            <i class="icofont-user-alt-5"></i>
                                        </div>
                                        <div class="form-input">
                                            <input type="password" class="Password" name="password"
                                                placeholder="{{ __('Type Password') }}" required>
                                            <i class="icofont-ui-password"></i>
                                        </div>
                                        <div class="form-forgot-pass">
                                            <div class="left">
                                                <input type="checkbox" name="remember" id="mrp1"
                                                    {{ old('remember') ? 'checked' : '' }}>
                                                <label for="mrp1">{{ __('Remember Password') }}</label>
                                            </div>
                                            <div class="right">
                                                <a href="javascript:;" id="show-forgot1">
                                                    {{ __('Forgot Password?') }}
                                                </a>
                                            </div>
                                        </div>
                                        <input type="hidden" name="modal" value="1">
                                        <input type="hidden" name="vendor" value="1">
                                        <input class="mauthdata" type="hidden" value="{{ __('Authenticating...') }}">
                                        <button type="submit" class="submit-btn">{{ __('Login') }}</button>

                                        @if ($socials->f_check == 1 || $socials->g_check == 1)
                                            <div class="social-area">
                                                <h3 class="title">{{ __('Or') }}</h3>
                                                <p class="text">{{ __('Sign In with social media') }}</p>
                                                <ul class="social-links">

                                                    @if ($socials->f_check == 1)
                                                        <li>
                                                            <a href="{{ route('social-provider', 'facebook') }}">
                                                                <i class="fab fa-facebook-f"></i>
                                                            </a>
                                                        </li>
                                                    @endif

                                                    @if ($socials->g_check == 1)
                                                        <li>
                                                            <a href="{{ route('social-provider', 'google') }}">
                                                                <i class="fab google"></i>
                                                            </a>
                                                        </li>
                                                    @endif

                                                </ul>
                                            </div>
                                        @endif

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<!-- VENDOR LOGIN MODAL ENDS -->
