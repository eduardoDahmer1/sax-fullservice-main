
    <div class="content-area">
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            @include('includes.admin.form-error')
                            <form id="geniusformdata" action="{{route('front.simplified_checkout-create')}}" method="GET" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="">{{ __('Name') }} *</label>
                                        <input type="text" id="customer_name" class="input-field" name="name" placeholder="{{ __('Name') }}" value="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="">{{ __('Phone') }} *</label>
                                        <input type="text" id="customer_phone" class="input-field" name="phone" placeholder="{{ __('Phone') }}" value="">
                                    </div>
                                </div>
                                <div class="row mt-30">
                                    <div class="col-lg-12" style="text-align: right">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                        <button onclick="btnDisabled(this)" class="btn btn-success" type="submit">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let clickCount = 0
        function btnDisabled(button) {
            clickCount++
            if(clickCount != 1) {
                button.disabled = true
            }
        }
    </script>

