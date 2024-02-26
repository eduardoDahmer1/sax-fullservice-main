<div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link {{request()->is('admin/orders') ? 'active' : ''}}" href="{{ route('admin-order-index') }}" role="tab">{{__('Default Checkout')}}</a>
                <a class="nav-item nav-link {{request()->is('admin/orders/simplified-checkout') ? 'active' : ''}}" href="{{ route('admin-order-simplified-checkout') }}" role="tab">{{__('Simplified Checkout')}}</a>
            </div>
        </nav>
    </div>
</div>