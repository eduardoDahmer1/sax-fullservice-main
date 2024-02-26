@if(Auth::guard('admin')->user()->sectionCheck('stores'))
<li>
    <a href="{{route('admin-stores-index')}}" class="wave-effect">
        <i class="ion-ios-home"></i>{{__('Stores')}}
    </a>
</li>
@endif

@if(Auth::guard('admin')->user()->sectionCheck('catalog'))
<li>
    <a href="#catalog" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="ion-document-text"></i>{{__('Catalog')}}
    </a>
    <ul class="collapse list-unstyled {{
    !request()->is('admin/products/popular/*') &&
    (request()->is('admin/subcategory') || 
    request()->is('admin/childcategory') || 
    request()->is('admin/products/*')) ? 'show' : ''
    }}" id="catalog" data-parent="#accordion">
        <li
            class="{{(request()->is('admin/products/*') && !request()->is('admin/products/popular/*')) && !request()->is('admin/products/rated') ? 'active' : ''}}">
            <a href="{{ route('admin-prod-index') }}"><span>{{__('Products')}}</span></a>
        </li>
        <li class="{{(request()->is('admin/subcategory') || request()->is('admin/childcategory') ? 'active' : '')}}">
            <a href="{{ route('admin-cat-index') }}"><span>{{__('Categories')}}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-brand-index') }}"><span>{{ __('Brands') }}</span></a>
        </li>
        @if(config("features.marketplace"))
        <li class="{{
            (request()->is('admin/products/rated'))  ? 'active' : ''
            }}">
                <a href="{{ route('admin-prod-rated',30) }}"><span>{{ __('High Rated Products') }}</span></a>
            </li>
        @endif
    </ul>
</li>
@endif

@if(Auth::guard('admin')->user()->sectionCheck('sell'))
<li>
    <a href="#sell" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="ion-ios-cart"></i>{{__('Sell')}}
    </a>
    <ul class="collapse list-unstyled {{request()->is('admin/orders/simplified-checkout') || request()->is('admin/order/*/show') || request()->is('admin/order/*/invoice') || request()->is('admin/user/*/show') ? 'show' : ''}}"
        id="sell" data-parent="#accordion">
        <li
            class="{{request()->is('admin/orders/simplified-checkout') || request()->is('admin/order/*/show') || request()->is('admin/order/*/invoice') ? 'active' : ''}}">
            <a href="{{route('admin-order-index')}}"><span>{{__('Orders')}}</span></a>
        </li>
        <li class="{{request()->is('admin/user/*/show') ? 'active' : ''}}">
            <a href="{{ route('admin-user-index') }}"><span>{{__('Customers')}}</span></a>
        </li>
        <li class="{{request()->is('admin/wishlist/*') ? 'active' : ''}}">
            <a href="{{ route('admin.wishlist.index') }}"><span>{{__('Wishlists')}}</span></a>
        </li>
        @if($gs->is_cart_abandonment)
        <li class="{{request()->is('admin/cartabandonments') || request()->is('admin/cartabandonments/*/details') ? 'active' : ''}}">
            <a href="{{ route('admin-cartabandonment-index') }}"><span>{{__('Cart Abandonment')}}</span></a>
        </li>
        @endif
    </ul>
</li>
@endif

@if(Auth::guard('admin')->user()->sectionCheck('content'))
<li>
    <a href="#content_menu" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="ion-ios-paper"></i>{{__('Content')}}
    </a>
    <ul class="collapse list-unstyled 
        {{(request()->is('admin/blog/category') || 
            request()->is('admin/team_member/category') || 
            request()->is('admin/page-settings/big-save') || 
            request()->is('admin/top/small/banner') || 
            request()->is('admin/bottom/small/banner') || 
            request()->is('admin/large/banner') ||
            request()->is('admin/slider/*') ? 'show' : '')
        }}" id="content_menu" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-faq-index') }}"><span>{{__('FAQ')}}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-ps-contact') }}"><span>{{ __('Contact Us Page') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-page-index') }}"><span>{{ __('Pages') }}</span></a>
        </li>
        <li class="{{request()->is('admin/blog/category') ? 'active' : ''}}">
            <a href="{{ route('admin-blog-index') }}"><span>{{ __('Blog') }}</span></a>
        </li>
        <li class="{{request()->is('admin/team_member/category') ? 'active' : ''}}">
            <a href="{{ route('admin-team_member-index') }}"><span>{{ __('Team') }}</span></a>
        </li>
        <li class="{{request()->is('admin/slider/*') ? 'active' : ''}}">
            <a href="{{ route('admin-sl-index') }}"><span>{{ __('Sliders') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-service-index') }}"><span>{{ __('Services') }}</span></a>
        </li>
        <li class="{{
            request()->is('admin/page-settings/big-save') || 
            request()->is('admin/bottom/small/banner') || 
            request()->is('admin/large/banner') || 
            request()->is('admin/top/small/banner') ? 'active' : ''
            }}">
            <a href="{{ route('admin-ps-best-seller') }}"><span>{{ __('Banners') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-gs-popup') }}"><span>{{ __('Popup') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-review-index') }}"><span>{{ __('Reviews') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-partner-index') }}"><span>{{ __('Logo Slider') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-gs-footer') }}"><span>{{ __('Footer') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-gs-error-banner') }}"><span>{{ __('Page Not Found') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-gs-policy') }}"><span>{{ __("Buy/Return Policy") }}</span></a>
        </li>
        <li>
            <a href="{{route('admin-gs-privacypolicy')}}"><span>{{ __('Privacy Policy') }}</span></a>
        </li>
    </ul>
</li>
@endif

@if(Auth::guard('admin')->user()->sectionCheck('config'))
<li>
    <a href="#config" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="ion-ios-settings-strong"></i>{{__('Config')}}
    </a>
    <ul class="collapse list-unstyled {{
        request()->is('admin/general-settings/favicon') || 
        request()->is('admin/general-settings/loader') || 
        request()->is('admin/general-settings/contents') || 
        request()->is('admin/page-settings/customize') || 
        request()->is('admin/general-settings/productconf') || 
        request()->is('admin/general-settings/cartconf') || 
        request()->is('admin/general-settings/paymentconf') || 
        request()->is('admin/general-settings/shippingconf') ||
        request()->is('admin/payment-informations/*') ||
        request()->is('admin/bank_account') || 
        request()->is('admin/shipping_prices') || 
        request()->is('admin/general-settings/correiosconf') ||
        request()->is('admin/general-settings/aexconf') ||
        request()->is('admin/general-settings/melhorenvioconf') ||
        request()->is('admin/package') || 
        request()->is('admin/pickup') ||
        request()->is('admin/languages/*')  ? 'show' : ''
        }}" id="config" data-parent="#accordion">
        <li class="{{
            request()->is('admin/general-settings/favicon') || 
            request()->is('admin/general-settings/loader') || 
            request()->is('admin/general-settings/contents') ? 'active' : ''
            }}">
            <a href="{{ route('admin-gs-logo') }}"><span>{{__('Theme')}}</span></a>
        </li>
        <li class="{{
        request()->is('admin/page-settings/customize') || 
        request()->is('admin/general-settings/productconf') || 
        request()->is('admin/general-settings/cartconf') || 
        request()->is('admin/general-settings/paymentconf') || 
        request()->is('admin/general-settings/shippingconf') ? 'active' : ''
        }}">
            <a href="{{ route('admin-gs-storeconf') }}"><span>{{__('Store')}}</span></a>
        </li>
        <li>
            <a href="{{route('admin-currency-index')}}"><span>{{ __('Currencies') }}</span></a>
        </li>
        <li
            class="{{request()->is('admin/payment-informations/*') || request()->is('admin/bank_account') ? 'active' : ''}}">
            <a href="{{route('admin-gs-payments-index')}}"><span>{{__('Payment Methods')}}</span></a>
        </li>
        <li class="{{
        request()->is('admin/shipping_prices') || 
        request()->is('admin/general-settings/correiosconf') || 
        request()->is('admin/general-settings/aexconf') || 
        request()->is('admin/general-settings/melhorenvioconf') || 
        request()->is('admin/package') || 
        request()->is('admin/pickup') ? 'active' : ''
        }}">
            <a href="{{ route('admin-shipping-index') }}"><span>{{ __('Shipping Methods') }}</span></a>
        </li>
        <li>
            <a href="{{route('admin-mail-index')}}"><span>{{ __('Email Templates') }}</span></a>
        </li>
        <li>
            <a href="{{route('admin-mail-config')}}"><span>{{ __('Email Configuration') }}</span></a>
        </li>
        <li class="{{
            request()->is('admin/languages/*') ? 'active' : ''
            }}">
            <a href="{{route('admin-lang-index')}}"><span>{{__('Languages')}}</span></a>
        </li>
    </ul>
</li>
@endif

@if(Auth::guard('admin')->user()->sectionCheck('marketing'))
<li>
    <a href="#marketing" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="ion-speakerphone"></i>{{ __('Marketing') }}
    </a>
    <ul class="collapse list-unstyled
    {{
    (request()->is('admin/products/popular/*') && !request()->is('admin/products/popular/30'))  ? 'show' : ''
    }}" id="marketing" data-parent="#accordion">
        <li>
            <a href="{{route('admin-coupon-index')}}"><span>{{ __('Coupons') }}</span></a>
        </li>
        <li>
            <a href="{{route('admin-social-index')}}"><span>{{ __('Social Links') }}</span></a>
        </li>
        <li class="{{
        (request()->is('admin/products/popular/*') && !request()->is('admin/products/popular/30'))  ? 'active' : ''
        }}">
            <a href="{{ route('admin-prod-popular',30) }}"><span>{{ __('Popular Products') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-seotool-keywords') }}"><span>{{ __('Meta Keywords') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-subs-index') }}"><span>{{ __("Subscribers") }}</span></a>
        </li>
        @if($gs->is_back_in_stock)
        <li>
            <a href="{{ route('admin-backinstock-index') }}"><span>{{ __("Back in Stock") }}</span></a>
        </li>
        @endif
    </ul>
</li>
@endif

@if(config('features.marketplace'))
@if(Auth::guard('admin')->user()->sectionCheck('marketplace'))
<li>
    <a href="#marketplace" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="ion-android-people"></i>Marketplace
    </a>
    <ul class="collapse list-unstyled {{request()->is('admin/vendor/color') ? 'show' : ''}}" id="marketplace"
        data-parent="#accordion">
        <li>
            <a href="{{ route('admin-import-index') }}"><span>{{ __("Affiliate Products") }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-withdraw-index') }}"><span>{{ __("User Withdraws") }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-subscription-index') }}"><span>{{ __("Vendor Subscription Plans") }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-vendor-index') }}"><span>{{ __("Vendors") }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-vendor-subs') }}"><span>{{ __("Vendor Subscriptions") }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-vr-index') }}"><span>{{ __("All Verifications") }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-vendor-withdraw-index') }}"><span>{{ __("Vendor Withdraws") }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-gs-affilate') }}"><span>{{ __('Affiliate Informations') }}</span></a>
        </li>
        <li>
            <a href="{{route('admin-gs-vendorpolicy')}}"><span>{{ __('Seller Terms of Service') }}</span></a>
        </li>
        <li class="{{
                request()->is('admin/vendor/color') ? 'active' : ''
                }}">
            <a href="{{ route('admin-gs-marketplaceconf') }}"><span>{{ __("Settings") }}</span></a>
        </li>
    </ul>
</li>
@endif
@endif

@if(config('mercadolivre.is_active'))
    @if(Auth::guard('admin')->user()->sectionCheck('mercadolivre'))
        <li>
            <a href="#mercadolivre" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
                <i class="fas fa-store" style="font-size:15px"></i>{{__('Mercado Livre')}}
            </a>
            <ul class="collapse list-unstyled" data-parent="#accordion" id="mercadolivre">
                <li>
                    <a href="{{route('admin.meli-dashboard')}}"><span>{{__('Dashboard')}}</span></a>
                </li>
            </ul>
        </li>
    @endif
@endif

@if(Auth::guard('admin')->user()->sectionCheck('system'))
<li>
    <a href="#system" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="ion-ios-gear"></i>{{__('System')}}
    </a>
    <ul class="collapse list-unstyled {{
        request()->is('admin/role*') || 
        request()->is('admin/general-settings/integrations/*') || 
        request()->is('admin/social/*') || 
        (request()->is('admin/seotools/*') && !request()->is('admin/seotools/keywords')) ||
        request()->is('admin/adminlanguages/*') ? 'show' : ''
        }}" id="system" data-parent="#accordion">
        <li class="{{request()->is('admin/role*') ? 'active' : ''}}">
            <a href="{{route('admin-staff-index')}}"><span>{{ __('Staffs') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-user-image') }}"><span>{{ __('Customer Default Image') }}</span></a>
        </li>
        <li class="{{
            request()->is('admin/adminlanguages/*') ? 'active' : ''
        }}">
            <a href="{{route('admin-tlang-index')}}"><span>{{ __('Admin Panel Language') }}</span></a>
        </li>
        @if(Auth::guard('admin')->user()->IsSuper())
        <li>
            <a href="{{ route('admin-gs-maintenance') }}"><span>{{ __('Website Maintenance') }}</span></a>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->IsSuper())
        <li class="{{
            request()->is('admin/general-settings/integrations/*') ||
            request()->is('admin/social/*') || 
            (request()->is('admin/seotools/*') && !request()->is('admin/seotools/keywords')) ? 'active' : ''
        }}">
            <a href="{{ route('admin-gs-integrations') }}"><span>{{__('Integrations')}}</span></a>
        </li>
        @endif
        <li>
            <a href="{{ route('admin-prod-import') }}"><span>{{ __("Product Bulk Upload") }}</span></a>
        </li>
        @if(Auth::guard('admin')->user()->IsSuper())
        <li>
            <a href="{{route('admin-cache-clear')}}"><span>{{__('Clear Cache')}}</span></a>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->IsSuper())
        <li>
            <a href="{{route('admin-activitylog-index')}}"><span>{{ __('Activity Log') }}</span></a>
        </li>
        @endif
        @if(Auth::guard('admin')->user()->IsSuper())
        <li>
            <a href="{{route('admin-gs-crowpolicy')}}"><span>{{ __('General Terms of Service') }}</span></a>
        </li>
        @endif
    </ul>
</li>
@endif

@if(Auth::guard('admin')->user()->sectionCheck('support'))
<li>
    <a href="#support" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="ion-ios-help"></i>{{__('Support')}}
    </a>
    <ul class="collapse list-unstyled {{
        request()->is('admin/comments') ||
        request()->is('admin/reports') ? 'show' : ''
    }}" id="support" data-parent="#accordion">
        <li class="{{
            request()->is('admin/comments') ||
            request()->is('admin/reports') ? 'active' : ''
        }}">
            <a href="{{ route('admin-rating-index') }}"><span>{{__('Products')}}</span></a>
        </li>
        @if(!config("features.marketplace"))
        <li>
            <a href="{{ route('admin-message-index') }}"><span>{{ __('Tickets') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-message-dispute') }}"><span>{{__('Disputes')}}</span></a>
        </li>
        @endif
        <li>
            <a href="{{route('admin-group-show')}}"><span>{{ __('Group Email') }}</span></a>
        </li>
    </ul>
</li>
@endif