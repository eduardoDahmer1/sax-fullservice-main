<div class="row">
    <div class="col-lg-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{request()->is('admin/payment-informations') ? 'active' : ''}}" href="{{ route('admin-gs-payments-index') }}" role="tab">{{ __('Cash On Delivery') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/payment-informations/deposit') ? 'active' : ''}}" href="{{ route('admin-gs-payments-deposit') }}" role="tab">{{ __('Bank Deposit') }}</a>
                <a class="nav-item nav-link {{request()->is('admin/bank_account') ? 'active' : ''}}" href="{{ route('admin-bank_account-index') }}" role="tab">{{ __('Bank Accounts') }}</a>
                @if(env("ENABLE_GATEWAY",true))
                <a class="nav-item nav-link {{request()->is('admin/payment-informations/gateway*') ? 'active' : ''}}" href="{{ route('admin-gs-payments-gateway') }}" role="tab">{{__('Gateways')}}</a>           
                @endif
            </div>
        </nav>
    </div>
</div>