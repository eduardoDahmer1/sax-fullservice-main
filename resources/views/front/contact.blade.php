@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')

@section('content')

    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pages">
                        <li>
                            <a href="{{ route('front.index') }}">
                                {{ __('Home') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('front.contact') }}">
                                {{ __('Contact Us') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->


    <!-- Contact Us Area Start -->
    <section class="contact-us">
        <div class="container-lg m-auto">
            <div class="row justify-content-center align-items-center">
                <div class="col-xl-8">
                    <div class="contact-section-title text-center w-75 m-auto">
                        <h3>Envía tu mensaje y te responderemos a la brevedad.</h3>
                        <p class='lead'>Bienvenido al mundo de SAX. La tienda de artículos de lujo más grande de Sudamérica. Por favor, siéntase libre de enviar su mensaje.</p>
                        
                        {{-- {!! $ps->contact_title !!}
                        {!! $ps->contact_text !!} --}}
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-12">
                    <div class="contact-form m-auto">
                        <div class="gocover"
                            style="background: url({{ asset('storage/images/' . $gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <form id="contactform" action="{{ route('front.contact.submit') }}" method="POST">
                            {{ csrf_field() }}
                            @include('includes.admin.form-both')

                            <div class="row justify-content-center w-100 m-auto">
                                <div class="col-xl-4 col-lg-5">
                                    <div class="form-input">
                                        <input type="text" name="name" placeholder="{{ __('Name') }} *" required="">
                                        <i class="icofont-user-alt-5"></i>
                                    </div>
                                    <div class="form-input">
                                        <input type="text" name="phone" placeholder="{{ __('Phone Number') }} *">
                                        <i class="icofont-ui-call"></i>
                                    </div>
                                    <div class="form-input">
                                        <input type="email" name="email" placeholder="{{ __('Email Address') }} *"
                                            required="">
                                        <i class="icofont-email"></i>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-5">
                                    <div class="form-input">
                                        <textarea name="text" placeholder="{{ __('Your Message') }} *" required=""></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4 justify-content-center align-items-center">
                                <div class="col-xl-4 col-lg-5 d-flex justify-content-center">
                                    @if ($gs->is_capcha == 1)
                                        <ul class="captcha-area">
                                            <li>
                                                <p><img class="codeimg1" src="{{ asset('storage/images/capcha_code.png') }}"
                                                        alt="">
                                                    <i class="fas fa-sync-alt pointer refresh_code"></i>
                                                </p>

                                            </li>
                                            <li>
                                                <input name="codes" type="text" class="input-field"
                                                    placeholder="{{ __('Enter Code') }}" required="">
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                <div class="mt-4 col-xl-4 col-lg-5 d-flex justify-content-center">
                                    <input type="hidden" name="to" value="{{ $ps->contact_email }}">
                                    <button class="submit-btn" type="submit">{{ __('Send Message') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mt-5 justify-content-center align-items-center">
                <div class="col-xl-12">
                    <div class="row justify-content-center">
                            @if ($ps->site != null || $ps->email != null)
                            <div class="col-xl-3 contact-info">
                                <div class="left ">
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M4.222 19.778a4.983 4.983 0 0 0 3.535 1.462 4.986 4.986 0 0 0 3.536-1.462l2.828-2.829-1.414-1.414-2.828 2.829a3.007 3.007 0 0 1-4.243 0 3.005 3.005 0 0 1 0-4.243l2.829-2.828-1.414-1.414-2.829 2.828a5.006 5.006 0 0 0 0 7.071zm15.556-8.485a5.008 5.008 0 0 0 0-7.071 5.006 5.006 0 0 0-7.071 0L9.879 7.051l1.414 1.414 2.828-2.829a3.007 3.007 0 0 1 4.243 0 3.005 3.005 0 0 1 0 4.243l-2.829 2.828 1.414 1.414 2.829-2.828z"></path><path d="m8.464 16.95-1.415-1.414 8.487-8.486 1.414 1.415z"></path></svg>
                                    </div>
                                </div>
                                <div class="content d-flex align-self-center text-center">
                                    <div class="">
                                        @if ($ps->site != null && $ps->email != null)
                                            <a href="{{ $ps->site }}" target="_blank">{{ $ps->site }}</a>
                                            <a href="mailto:{{ $ps->email }}">{{ $ps->email }}</a>
                                        @elseif($ps->site != null)
                                            <a href="{{ $ps->site }}" target="_blank">{{ $ps->site }}</a>
                                        @else
                                            <a href="mailto:{{ $ps->email }}">{{ $ps->email }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($ps->street != null)
                            <div class="col-xl-3 contact-info">
                                <div class="left ">
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M12 2C7.589 2 4 5.589 4 9.995 3.971 16.44 11.696 21.784 12 22c0 0 8.029-5.56 8-12 0-4.411-3.589-8-8-8zm0 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"></path></svg>
                                    </div>
                                </div>
                                <div class="content d-flex align-self-center text-center">
                                    <div class="">
                                        @if ($ps->street != null)
                                            {!! $ps->street !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($ps->phone != null || $ps->fax != null)
                        <div class="col-xl-3 contact-info">
                            <div class="left ">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="m20.487 17.14-4.065-3.696a1.001 1.001 0 0 0-1.391.043l-2.393 2.461c-.576-.11-1.734-.471-2.926-1.66-1.192-1.193-1.553-2.354-1.66-2.926l2.459-2.394a1 1 0 0 0 .043-1.391L6.859 3.513a1 1 0 0 0-1.391-.087l-2.17 1.861a1 1 0 0 0-.29.649c-.015.25-.301 6.172 4.291 10.766C11.305 20.707 16.323 21 17.705 21c.202 0 .326-.006.359-.008a.992.992 0 0 0 .648-.291l1.86-2.171a.997.997 0 0 0-.085-1.39z"></path></svg>
                                </div>
                            </div>
                            <div class="content d-flex align-self-center text-center">
                                <div class="">
                                    @if ($ps->phone != null && $ps->fax != null)
                                        <a href="tel:{{ $ps->phone }}">{{ $ps->phone }}</a>
                                        <a href="tel:{{ $ps->fax }}">{{ $ps->fax }}</a>
                                    @elseif($ps->phone != null)
                                        <a href="tel:{{ $ps->phone }}">{{ $ps->phone }}</a>
                                    @else
                                        <a href="tel:{{ $ps->fax }}">{{ $ps->fax }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-lg-4">
                            <div class="social-links">
                                <h4 class="title">{{ __('Find Us Here') }} :</h4>
                                <ul>
                                    @if (App\Models\Socialsetting::find(1)->f_status == 1)
                                        <li>
                                            <a href="{{ App\Models\Socialsetting::find(1)->facebook }}" class="facebook"
                                                target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="transform: ;msFilter:;"><path d="M20 3H4a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h8.615v-6.96h-2.338v-2.725h2.338v-2c0-2.325 1.42-3.592 3.5-3.592.699-.002 1.399.034 2.095.107v2.42h-1.435c-1.128 0-1.348.538-1.348 1.325v1.735h2.697l-.35 2.725h-2.348V21H20a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1z"></path></svg>                                    
                                            </a>
                                        </li>
                                    @endif
                                    @if (App\Models\Socialsetting::find(1)->i_status == 1)
                                        <li>
                                            <a href="{{ App\Models\Socialsetting::find(1)->instagram }}" class="instagram"
                                                target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="transform: ;msFilter:;"><path d="M20.947 8.305a6.53 6.53 0 0 0-.419-2.216 4.61 4.61 0 0 0-2.633-2.633 6.606 6.606 0 0 0-2.186-.42c-.962-.043-1.267-.055-3.709-.055s-2.755 0-3.71.055a6.606 6.606 0 0 0-2.185.42 4.607 4.607 0 0 0-2.633 2.633 6.554 6.554 0 0 0-.419 2.185c-.043.963-.056 1.268-.056 3.71s0 2.754.056 3.71c.015.748.156 1.486.419 2.187a4.61 4.61 0 0 0 2.634 2.632 6.584 6.584 0 0 0 2.185.45c.963.043 1.268.056 3.71.056s2.755 0 3.71-.056a6.59 6.59 0 0 0 2.186-.419 4.615 4.615 0 0 0 2.633-2.633c.263-.7.404-1.438.419-2.187.043-.962.056-1.267.056-3.71-.002-2.442-.002-2.752-.058-3.709zm-8.953 8.297c-2.554 0-4.623-2.069-4.623-4.623s2.069-4.623 4.623-4.623a4.623 4.623 0 0 1 0 9.246zm4.807-8.339a1.077 1.077 0 0 1-1.078-1.078 1.077 1.077 0 1 1 2.155 0c0 .596-.482 1.078-1.077 1.078z"></path><circle cx="11.994" cy="11.979" r="3.003"></circle></svg>                                    
                                            </a>
                                        </li>
                                    @endif
        
                                    @if (App\Models\Socialsetting::find(1)->t_status == 1)
                                        <li>
                                            <a href="{{ App\Models\Socialsetting::find(1)->twitter }}" class="twitter"
                                                target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="transform: ;msFilter:;"><path d="M19.633 7.997c.013.175.013.349.013.523 0 5.325-4.053 11.461-11.46 11.461-2.282 0-4.402-.661-6.186-1.809.324.037.636.05.973.05a8.07 8.07 0 0 0 5.001-1.721 4.036 4.036 0 0 1-3.767-2.793c.249.037.499.062.761.062.361 0 .724-.05 1.061-.137a4.027 4.027 0 0 1-3.23-3.953v-.05c.537.299 1.16.486 1.82.511a4.022 4.022 0 0 1-1.796-3.354c0-.748.199-1.434.548-2.032a11.457 11.457 0 0 0 8.306 4.215c-.062-.3-.1-.611-.1-.923a4.026 4.026 0 0 1 4.028-4.028c1.16 0 2.207.486 2.943 1.272a7.957 7.957 0 0 0 2.556-.973 4.02 4.02 0 0 1-1.771 2.22 8.073 8.073 0 0 0 2.319-.624 8.645 8.645 0 0 1-2.019 2.083z"></path></svg>                                    
                                            </a>
                                        </li>
                                    @endif
        
                                    @if (App\Models\Socialsetting::find(1)->l_status == 1)
                                        <li>
                                            <a href="{{ App\Models\Socialsetting::find(1)->linkedin }}" class="linkedin"
                                                target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="transform: ;msFilter:;"><path d="M20 3H4a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM8.339 18.337H5.667v-8.59h2.672v8.59zM7.003 8.574a1.548 1.548 0 1 1 0-3.096 1.548 1.548 0 0 1 0 3.096zm11.335 9.763h-2.669V14.16c0-.996-.018-2.277-1.388-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248h-2.667v-8.59h2.56v1.174h.037c.355-.675 1.227-1.387 2.524-1.387 2.704 0 3.203 1.778 3.203 4.092v4.71z"></path></svg>                                    
                                            </a>
                                        </li>
                                    @endif
        
                                    @if (App\Models\Socialsetting::find(1)->d_status == 1)
                                        <li>
                                            <a href="{{ App\Models\Socialsetting::find(1)->dribble }}" class="dribbble"
                                                target="_blank">
                                                <i class="fab fa-dribbble"></i>
                                            </a>
                                        </li>
                                    @endif
        
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- Contact Us Area End-->

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $(".refresh_code").click();
            }, 10);
        });
    </script>
@endsection
