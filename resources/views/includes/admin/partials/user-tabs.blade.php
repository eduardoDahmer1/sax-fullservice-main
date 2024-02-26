<div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{request()->is('admin/staff') ? 'active' : ''}}" href="{{ route('admin-staff-index') }}" role="tab">{{ __('Staffs') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/role') ? 'active' : ''}}" href="{{ route('admin-role-index') }}" role="tab">{{ __('Roles') }}</a>
            </div>
        </nav>
    </div>
</div>