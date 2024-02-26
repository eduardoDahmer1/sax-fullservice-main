<section class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="content">
                    <div class="left-content">
                        <div class="list">
                            <ul>
                                @if (config('features.lang_switcher') && $gs->is_language == 1)
                                    <li>
                                        <div class="language-selector">
                                            <i class="fas fa-globe-americas"></i>
                                            <select name="language" class="language selectors nice">
                                                @foreach ($locales as $language)
                                                    <option value="{{ route('front.language', $language->id) }}"
                                                        {{ $slocale->id == $language->id ? 'selected' : '' }}>
                                                        {{ $language->language }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                @endif

                                @if (config('features.currency_switcher') && $gs->is_currency == 1)
                                    <li>
                                        <div class="currency-selector">
                                            <span>{{ $scurrency->sign }}</span>
                                            <select name="currency" class="currency selectors nice">
                                                @foreach ($currencies as $currency)
                                                    <option value="{{ route('front.currency', $currency->id) }}"
                                                        {{ $scurrency->id == $currency->id ? 'selected' : '' }}>
                                                        {{ $currency->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                @endif

                                @if ($gs->show_currency_values == 1)
                                    @php
                                        $top_first_curr = $curr;
                                        $top_curr = $scurrency;
                                    @endphp

                                    @if ($scurrency->id != 1)
                                        <li>
                                            <div class="currency-selector">
                                                <span><i class="fas fa-coins"></i>
                                                    {{ __('Currency Rate') }}:
                                                    {{ $top_first_curr->sign . number_format($top_first_curr->value, $top_first_curr->decimal_digits, $top_first_curr->decimal_separator, $top_first_curr->thousands_separator) }}
                                                    =
                                                    {{ $top_curr->sign .
                                                        ' ' .
                                                        number_format(
                                                            $top_curr->value,
                                                            $top_curr->decimal_digits,
                                                            $top_curr->decimal_separator,
                                                            $top_curr->thousands_separator,
                                                        ) }}
                                                </span>
                                            </div>
                                        </li>
                                    @endif
                                @endif

                                <!-- @if (Auth::guard('admin')->check())
                                    <li>
                                        <div class="mybadge1">
                                            <a href="{{ route('admin.logout') }}">
                                                {{ __('Viewing as Admin') }}
                                                <i class="fas fa-power-off"></i>
                                                {{ __('Logout') }}
                                            </a>
                                        </div>
                                    </li>
                                @endif -->
                            </ul>
                        </div>
                    </div>
                    <div class="right-content">
                        <div class="list">
                            <ul>
                                @if (!Auth::guard('web')->check())
                                    <li class="login">
                                        <a href="{{ route('user.login') }}" class="sign-log">
                                            <div class="links">
                                                <span class="sign-in">{{ __('Sign in') }}</span> <span>|</span>
                                                <span class="join">{{ __('Join') }}</span>
                                            </div>
                                        </a>
                                    </li>
                                @else
                                    <li class="profilearea my-dropdown">
                                        <a href="javascript: ;" id="profile-icon" class="profile carticon">
                                            <span class="text">
                                                <i class="far fa-user"></i> {{ __('My Account') }}
                                                <i class="fas fa-chevron-down"></i>
                                            </span>
                                        </a>
                                        <div class="my-dropdown-menu profile-dropdown">
                                            <ul class="profile-links">
                                                @if (!Auth::user()->IsVendor())
                                                    <li>
                                                        <a href="{{ route('user-dashboard') }}">
                                                            <i class="fas fa-angle-double-right"></i>
                                                            {{ __('User Panel') }}
                                                        </a>
                                                    </li>
                                                @endif

                                                @if (config('features.marketplace'))
                                                    @if (Auth::user()->IsVendor())
                                                        <li>
                                                            <a href="{{ route('vendor-dashboard') }}">
                                                                <i class="fas fa-angle-double-right"></i>
                                                                {{ __('Vendor Panel') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a
                                                                href="{{ route('front.vendor', str_replace(' ', '-', Auth::user()->shop_name)) }}">
                                                                <i class="fas fa-angle-double-right"></i>
                                                                {{ __('My Store') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                                @if (!Auth::user()->IsVendor())
                                                    <li>
                                                        <a href="{{ route('user-profile') }}">
                                                            <i class="fas fa-angle-double-right"></i>
                                                            {{ __('Edit Profile') }}
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a href="{{ route('user-logout') }}">
                                                        <i class="fas fa-angle-double-right"></i>
                                                        {{ __('Logout') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                                @if (config('features.marketplace'))
                                    @if (!Auth::guard('web')->check() || (Auth::guard('web')->check() && !Auth::user()->IsVendor()))
                                        <li class="login ml-0">
                                            <a href="{{ route('vendor.login') }}">
                                                <div class="links">
                                                    | {{ __('Start Selling') }}
                                                    <i class="fas fa-store"></i>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                                @if (config('features.productsListPdf'))
                                    <li class="login ml-0">
                                        <a target="_blank" href="{{ route('download-list-pdf') }}">
                                            <div class="links">
                                                | {{ __('Products list - PDF') }}
                                                <i class="fas fa-download"></i>
                                            </div>
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
<!-- Top Header Area End -->
<!-- Logo Header Area Start -->
<section class="logo-header">
    <div class="container">
        <div class="row ">
            <div class="col-lg-3 col-sm-6 col-5 remove-padding">
                <div class="logo">
                    <a href="{{ route('front.index') }}">
                        <img src="{{ $gs->logoUrl }}" alt="">
                    </a>
                </div>
            </div>
            <div class="col-lg-7 col-sm-12 remove-padding order-last order-sm-2 order-md-2">
                <div class="search-box-wrapper">
                    <div class="search-box">
                        <form id="searchForm" class="search-form" action="{{ route('front.category') }}"
                            method="GET">

                            @if (!empty(request()->input('sort')))
                                <input type="hidden" name="sort" value="{{ request()->input('sort') }}">
                            @endif

                            @if (!empty(request()->input('minprice')))
                                <input type="hidden" name="minprice" value="{{ request()->input('minprice') }}">
                            @endif

                            @if (!empty(request()->input('maxprice')))
                                <input type="hidden" name="maxprice" value="{{ request()->input('maxprice') }}">
                            @endif

                            <input type="text" id="prod_name" name="searchHttp"
                                placeholder="{{ __('Search For Product') }}"
                                value="{{ request()->input('searchHttp') }}" autocomplete="off">
                            <div class="autocomplete">
                                <div id="myInputautocomplete-list" class="autocomplete-items"></div>
                            </div>
                            <button type="submit"><i class="icofont-search-1"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 col-7 remove-padding order-lg-last">
                <div class="helpful-links">
                    <ul class="helpful-links-inner">

                        @if ($gs->is_cart)
                            <li class="my-dropdown">
                                <a href="javascript:;" class="cart carticon">
                                    <div class="icon">
                                        <i class="icofont-shopping-cart"></i>
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

                        <li class="wishlist" data-toggle="tooltip" data-placement="top"
                            title="{{ __('Wish') }}">

                            @if (Auth::guard('web')->check())
                                <a href="{{ route('user-wishlists') }}" class="wish">
                                    <i class="far fa-heart"></i>
                                    <span id="wishlist-count">{{ count(Auth::user()->wishlists) }}</span>
                                </a>
                            @else
                                <a href="javascript:;" data-toggle="modal" id="wish-btn"
                                    data-target="#comment-log-reg" class="wish">
                                    <i class="far fa-heart"></i>
                                    <span id="wishlist-count">0</span>
                                </a>
                            @endif

                        </li>
                        <li class="compare" data-toggle="tooltip" data-placement="top" title="{{ __('Compare') }}">
                            <a href="{{ route('product.compare') }}" class="wish compare-product">
                                <div class="icon">
                                    <i class="fas fa-exchange-alt"></i>
                                    <span id="compare-count">
                                        {{ Session::has('compare') ? count(Session::get('compare')->items) : '0' }}
                                    </span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Logo Header Area End -->

<!--Main-Menu Area Start-->
<div class="mainmenu-area mainmenu-bb">
    <div class="container">
        <div class="row align-items-center mainmenu-area-innner">
            <div class="col-lg-3 col-md-6 categorimenu-wrapper remove-padding">
                <!--categorie menu start-->
                <div class="categories_menu">
                    <div class="categories_title">
                        <h2 class="categori_toggle"><i class="fa fa-bars"></i> {{ __('Categories') }}
                            <i class="fa fa-angle-down arrow-down"></i>
                        </h2>
                    </div>
                    <div class="categories_menu_inner">
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
            <!--categorie menu end-->
        </div>
        <div class="col-lg-9 col-md-6 mainmenu-wrapper remove-padding">
            <nav hidden>
                <div class="nav-header">
                    <button class="toggle-bar"><span class="fa fa-bars"></span></button>
                </div>
                <ul class="menu">

                    @if ($gs->is_home == 1)
                        <li><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>
                    @endif

                    @if (config('features.marketplace'))
                        <li><a href="{{ route('front.stores') }}">{{ __('Stores') }}</a></li>
                    @endif

                    @if ($gs->is_brands == 1)
                        <li><a href="{{ route('front.brands') }}">{{ __('Brands') }}</a></li>
                    @endif

                    @if ($gs->is_blog == 1)
                        <li><a href="{{ route('front.blog') }}">{{ __('Blog') }}</a></li>
                    @endif

                    @if ($gs->is_faq == 1)
                        <li><a href="{{ route('front.faq') }}">{{ __('Faq') }}</a></li>
                    @endif

                    @foreach ($pheader as $data)
                        <li><a href="{{ route('front.page', $data->slug) }}">{{ $data->title }}</a></li>
                    @endforeach

                    @if ($gs->is_contact == 1)
                        <li><a href="{{ route('front.contact') }}">{{ __('Contact Us') }}</a></li>
                    @endif

                    @if ($gs->policy)
                        <li><a href="{{ route('front.policy') }}">{{ __('Buy & Return Policy') }}</a></li>
                    @endif

                    @if ($gs->is_cart)
                        <li>
                            <a href="javascript:;" data-toggle="modal" data-target="#track-order-modal"
                                class="track-btn">{{ __('Track Order') }}</a>
                        </li>
                    @endif

                    @if ($gs->team_show_header == 1)
                        <li><a href="{{ route('front.team_member') }}">{{ __('Team') }}</a></li>
                    @endif

                </ul>
            </nav>
        </div>
    </div>
</div>
</div>
<!--Main-Menu Area End-->
