<div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{request()->is('admin/general-settings/storeconf') ? 'active' : ''}}" href="{{ route('admin-gs-storeconf') }}" role="tab">{{__('General')}}</a>
                <a class="nav-item nav-link {{request()->is('admin/page-settings/customize') ? 'active' : ''}}" href="{{ route('admin-ps-customize') }}" role="tab">{{__('Home Page')}}</a>
                <a class="nav-item nav-link {{request()->is('admin/general-settings/productconf') ? 'active' : ''}}" href="{{ route('admin-gs-productconf') }}" role="tab">{{__('Products')}}</a>
                <a class="nav-item nav-link {{request()->is('admin/general-settings/cartconf') ? 'active' : ''}}" href="{{ route('admin-gs-cartconf') }}" role="tab">{{__('Cart')}}</a>
                <a class="nav-item nav-link {{request()->is('admin/general-settings/shippingconf') ? 'active' : ''}}" href="{{ route('admin-gs-shippingconf') }}" role="tab">{{__('Shipping')}}</a>
            </div>
        </nav>
    </div>
</div>