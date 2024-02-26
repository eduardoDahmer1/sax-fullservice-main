<div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{request()->is('admin/ratings') ? 'active' : ''}}" href="{{ route('admin-rating-index') }}" role="tab">{{ __('Reviews') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/comments') ? 'active' : ''}}" href="{{ route('admin-comment-index') }}" role="tab">{{ __('Comments') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/reports') ? 'active' : ''}}" href="{{ route('admin-report-index') }}" role="tab">{{ __('Reports') }}</a>
            </div>
        </nav>
    </div>
</div>