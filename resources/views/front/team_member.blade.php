@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')

@section('content')


<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="pages">

                    {{-- Category Breadcumbs --}}

                    @if(isset($scat))

                    <li>
                        <a href="{{ route('front.index') }}">
                            {{ __('Home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.team_member') }}">
                            {{ __('Blog') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.team_membercategory',$scat->slug) }}">
                            {{ $scat->name }}
                        </a>
                    </li>

                    @elseif(isset($slug))

                    <li>
                        <a href="{{ route('front.index') }}">
                            {{ __('Home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.team_member') }}">
                            {{ __('Blog') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.team_membertags',$slug) }}">
                            {{ __('Tag') }}: {{ $slug }}
                        </a>
                    </li>

                    @elseif(isset($search))

                    <li>
                        <a href="{{ route('front.index') }}">
                            {{ __('Home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.team_member') }}">
                            {{ __('Blog') }}
                        </a>
                    </li>
                    <li>
                        <a href="Javascript:;">
                            {{ __('Search') }}
                        </a>
                    </li>
                    <li>
                        <a href="Javascript:;">
                            {{ $search }}
                        </a>
                    </li>

                    @elseif(isset($date))

                    <li>
                        <a href="{{ route('front.index') }}">
                            {{ __('Home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.team_member') }}">
                            {{ __('Blog') }}
                        </a>
                    </li>
                    <li>
                        <a href="Javascript:;">
                            {{ __('Archive') }}: {{ date('F Y',strtotime($date)) }}
                        </a>
                    </li>

                    @else

                    <li>
                        <a href="{{ route('front.index') }}">
                            {{ __('Home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.team_member') }}">
                            {{ __('Team') }}
                        </a>
                    </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->

<!-- Team Page Area Start -->
<section class="blogpagearea">
    <div class="container">
        <div id="ajaxContent">
            @include('front.pagination.team_member')
        </div>
    </div>
</section>
<!-- Team Page Area Start -->




@endsection


@section('scripts')

<script type="text/javascript">
    // Pagination Starts

    $(document).on('click', '.pagination li', function (event) {
      event.preventDefault();
      if ($(this).find('a').attr('href') != '#' && $(this).find('a').attr('href')) {
        $('#preloader').show();
        $('#ajaxContent').load($(this).find('a').attr('href'), function (response, status, xhr) {
          if (status == "success") {
            $("html,body").animate({
              scrollTop: 0
            }, 1);
            $('#preloader').fadeOut();


          }

        });
      }
    });

    // Pagination Ends

</script>


@endsection