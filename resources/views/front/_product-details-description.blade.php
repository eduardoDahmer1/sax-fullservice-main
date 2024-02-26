<div class="row">
    <div class="col-lg-12">
        <div id="product-details-tab">
            <div class="top-menu-area">
                <ul class="tab-menu">
                    @if ($gs->is_rating == 1)
                        <li><a href="#tabs-1">{{ __('Reviews') }}({{ count($productt->ratings) }})</a></li>
                    @endif
                    <li><a href="#tabs-2">{{ __('BUY & RETURN POLICY') }}</a></li>
                    @if ($gs->is_comment == 1)
                        <li>
                            <a href="#tabs-3">{{ __('Comment') }}
                                (<span id="comment_count">{{ count($productt->comments) }}</span>)
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="tab-content-wrapper">
                
                @if ($gs->is_rating == 1)
                    <div id="tabs-1" class="tab-content-area">
                        <div id="replay-area">
                            <div id="reviews-section">
                                <ul class="all-replay">
                                    @forelse ($productt->ratings as $review)
                                        <li>
                                            <div class="single-review">
                                                <div class="right-area">
                                                    <h3 class="name">{{ $review->user->name }}
                                                        <span class="header-area ml-2">
                                                            <span class="stars-area">
                                                                <ul class="stars">
                                                                    <div class="ratings">
                                                                        <div class="empty-stars"></div>
                                                                        <div class="full-stars"
                                                                            style="width:{{ $review->rating * 20 }}%">
                                                                        </div>
                                                                    </div>
                                                                </ul>
                                                            </span>
                                                        </span>
                                                    </h3>
                                                    <p class="date">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $review->review_date)->diffForHumans() }}</p>
                                                    <div class="review-body">
                                                        <p class="m-0">
                                                            {{ $review->review }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <p class="text-center" style="font-family:'Montserrat', sans-serif;">{{ __('No Review Found.') }}</p>
                                    @endforelse
                                </ul>
                            </div>
                            @if (Auth::guard('web')->check())
                                <div class="review-area">
                                    <h4 class="title">{{ __('Review') }}</h4>
                                    <div class="star-area">
                                        <ul class="star-list">
                                            <li class="stars" data-val="1">
                                                <i class="fas fa-star"></i>
                                            </li>
                                            <li class="stars" data-val="2">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </li>
                                            <li class="stars" data-val="3">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </li>
                                            <li class="stars" data-val="4">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </li>
                                            <li class="stars active" data-val="5">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="write-comment-area">
                                    <div class="gocover"
                                        style="background: url({{ asset('storage/images/' . $gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                                    </div>
                                    <form id="reviewform" action="{{ route('front.review.submit') }}"
                                        data-href="{{ route('front.reviews', $productt->id) }}" method="POST">
                                        @include('includes.admin.form-both')
                                        {{ csrf_field() }}
                                        <input type="hidden" id="rating" name="rating" value="5">
                                        <input type="hidden" name="user_id"
                                            value="{{ Auth::guard('web')->user()->id }}">
                                        <input type="hidden" name="product_id" value="{{ $productt->id }}">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <textarea name="review" placeholder="{{ __('Your Review') }}" required></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <button class="submit-btn" type="submit">
                                                    {{ __('SUBMIT') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="text-center">{{ __('To rate please login') }}</h4>
                                        <h5 class="text-center">
                                            <a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg"
                                                class="btn login-btn mr-1">
                                                {{ __('Login') }}
                                            </a>
                                        </h5>
                                        <br>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                <div id="tabs-2" class="tab-content-area">
                    @if ($gs->policy)
                        <p>{!! $gs->policy !!}</p>
                    @elseif($productt->policy)
                        <p>{!! $productt->policy !!}</p>
                    @endif
                </div>
                @if ($gs->is_comment == 1)
                    <div id="tabs-3" class="tab-content-area">
                        <div id="comment-area">
                            @include('includes.comment-replies')
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
