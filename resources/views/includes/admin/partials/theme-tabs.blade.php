<div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{request()->is('admin/general-settings/logo') ? 'active' : ''}}" href="{{ route('admin-gs-logo') }}" role="tab">{{ __('Logo') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/general-settings/favicon') ? 'active' : ''}}" href="{{ route('admin-gs-fav') }}" role="tab">{{ __('Favicon') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/general-settings/loader') ? 'active' : ''}}" href="{{ route('admin-gs-load') }}" role="tab">{{ __('Loader') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/general-settings/contents') ? 'active' : ''}}" href="{{ route('admin-gs-contents') }}" role="tab">{{ __('Colors') }}</a>
            </div>
        </nav>
    </div>
</div>