<div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{request()->is('admin/general-settings/marketplaceconf') ? 'active' : ''}}" href="{{ route('admin-gs-marketplaceconf') }}" role="tab">Geral</a>
                <a class="nav-item nav-link {{request()->is('admin/vendor/color') ? 'active' : ''}}" href="{{ route('admin-vendor-color') }}" role="tab">Cores</a>
            </div>
        </nav>
    </div>
</div>