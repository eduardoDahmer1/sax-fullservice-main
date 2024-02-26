<div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{request()->is('admin/shipping') ? 'active' : ''}}" href="{{ route('admin-shipping-index') }}" role="tab">{{ __('General Shipping') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/general-settings/correiosconf') ? 'active' : ''}}" href="{{ route('admin-gs-correiosconf') }}" role="tab">{{ __('Correios Shipping') }}</a>
                @if(config("features.aex_shipping"))
                    <a class="nav-item nav-link {{request()->is('admin/general-settings/aexconf') ? 'active' : ''}}" href="{{ route('admin-gs-aexconf') }}" role="tab">{{ __('AEX Shipping') }}</a>
                @endif
                @if(config("features.melhorenvio_shipping"))
                    <a class="nav-item nav-link {{request()->is('admin/general-settings/melhorenvioconf') ? 'active' : ''}}" href="{{ route('admin-gs-melhorenvioconf') }}" role="tab">{{ __('Melhor Envio Shipping') }}</a>
                @endif
                @if(config("features.fedex_shipping"))
                    <a class="nav-item nav-link {{request()->is('admin/general-settings/fedexconf') ? 'active' : ''}}" href="{{ route('admin-gs-fedexconf') }}" role="tab">{{ __('Fedex Shipping') }}</a>
                @endif
                <a class="nav-item nav-link {{request()->is('admin/package') ? 'active' : ''}}" href="{{ route('admin-package-index') }}" role="tab">{{ __('Packagings') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/pickup') ? 'active' : ''}}" href="{{ route('admin-pick-index') }}" role="tab">{{ __('Pickup Locations') }}</a>
            </div>
        </nav>
    </div>
</div>