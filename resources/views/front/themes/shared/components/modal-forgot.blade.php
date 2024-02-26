<!-- FORGOT MODAL -->
<div class="modal fade" id="forgot-modal" tabindex="-1" role="dialog" aria-labelledby="comment-log-reg-Title"
    aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="login-area">
                    <div class="header-area forgot-passwor-area">
                        <h4 class="title">{{ __('Forgot Password') }} </h4>
                        <p class="text">{{ __('Please Write your Email') }} </p>
                    </div>
                    <div class="login-form">

                        @include('includes.admin.form-login')

                        <form id="mforgotform" action="{{ route('user-forgot-submit') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-input">
                                <input type="email" name="email" class="User Name"
                                    placeholder="{{ __('Email Address') }}" required>
                                <i class="icofont-user-alt-5"></i>
                            </div>
                            <div class="to-login-page">
                                <a href="javascript:;" id="show-login">
                                    {{ __('Login Now') }}
                                </a>
                            </div>
                            <input class="fauthdata" type="hidden" value="{{ __('Checking...') }}">
                            <button type="submit" class="submit-btn">{{ __('SUBMIT') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FORGOT MODAL ENDS -->
