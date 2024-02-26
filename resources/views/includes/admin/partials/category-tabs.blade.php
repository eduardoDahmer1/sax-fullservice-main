<div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link {{request()->is('admin/category') ? 'active' : ''}}" href="{{ route('admin-cat-index') }}" role="tab">{{ __('Categories') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/subcategory') ? 'active' : ''}}" href="{{ route('admin-subcat-index') }}" role="tab">{{ __("Sub Categories") }}</a>
                <a class="nav-item nav-link {{request()->is('admin/childcategory') ? 'active' : ''}}" href="{{ route('admin-childcat-index') }}" role="tab">{{ __('Child Categories') }}</a>
            </div>
        </nav>
    </div>
</div>