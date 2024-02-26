<!-- Order Tracking modal Start-->
<div class="modal fade" id="track-order-modal" tabindex="-1" role="dialog" aria-labelledby="order-tracking-modal"
    aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"> <b>{{ __('Order Tracking') }}</b> </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="order-tracking-content">
                    <form id="track-form" class="track-form">
                        {{ csrf_field() }}
                        <input type="text" id="track-code" placeholder="{{ __('Get Tracking Code') }}"
                            required="">
                        <button type="submit" class="mybtn1">{{ __('View Tracking') }}</button>
                        <a href="#" data-toggle="modal" data-target="#order-tracking-modal"></a>
                    </form>
                </div>
                <div>
                    <div class="submit-loader d-none">
                        <img src="{{ $gs->loaderUrl }}" alt="">
                    </div>
                    <div id="track-order"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Order Tracking modal End -->
