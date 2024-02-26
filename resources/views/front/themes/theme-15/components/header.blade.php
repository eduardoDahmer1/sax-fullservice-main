@include('front.themes.theme-15.components.topheader')
<!-- Logo Header Area Start -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<div class="menufixed">
    <section class="logo-header">
        <div class="container">
            <div class="row justify-content-between align-items-center align-items-lg-end" style="position: relative;">

                <div class="col-lg-2 col-2 order-2 order-lg-1" style="position: static;">
                    <div class="button-open-search">
                        <input type="checkbox">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </div>
                    <div class="search-box-wrapper">
                        <div class="search-box">
                            <form id="searchForm" class="search-form" action="{{ route('front.category') }}"
                                method="GET">

                                <button type="submit"><i class="bi bi-search"></i></button>

                                @if (!empty(request()->input('sort')))
                                    <input type="hidden" name="sort" value="{{ request()->input('sort') }}">
                                @endif

                                @if (!empty(request()->input('minprice')))
                                    <input type="hidden" name="minprice" value="{{ request()->input('minprice') }}">
                                @endif

                                @if (!empty(request()->input('maxprice')))
                                    <input type="hidden" name="maxprice" value="{{ request()->input('maxprice') }}">
                                @endif

                                <input type="text" id="prod_name" name="searchHttp" aria-label="Search"
                                    placeholder="{{ __('What are you looking for?') }}"
                                    value="{{ request()->input('searchHttp') }}" autocomplete="off">
                                <div class="autocomplete">
                                    <div id="myInputautocomplete-list" class="autocomplete-items"></div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-4 order-3 order-lg-2 d-flex justify-content-center p-0">
                    <div class="logo">
                        <a href="{{ route('front.index') }}">
                            <img src="{{ $gs->logoUrl }}" alt="">
                        </a>
                    </div>
                </div>

                <div class="col-lg-2 col-4 order-4 order-lg-3">
                    <div class="helpful-links">
                        <ul class="helpful-links-inner">

                            <li data-toggle="tooltip" data-placement="top" class="loginbutton"
                            title="{{ !Auth::guard('web')->check() ? __('Login') :  __('Profile') }}" >
                                @if (!Auth::guard('web')->check())
                                    <a href="{{ route('user.login') }}" class="profile carticon">
                                        <img class="img-fluid icons-header" src="{{ asset('assets/images/theme15/iconelogin.png')}}" alt="">
                                    </a>
                                @else
                                    <a href="{{ route('user-dashboard') }}" class="profile carticon">
                                        <img class="img-fluid icons-header" src="{{ asset('assets/images/theme15/iconelogin.png')}}" alt="">
                                    </a>
                                @endif
                            </li>

                            @if (!env('ENABLE_SAX_BRIDAL'))
                                <li class="wishlist" data-toggle="tooltip" data-placement="top"
                                    title="{{ __('Wish') }}">
                                    @if (Auth::guard('web')->check())

                                        <a href="{{ route('user-wishlists') }}" class="wish">
                                            <img class="img-fluid icons-header" src="{{ asset('assets/images/theme15/wishicone.png')}}" alt="">
                                            <span id="wishlist-count">{{ count(Auth::user()->wishlists) }}</span>
                                        </a>

                                    @else
                                        <a href="javascript:;" data-toggle="modal" id="wish-btn"
                                            data-target="#comment-log-reg" class="wish">
                                            <img class="img-fluid icons-header" src="{{ asset('assets/images/theme15/wishicone.png')}}" alt="">
                                            <span id="wishlist-count">0</span>
                                        </a>
                                    @endif
                                </li>

                                @if ($gs->is_cart)
                                    <li class="my-dropdown">
                                        <a href="javascript:;" class="cart carticon">
                                            <div class="icon">
                                                <img class="img-fluid icons-header" src="{{ asset('assets/images/theme15/bagicone.png')}}" alt="">
                                                <span class="cart-quantity" id="cart-count">
                                                    {{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}
                                                </span>
                                            </div>
                                        </a>
                                        <div class="my-dropdown-menu" id="cart-items">
                                            @include('load.cart')
                                        </div>
                                    </li>
                                @endif
                            @else
                                <li class="my-dropdown">
                                    <a href="javascript:;" class="cart carticon">
                                        <div class="icon">
                                            <img class="img-fluid icons-header" src="{{ asset('assets/images/theme15/wishicone.png')}}" alt="">
                                            <span class="cart-quantity" id="cart-count">
                                                {{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}
                                            </span>
                                        </div>
                                    </a>
                                    <div class="my-dropdown-menu" id="cart-items">
                                        @include('load.cart')
                                    </div>
                                </li>
                            @endif
                            {{-- <li class="compare" data-toggle="tooltip" data-placement="top"
                                title="{{ __('Compare') }}">
                                <a href="{{ route('product.compare') }}" class="wish compare-product">
                                    <div class="icon">
                                        <svg class="img-fluid icons-header" width="30" height="30"
                                            viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M20 17.5L13.75 11.25L20 5L21.75 6.78125L18.5313 10H27.5V12.5H18.5313L21.75 15.7188L20 17.5ZM10 25L16.25 18.75L10 12.5L8.25 14.2813L11.4688 17.5H2.5V20H11.4688L8.25 23.2188L10 25Z"
                                                fill="#333333" />
                                        </svg>

                                        <span id="compare-count">
                                            {{ Session::has('compare') ? count(Session::get('compare')->items) : '0' }}
                                        </span>
                                    </div>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </div>

                <div class="col-lg-12 col-2 order-1 order-lg-4">
                    <div class="saxnavigation">
                        <svg class="icone-menu-nav" width="35" height="24" viewBox="0 0 35 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="21.5" width="35" height="2" rx="1" fill="black"/>
                            <rect y="10.75" width="35" height="2" rx="1" fill="black"/>
                            <rect width="35" height="2" rx="1" fill="black"/>
                        </svg>
                            
                        <ul class="menu-navigation">
                            <li>
                                <a class="navlink" href="{{ route('front.index') }}">
                                    {{ __('Home') }}                                   
                                </a>
                            </li>
                
                            <li class="menudrop">
                                <a  class="navlink" href="{{ route('front.categories') }}">
                                    {{ __('Categories') }}
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                        width="15px" height="15px" viewBox="0 0 201.457 201.457"
                                        xml:space="preserve">
                                    <g>
                                        <path style="fill:currentColor;" d="M46.233,8.279L54.513,0l100.729,100.723L54.495,201.457l-8.279-8.28l92.46-92.46L46.233,8.279z"/>
                                    </g>
                                    </svg>
                                </a>
                            </li>

                            <div class="submenu-cat">
                                <ul>
                                    @foreach ($nav_categories->sortBy('presentation_position')->take(8) as $category)
                                        <li class="subcat-link">
                                            <a class="categoryLink text-uppercase" href={{ $category->link ?? route('front.category', $category->slug )}}>{{ $category->name }}</a>
                                            <div class="boxsubcat">
                                                <div class="container-lg justify-content-center d-flex">
                                                    <div class="display-subs">
                                                        @foreach ($category->subs_order_by as $subcategory)
                                                        <div @class([
                                                            'px-3' => count($category->subs_order_by) == 1,
                                                            'col-lg-6' => count($category->subs_order_by) == 2,
                                                            'col-lg-4' => count($category->subs_order_by) == 3,
                                                            'col-lg-3' => count($category->subs_order_by) >= 4
                                                            ])>
                                                            <a class="sub-link" href="{{ route('front.subcat',['slug1' => $subcategory->category->slug, 'slug2' => $subcategory->slug]) }}">{{ $subcategory->name }}</a>
                                                            @foreach ($subcategory->childs_order_by as $childcat)
                                                                <a class="child-link" href="{{ route('front.childcat',['slug1' => $childcat->subcategory->category->slug, 'slug2' => $childcat->subcategory->slug, 'slug3' => $childcat->slug]) }}"><i style="font-size: 10px;" class="fas fa-angle-right"></i>{{ $childcat->name }}</a>
                                                            @endforeach
                                                        </div>
                                                        @endforeach
                                                        <a class="link-seemore col-12 py-2" href="{{ route('front.category', $category->slug )}}"> Ver todos {{ $category->name }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    <li><a class="navlink py-2" href="{{ route('front.categories')}}">{{__('See all categories')}}</a></li>
                                </ul>
                            </div>
                
                            <li>
                                <a  class="navlink" target="_blank" href="https://saxdepartment.com/">
                                    {{ __('Institutional') }}
                                </a>
                            </li>
                
                            <li>
                                <a  class="navlink" target="_blank"  href="https://saxdepartment.com/sax-palace">
                                    {{ __('Sax Palace') }}
                                </a>
                            </li>
                
                            <li>
                                <a  class="navlink" target="_blank"  href="https://saxdepartment.com/sax-bridal">
                                    {{ __('SAX Bridal') }}
                                </a>
                            </li>

                            @if (!Auth::guard('web')->check())
                                <li class="login-button-menu">
                                    <a class="navlink" href="{{ route('user-dashboard') }}">
                                        {{ __('Profile') }}                                   
                                    </a>
                                </li>
                            @else
                                <li class="login-button-menu">
                                    <a class="navlink" href="{{ route('user.login') }}">
                                        {{ __('Login') }}                                   
                                    </a>
                                </li>
                            @endif
                        
                            @if ($gs->is_contact == 1)
                                <li>
                                    <a  class="navlink" href="{{ route('front.contact') }}">
                                        {{ __('Contact Us') }}
                                    </a>
                                </li>
                            @endif
                
                
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Logo Header Area End -->

    <!--Main-Menu Area Start-->
    {{-- <div class="mainmenu-area mainmenu-bb">
        <div class="container">
            <div class="row mainmenu-area-innner">
                <div class="col-6 col-lg-10 d-flex justify-content-center align-items-center">
                    <!--categorie menu start-->
                    <div class="categories_menu vertical">
                        <div class="categories_title">
                            <h2 class="categori_toggle"><i class="fas fa-layer-group"></i> {{ __('Categories') }}
                                <i class="fa fa-angle-down arrow-down"></i>
                            </h2>
                        </div>
                        <div class="categories_menu_inner">
                            <ul style="width:100%;">
                                @php
                                    $i = 1;
                                @endphp

                                @foreach ($categories as $category)
                                    @php
                                        $count = count($category->subs_order_by);
                                    @endphp
                                    <li
                                        class="{{ $count ? 'dropdown_list' : '' }}
                                        {{ $i >= 15 ? 'rx-child' : '' }} qntd">

                                        @if ($count)
                                            @if ($category->photo)
                                                <div class="img">
                                                    <img src="{{ asset('storage/images/categories/' . $category->photo) }}"
                                                        alt="">
                                                </div>
                                            @endif
                                            <div class="link-area">
                                                <span><a href="{{ route('front.category', $category->slug) }}">
                                                        {{ $category->name }}</a>
                                                </span>

                                                @if ($count)
                                                    <a href="javascript:;">
                                                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <a href="{{ route('front.category', $category->slug) }}">
                                                @if ($category->photo)
                                                    <img
                                                        src="{{ asset('storage/images/categories/' . $category->photo) }}">
                                                @endif
                                                {{ $category->name }}
                                            </a>
                                        @endif

                                        @if ($count)
                                            @php
                                                $ck = 0;

                                                foreach ($category->subs_order_by as $subcat):
                                                    if (count($subcat->childs_order_by) > 0):
                                                        $ck = 1;
                                                        break;
                                                    endif;
                                                endforeach;

                                            @endphp

                                            <ul
                                                class="{{ $ck == 1 ? 'categories_mega_menu' : 'categories_mega_menu column_1' }}">

                                                @foreach ($category->subs_order_by as $subcat)
                                                    <li>
                                                        <a
                                                            href="{{ route('front.subcat', ['slug1' => $category->slug, 'slug2' => $subcat->slug]) }}">
                                                            {{ $subcat->name }}
                                                        </a>

                                                        @if (count($subcat->childs_order_by) > 0)
                                                            <div class="categorie_sub_menu">
                                                                <ul>
                                                                    @foreach ($subcat->childs_order_by as $childcat)
                                                                        <li>
                                                                            <a
                                                                                href="{{ route('front.childcat', ['slug1' => $category->slug, 'slug2' => $subcat->slug, 'slug3' => $childcat->slug]) }}">
                                                                                {{ $childcat->name }}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </li>
                                                @endforeach

                                            </ul>
                                        @endif

                                    </li>

                                    @php
                                        $i++;
                                    @endphp

                                    @if ($i == 15)
                                        <li>
                                            <a href="{{ route('front.categories') }}"><i class="fas fa-plus"></i>
                                                {{ __('See All Categories') }}
                                            </a>
                                        </li>
                                    @break
                                @endif
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="categories_menu horizontal">
                    <div class="categories_title_horizontal">
                        <h2 class="categori_toggle"><i class="fa fa-bars"></i> {{ __('Categories') }}
                            <i class="fa fa-angle-down arrow-down"></i>
                        </h2>
                    </div>
                    <div class="categories_menu_inner_horizontal">
                        <ul>
                            @php
                                $i = 1;
                            @endphp

                            @foreach ($categories as $category)
                                @php
                                    $count = count($category->subs_order_by);
                                @endphp
                                <li
                                    class="{{ $count ? 'dropdown_list' : '' }}
                                        {{ $i >= 15 ? 'rx-child' : '' }}">

                                    @if ($count)
                                        @if ($category->photo)
                                            <div class="img">
                                                <img src="{{ asset('storage/images/categories/' . $category->photo) }}"
                                                    alt="">
                                            </div>
                                        @endif
                                        <div class="link-area">
                                            <span><a href="{{ route('front.category', $category->slug) }}">
                                                    {{ $category->name }}</a>
                                            </span>

                                            @if ($count)
                                                <a href="javascript:;">
                                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <a href="{{ route('front.category', $category->slug) }}">
                                            @if ($category->photo)
                                                <img
                                                    src="{{ asset('storage/images/categories/' . $category->photo) }}">
                                            @endif
                                            {{ $category->name }}
                                        </a>
                                    @endif

                                    @if ($count)
                                        @php
                                            $ck = 0;

                                            foreach ($category->subs_order_by as $subcat):
                                                if (count($subcat->childs_order_by) > 0):
                                                    $ck = 1;
                                                    break;
                                                endif;
                                            endforeach;

                                        @endphp

                                        <ul
                                            class="{{ $ck == 1 ? 'categories_mega_menu' : 'categories_mega_menu column_1' }}">

                                            @foreach ($category->subs_order_by as $subcat)
                                                <li>
                                                    <a
                                                        href="{{ route('front.subcat', ['slug1' => $category->slug, 'slug2' => $subcat->slug]) }}">
                                                        {{ $subcat->name }}
                                                    </a>

                                                    @if (count($subcat->childs_order_by) > 0)
                                                        <div class="categorie_sub_menu">
                                                            <ul>
                                                                @foreach ($subcat->childs_order_by as $childcat)
                                                                    <li>
                                                                        <a
                                                                            href="{{ route('front.childcat', ['slug1' => $category->slug, 'slug2' => $subcat->slug, 'slug3' => $childcat->slug]) }}">
                                                                            {{ $childcat->name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </li>
                                            @endforeach

                                        </ul>
                                    @endif

                                </li>

                                @php
                                    $i++;
                                @endphp

                                @if ($i == 6)
                                    <li>
                                        <a href="{{ route('front.categories') }}"><i class="fas fa-plus"></i>
                                            {{ __('See All Categories') }}
                                        </a>
                                    </li>
                                @break
                            @endif
                        @endforeach

                    </ul>
                </div>
            </div>
            <!--categorie menu end-->
        </div>

        <div class="col-6 col-lg-2">
            <div class="box-button-site" data-menu-toggle-main="#menu-browse-site">
                <i class="fas fa-bars"></i>
                <p>{{ __('Browse the site') }}</p>
                <div id="menu-browse-site" class="container-menu">
                    
                </div>
            </div>

        </div>
    </div> --}}
</div>
</div>
</div>
<!--Main-Menu Area End-->

<script>

    const linkSubCategories = document.querySelectorAll('.subcat-link');

    document.querySelector(".icone-menu-nav").addEventListener('click', () =>  {
        document.querySelector(".menu-navigation").classList.toggle("showNav")
    })

    document.querySelector('.menudrop').addEventListener('click', event => {
        event.preventDefault()
        document.querySelector('.menu-navigation').classList.toggle('drop_open')
        event.target.classList.toggle('submenu_open')
    })

    linkSubCategories.forEach(element => {
        element.addEventListener('click', event => {
            linkSubCategories.forEach(element => element.classList.remove('subcat_open'))
            event.currentTarget.classList.toggle('subcat_open')
        })
    });

    if (window.matchMedia("(min-width:992px)").matches) {

        document.querySelector('.menudrop').addEventListener('mouseover', event => {
            event.preventDefault()
            document.querySelector('.menu-navigation').classList.add('drop_open')
            event.target.classList.add('submenu_open')
        })

        linkSubCategories.forEach(element => {
            element.addEventListener('mouseover', event => {
                linkSubCategories.forEach(element => element.classList.remove('subcat_open'))
                event.currentTarget.classList.toggle('subcat_open')
            })
        });

        //Remove as classes
        document.querySelector(".submenu-cat").addEventListener('mouseleave', () => {
            document.querySelector('.menu-navigation').classList.toggle('drop_open')
        })        

    } else {
        document.querySelectorAll('.categoryLink').forEach( element => {
            element.addEventListener('click', event => {
                event.preventDefault();
            })
        })
    }

</script>