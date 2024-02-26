<div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{request()->is('admin/team_member') ? 'active' : ''}}" href="{{ route('admin-team_member-index') }}" role="tab">{{ __('Team Members') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/team_member/category') ? 'active' : ''}}" href="{{ route('admin-cteam_member-index') }}" role="tab">{{ __('Categories') }}</a>
            </div>
        </nav>
    </div>
</div>