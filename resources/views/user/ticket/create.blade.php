@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')
    <section class="user-dashbord">
        <div class="container">
            <div class="row">
                @include('includes.user-dashboard-sidebar')
                <div class="col-lg-8">
                    <div class="user-profile-details">
                        <div class="order-history">
                            <div class="header-area">
                                @if ($conv->order_number != null)
                                    <h4 class="title">
                                        {{ __('Order Number:') }} {{ $conv->order_number }}
                                    </h4>
                                @endif
                                <h4 class="title">
                                    {{ __('Subject:') }} {{ $conv->subject }} <a class="mybtn1"
                                        href="{{ url()->previous() }}"> <i class="fas fa-arrow-left"></i>
                                        {{ __('Back') }}</a>
                                </h4>
                            </div>


                            <div class="support-ticket-wrapper ">
                                <div class="panel panel-primary">
                                    <div class="gocover"
                                        style="background: url({{ asset('storage/images/' . $gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                                    </div>
                                    <div class="panel-body" id="messages">
                                        @foreach ($conv->messages as $message)
                                            @if ($message->user_id != 0)
                                                <div class="single-reply-area user">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="reply-area">
                                                                <div class="left">
                                                                    <p>{{ $message->message }}</p>
                                                                </div>
                                                                <div class="right">
                                                                    @if ($message->conversation->user->is_provider == 1)
                                                                        <img class="img-circle"
                                                                            src="{{ $message->conversation->user->photo != null ? $message->conversation->user->photo : asset('assets/images/noimage.png') }}"
                                                                            alt="">
                                                                    @else
                                                                        <img class="img-circle"
                                                                            src="{{ $message->conversation->user->photo != null ? asset('storage/images/users/' . $message->conversation->user->photo) : asset('assets/images/noimage.png') }}"
                                                                            alt="">
                                                                    @endif
                                                                    <p class="ticket-date">
                                                                        {{ $message->conversation->user->name }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                            @else
                                                <div class="single-reply-area admin">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="reply-area">
                                                                <div class="left">
                                                                    <img class="img-circle"
                                                                        src="{{ asset('storage/images/admin.jpg') }}"
                                                                        alt="">
                                                                    <p class="ticket-date">{{ __('Admin') }}</p>
                                                                </div>
                                                                <div class="right">
                                                                    <p>{{ $message->message }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                            @endif
                                        @endforeach

                                    </div>
                                    <div class="panel-footer">
                                        <form id="messageform" data-href="{{ route('user-message-load', $conv->id) }}"
                                            action="{{ route('user-message-store') }}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <input type="hidden" name="conversation_id" value="{{ $conv->id }}">
                                                <input type="hidden" name="user_id" value="{{ $conv->user->id }}">
                                                <textarea class="form-control" name="message" id="wrong-invoice" rows="5" style="resize: vertical;" required=""
                                                    placeholder="{{ __('Message') }}"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button class="mybtn1">
                                                    {{ __('Add Reply') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
