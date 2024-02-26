{{-- ADD / EDIT MODAL --}}
<div class="modal fade" id="simplified-checkout-modal" tabindex="-1" role="dialog"
    aria-labelledby="simplified-checkout-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header top-header" style="padding: 1rem 1rem !important; border-radius: 0 !important;">
                <div class="modal-title" style="color: #fff">{{ __('Simplified Checkout') }}</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="container mt-5">
                <p>{{ __('Buy in a simple and interactive way') }}</p>
            </div>
            <div class="modal-body mt-3">
                @include('includes.simplified-checkout')
            </div>
        </div>
    </div>
</div>
{{-- ADD / EDIT MODAL ENDS --}}