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
                            <a href="{{ route('front.blog') }}">
                                {{ __('Blog') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('front.blog') }}">
                                {{ $blog->category->name }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('front.blogshow', $blog->id) }}">
                                {{ __('Blog Details') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->



    <!-- Blog Details Area Start -->
    <section class="blog-details" id="blog-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-content">
                        <div class="feature-image">
                            <img class="img-fluid" src="{{ asset('storage/images/blogs/' . $blog->photo) }}" alt="">
                        </div>
                        <div class="content">
                            <h3 class="title">
                                {{ $blog->title }}
                            </h3>
                            <ul class="post-meta">
                                <li>
                                    <a href="javascript:;">
                                        <i class="icofont-calendar"></i>
                                        {{ date('d M, Y', strtotime($blog->created_at)) }}
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <i class="icofont-eye"></i>
                                        {{ $blog->views }} {{ __('View(s)') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <i class="icofont-speech-comments"></i>
                                        {{ __('Source') }} : {{ $blog->source }}
                                    </a>
                                </li>
                            </ul>

                            {!! $blog->details !!}

                            <div class="tag-social-link">
                                <div class="tag">
                                    <h6 class="title">Tag : </h6>
                                    @if (is_array($blog->tags))
                                        @foreach ($blog->tags as $key => $tag)
                                            <a href="{{ route('front.blogtags', $tag) }}">
                                                {{ $tag }}{{ $key != count($blog->tags) - 1 ? ',' : '' }}
                                            </a>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="social-sharing a2a_kit a2a_kit_size_32">
                                    <ul class="social-links">
                                        <li>
                                            <a class="facebook a2a_button_facebook" href="">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="twitter a2a_button_twitter" href="">
                                                <i class="fab fa-twitter"></i>
                                            </a>

                                        </li>
                                        <li>
                                            <a class="linkedin a2a_button_linkedin" href="">
                                                <i class="fab fa-linkedin-in"></i>
                                            </a>

                                        </li>
                                        <li>

                                            <a class="a2a_dd plus" href="https://www.addtoany.com/share">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                                <script async src="https://static.addtoany.com/menu/page.js"></script>
                            </div>
                        </div>
                    </div>


                    {{-- DISQUS START --}}
                    @if ($gs->is_disqus == 1)
                        <div class="comments">
                            {!! $gs->disqus !!}
                        </div>
                    @endif
                    {{-- DISQUS ENDS --}}

                </div>

                <div class="col-lg-4">
                    <div class="blog-aside">
                        <div class="serch-form">
                            <form action="{{ route('front.blogsearch') }}">
                                <input type="text" name="search" placeholder="{{ __('Search') }}" required="">
                                <button type="submit"><i class="icofont-search"></i></button>
                            </form>
                        </div>
                        <div class="categori">
                            <h4 class="title">{{ __('Categories') }}</h4>
                            <span class="separator"></span>
                            <ul class="categori-list">
                                @foreach ($bcats as $cat)
                                    <li>
                                        <a href="{{ route('front.blogcategory', $cat->slug) }}" {!! $cat->id == $blog->category_id ? 'class="active"' : '' !!}>
                                            <span>{{ $cat->name }}</span>
                                            <span>({{ $cat->blogs()->count() }})</span>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                        <div class="recent-post-widget">
                            <h4 class="title">{{ __('Recent Post') }}</h4>
                            <span class="separator"></span>
                            <ul class="post-list">

                                @foreach (App\Models\Blog::orderBy('created_at', 'desc')->limit(4)->get() as $blog)
                                    <li>
                                        <div class="post">
                                            <div class="post-img">
                                                <img style="width: 73px; height: 59px;"
                                                    src="{{ asset('storage/images/blogs/' . $blog->photo) }}" alt="">
                                            </div>
                                            <div class="post-details">
                                                <a href="{{ route('front.blogshow', $blog->id) }}">
                                                    <h4 class="post-title">
                                                        {{ mb_strlen($blog->title, 'utf-8') > 45 ? mb_substr($blog->title, 0, 45, 'utf-8') . ' ..' : $blog->title }}
                                                    </h4>
                                                </a>
                                                <p class="date">
                                                    {{ date('M d - Y', strtotime($blog->created_at)) }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach


                            </ul>
                        </div>
                        <div class="archives">
                            <h4 class="title">{{ __('Archives') }}</h4>
                            <span class="separator"></span>
                            <ul class="archives-list">
                                @foreach ($archives as $key => $archive)
                                    <li>
                                        <a href="{{ route('front.blogarchive', $key) }}">
                                            <span>{{ $key }}</span>
                                            <span>({{ count($archive) }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tags">
                            <h4 class="title">{{ __('Tags') }}</h4>
                            <span class="separator"></span>
                            <ul class="tags-list">
                                @if (is_array($tags))
                                    @foreach ($tags as $tag)
                                        @if (!empty($tag))
                                            <li>
                                                <a href="{{ route('front.blogtags', $tag) }}">{{ $tag }} </a>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Area End-->


@endsection
