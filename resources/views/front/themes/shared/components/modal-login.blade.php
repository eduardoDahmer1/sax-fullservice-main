<!-- LOGIN MODAL -->
<div class="modal fade" id="comment-log-reg" tabindex="-1" role="dialog" aria-labelledby="comment-log-reg-Title"
    aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <nav class="comment-log-reg-tabmenu">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link login active" id="nav-log-tab1" data-toggle="tab" href="#nav-log1"
                            role="tab" aria-controls="nav-log" aria-selected="true">
                            {{ __('Login') }}
                        </a>
                        <a class="nav-item nav-link" id="nav-reg-tab1" data-toggle="tab" href="#nav-reg1" role="tab"
                            aria-controls="nav-reg" aria-selected="false">
                            {{ __('Register') }}
                        </a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-log1" role="tabpanel"
                        aria-labelledby="nav-log-tab1">
                        <div class="login-area">
                            <div class="header-area">
                                <h4 class="title">{{ __('LOGIN NOW') }}</h4>
                            </div>
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
                                            <input type="checkbox" name="remember" id="mrp"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label for="mrp">{{ __('Remember Password') }}</label>
                                        </div>
                                        <div class="right">
                                            <a href="javascript:;" id="show-forgot">
                                                {{ __('Forgot Password?') }}
                                            </a>
                                        </div>
                                    </div>
                                    <input type="hidden" name="modal" value="1">
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
                    <div class="tab-pane fade" id="nav-reg1" role="tabpanel" aria-labelledby="nav-reg-tab1">
                        <div class="login-area signup-area">
                            <div class="header-area">
                                <h4 class="title">{{ __('Signup Now') }}</h4>
                            </div>
                            <div class="login-form signup-form">

                                @include('includes.admin.form-login')

                                <form class="mregisterform" action="{{ route('user-register-submit') }}"
                                    method="POST">
                                    {{ csrf_field() }}

                                    <div class="form-input">
                                        <input type="text" class="User Name" name="name" pattern="^(\S*)\s+(.*)$"
                                            placeholder="{{ __('Full Name') }}" required>
                                        <i class="icofont-user-alt-5"></i>
                                    </div>

                                    <div class="form-input">
                                        <input type="email" class="User Name" name="email"
                                            placeholder="{{ __('Email Address') }}" required>
                                        <i class="icofont-email"></i>
                                    </div>

                                    <div class="form-input">
                                        <input type="password" class="Password" name="password"
                                            placeholder="{{ __('Password') }}" required>
                                        <i class="icofont-ui-password"></i>
                                    </div>

                                    <div class="form-input">
                                        <input type="password" class="Password" name="password_confirmation"
                                            placeholder="{{ __('Confirm Password') }}" required>
                                        <i class="icofont-ui-password"></i>
                                    </div>
                                    <div class="form-input">
                                        <input placeholder="{{ __('Date of Birth') }}" class="textbox-n" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="birthday" name="birthday" required/>
                                        
                                    </div>
                                    <div class="form-input">
                                        <select class="form-control" name="gender" id="gender">
                                            <option value="">{{ __("Gender") }}</option>
                                            <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}> {{ __("Male") }}</option>
                                            <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>{{ __("Female") }}</option>
                                            <option value="O" {{ old('gender') == 'O' ? 'selected' : '' }}>{{ __("Other") }}</option>
                                            <option value="N" {{ old('gender') == 'N' ? 'selected' : '' }}>{{ __("Not Declared") }}</option>
                                        </select>
                                    </div>
                                    @if ($gs->is_capcha == 1)
                                        <ul class="captcha-area">
                                            <li>
                                                <p><img class="codeimg1"
                                                        src="{{ asset('storage/images/capcha_code.png') }}"
                                                        alt="">
                                                    <i class="fas fa-sync-alt pointer refresh_code "></i>
                                                </p>
                                            </li>
                                        </ul>

                                        <div class="form-input">
                                            <input type="text" class="Password" name="codes"
                                                placeholder="{{ __('Enter Code') }}" required>
                                            <i class="icofont-refresh"></i>
                                        </div>
                                    @endif

                                    @php
                                        $url = $gs->privacy_policy ? true : false;
                                    @endphp
                                    <div class="form-forgot-pass">
                                        <div class="left">
                                            <input type="checkbox" name="agree_privacy_policy"
                                                id="agree_privacy_policy">
                                            <label for="agree_privacy_policy">{{ __("I agree with the") }} <a target="_blank"
                                                    href="{{ $url ? route('front.privacypolicy') : '' }}"> {{ __("Privacy Policy") }}</a>.</label>
                                        </div>
                                    </div>

                                    <input class="mprocessdata" type="hidden" value="{{ __('Processing...') }}">
                                    <button type="submit" class="submit-btn">{{ __('Register') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- LOGIN MODAL ENDS -->
