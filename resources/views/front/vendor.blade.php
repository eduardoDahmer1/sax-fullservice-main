@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')
    <style>
        .super-title {
            font-size: 60px;
            line-height: 50px;
            font-weight: bold;
            color: #00000;
            text-align: center;
            padding-bottom: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #00000;
        }

        iframe {
            width: 100%;
            height: 450px;
        }
    </style>
    <!-- Vendor Area Start -->
    <div class="vendor-banner"
        style="background: url({{ $vendor->shop_image != null ? asset('storage/images/vendorbanner/' . $vendor->shop_image) : '' }}); background-repeat: no-repeat; background-size: cover ;background-position: center;{!! $vendor->shop_image != null ? '' : 'background-color:' . $gs->vendor_color !!} ">

    </div>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <p class="super-title" align="center">
                    {{ $vendor->shop_name }}
                </p>
            </div>
        </div>
        <div align="center">
            <div class="row">
                <div class="col-lg-4">
                    <div class="content">
                        <p class="title">
                            {{ __('Corporate Name') }}
                        </p>
                        <h4 class="sub-title">
                            {{ $vendor->vendor_corporate_name }}
                        </h4>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="content">
                        <p class="title">
                            {{ __('CNPJ') }}
                        </p>
                        <h4 class="sub-title">
                            {{ $vendor->vendor_document }}
                        </h4>
                    </div>
                </div>
                <div class="col-lg-4">
                    <p class="title">{{ __('Phone') }}(s)</p>
                    <h4 class="sub-title">
                        {{ $vendor->vendor_phone }}
                    </h4>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-4">
                    <p class="title">Funcionamento</p>
                    <h4 class="sub-title">
                        {{ $vendor->vendor_opening_hours }}
                    </h4>
                </div>
                <div class="col-lg-4">
                    <p class="title">{{ __('Payment') }}</p>
                    <h4 class="sub-title">
                        {{ $vendor->vendor_payment_methods }}
                    </h4>
                </div>
                <div class="col-lg-4">
                    <p class="title">{{ __('Delivery Info') }}</p>
                    <h4 class="sub-title">
                        {{ $vendor->vendor_delivery_info }}
                    </h4>
                </div>
            </div>
        </div>
        @if ($vendor->shop_details)
            <br /><br>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p class="title">{{ __('Details') }}</p>
                    <h4 class="sub-title">
                        <div class="map">
                            {{ $vendor->shop_details }}
                        </div>
                    </h4>
                </div>
            </div>
        @endif
        @if ($vendor->vendor_map_embed)
            <br /><br>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p class="title">{{ __('Map') }}</p>
                    <h4 class="sub-title">
                        <div class="map">
                            {!! $vendor->vendor_map_embed !!}
                        </div>
                    </h4>
                </div>
            </div>
        @endif
    </div>
    {{-- Info Area Start --}}
    <section class="info-area">
        <div class="container">


            @foreach ($services_vendor->chunk(4) as $chunk)
                <div class="row">

                    <div class="col-lg-12 p-0">
                        <div class="info-big-box">
                            <div class="row">
                                @foreach ($chunk as $service)
                                    <div class="col-6 col-xl-3 p-0">
                                        <div class="info-box">
                                            <div class="icon">
                                                <img src="{{ asset('storage/images/services/' . $service->photo) }}">
                                            </div>
                                            <div class="info">
                                                <div class="details">
                                                    <h4 class="title">{{ $service->title }}</h4>
                                                    <p class="text">
                                                        {{ $service->details }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach


        </div>
    </section>
    {{-- Info Area End --}}




    <!-- SubCategori Area Start -->
    <section class="sub-categori">
        <div class="container">
            <div class="row">


                <div class="col-lg-12 order-first order-lg-last">
                    <div class="right-area">

                        @if (count($vprods) > 0)
                            @include('includes.vendor-filter')

                            <div class="categori-item-area">
                                {{-- <div id="ajaxContent"> --}}
                                <div class="row">

                                    @foreach ($vprods as $prod)
                                        @include('includes.product.vendor')
                                    @endforeach

                                </div>
                                <div class="page-center category">
                                    {!! $vprods->appends(['sort' => request()->input('sort'), 'min' => request()->input('min'), 'max' => request()->input('max')])->links() !!}
                                </div>
                                {{-- </div> --}}
                            </div>
                        @else
                            <div class="page-center">
                                <h4 class="text-center">{{ __('No Product Found.') }}</h4>
                            </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- SubCategori Area End -->


    @if (Auth::guard('web')->check())
        {{-- MESSAGE VENDOR MODAL --}}

        <div class="message-modal">
            <div class="modal" id="vendorform1" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel1"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="vendorformLabel1">{{ __('Send Message') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid p-0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="contact-form">

                                            <form id="emailreply">
                                                {{ csrf_field() }}
                                                <ul>

                                                    <li>
                                                        <input type="text" class="input-field" readonly=""
                                                            placeholder="Send To {{ $vendor->shop_name }}" readonly="">
                                                    </li>

                                                    <li>
                                                        <input type="text" class="input-field" id="subj"
                                                            name="subject" placeholder="{{ __('Subject *') }}"
                                                            required="">
                                                    </li>

                                                    <li>
                                                        <textarea class="input-field textarea" name="message" id="msg" placeholder="{{ __('Your Message') }}"
                                                            required=""></textarea>
                                                    </li>

                                                    <input type="hidden" name="email"
                                                        value="{{ Auth::guard('web')->user()->email }}">
                                                    <input type="hidden" name="name"
                                                        value="{{ Auth::guard('web')->user()->name }}">
                                                    <input type="hidden" name="user_id"
                                                        value="{{ Auth::guard('web')->user()->id }}">
                                                    <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">

                                                </ul>
                                                <button class="submit-btn" id="emlsub1"
                                                    type="submit">{{ __('Send Message') }}</button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MESSAGE VENDOR MODAL ENDS --}}
    @endif

@endsection

@section('scripts')
    <script type="text/javascript">
        $(function() {
            $("#slider-range").slider({
                range: true,
                orientation: "horizontal",
                min: 0,
                max: {{ $range_max }},
                values: [{{ isset($_GET['min']) ? $_GET['min'] : '0' }},
                    {{ isset($_GET['max']) ? $_GET['max'] : $range_max }}
                ],
                step: 5,

                slide: function(event, ui) {
                    if (ui.values[0] == ui.values[1]) {
                        return false;
                    }

                    $("#min_price").val(ui.values[0]);
                    $("#max_price").val(ui.values[1]);
                }
            });

            $("#min_price").val($("#slider-range").slider("values", 0));
            $("#max_price").val($("#slider-range").slider("values", 1));

        });
    </script>

    <script type="text/javascript">
        $(document).on("submit", "#emailreply", function() {
            var token = $(this).find('input[name=_token]').val();
            var subject = $(this).find('input[name=subject]').val();
            var message = $(this).find('textarea[name=message]').val();
            var email = $(this).find('input[name=email]').val();
            var name = $(this).find('input[name=name]').val();
            var user_id = $(this).find('input[name=user_id]').val();
            var vendor_id = $(this).find('input[name=vendor_id]').val();
            $('#subj').prop('disabled', true);
            $('#msg').prop('disabled', true);
            $('#emlsub').prop('disabled', true);
            $.ajax({
                type: 'post',
                url: "{{ URL::to('/vendor/contact') }}",
                data: {
                    '_token': token,
                    'subject': subject,
                    'message': message,
                    'email': email,
                    'name': name,
                    'user_id': user_id,
                    'vendor_id': vendor_id
                },
                success: function() {
                    $('#subj').prop('disabled', false);
                    $('#msg').prop('disabled', false);
                    $('#subj').val('');
                    $('#msg').val('');
                    $('#emlsub').prop('disabled', false);
                    toastr.success("{{ __('Message Sent !!') }}");
                    $('.ti-close').click();
                }
            });
            return false;
        });
    </script>
@endsection
