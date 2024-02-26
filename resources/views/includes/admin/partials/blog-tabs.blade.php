<div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{request()->is('admin/blog') ? 'active' : ''}}" href="{{ route('admin-blog-index') }}" role="tab">{{ __('Posts') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/blog/category') ? 'active' : ''}}" href="{{ route('admin-cblog-index') }}" role="tab">{{ __('Categories') }}</a>
            </div>
        </nav>
    </div>
</div>