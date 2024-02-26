<div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link {{request()->is('admin/products/edit/*') ? 'active' : ''}}" href="{{ route('admin-prod-edit', $data->id) }}" role="tab">{{ __('Product') }}</a>
            <a class="nav-item nav-link {{request()->is('admin/products/edit-meli/*') ? 'active' : ''}}" href="{{ route('admin-prod-edit-meli', $data->id) }}" role="tab">Gerenciar Anúncio - {{ __("Mercado Livre") }}</a>
            </div>
        </nav>
    </div>
</div>