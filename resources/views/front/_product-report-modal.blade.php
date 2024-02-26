@if (Auth::check())
    {{-- REPORT MODAL SECTION --}}
    <div class="modal fade" id="report-modal" tabindex="-1" role="dialog" aria-labelledby="report-modal-Title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="gocover"
                        style="background: url({{ asset('storage/images/' . $gs->loader) }})
                        no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                    </div>
                    <div class="login-area">
                        <div class="header-area forgot-passwor-area">
                            <h4 class="title">{{ __('REPORT PRODUCT') }}</h4>
                            <p class="text">{{ __('Please give the following details') }}</p>
                        </div>
                        <div class="login-form">
                            <form id="reportform" action="{{ route('product.report') }}" method="POST">
                                @include('includes.admin.form-login')
                                {{ csrf_field() }}
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <input type="hidden" name="product_id" value="{{ $productt->id }}">
                                <div class="form-input">
                                    <input type="text" name="title" class="User Name"
                                        placeholder="{{ __('Enter Report Title') }}" required>
                                    <i class="icofont-notepad"></i>
                                </div>
                                <div class="form-input">
                                    <textarea name="note" class="User Name" placeholder="{{ __('Enter Report Note') }}" required>
                                    </textarea>
                                </div>
                                <button type="submit" class="submit-btn">{{ __('SUBMIT') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- REPORT MODAL SECTION ENDS --}}
@endif
