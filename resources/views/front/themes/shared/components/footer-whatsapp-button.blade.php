<!--Start of Whatsapp -->
@if ($gs->is_whatsapp == 1)
    @if (count($twhatsapp) > 0 && $gs->team_show_whatsapp == 1)

        @foreach ($twhatsapp as $tcategory)
            <!-- Modal -->
            <div class="modal fade" id="{{ 'modalSales' . $tcategory->id }}" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="margin: 10px 0 10px 0;">
                            <h3 class="modal-title">{{ $tcategory->name }}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="border-top: 1px solid #dee2e6; !important">
                            @include('includes.team_membermodal')
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="floating_button_sellers">
            @if (!empty($gs->whatsapp_number))
                <a href="https://api.whatsapp.com/send?1=pt_BR&phone={!! $gs->whatsapp_number !!}&text={{urlencode(__('Hello, I was visiting the Sax shop and would like more information?'))}}"
                    class="floating_button_seller" target="_blank">
                    <button class="btn btn-success">{{ __('Main') }}</button>
                </a>
            @endif
            @foreach ($twhatsapp as $tcategory)
                <a href="#" data-toggle="modal" data-target="{{ '#modalSales' . $tcategory->id }}"
                    class=" floating_button_seller">
                    <button class="btn btn-secondary mb-0">{{ $tcategory->name }}</button>
                </a>
            @endforeach
            <div>
                <a target="_blank" class="floating_button_seller main" id="wppHov"><img
                        src="{{ asset('assets/images/icon/wpp_mini.png') }}"></a>
            </div>
        </div>
    @else
        <div>
            <a href="https://api.whatsapp.com/send?1=pt_BR&phone={!! $gs->whatsapp_number !!}&text={{urlencode(__('Hello, I was visiting the Sax shop and would like more information?'))}}"
                target="_blank">
                <img class="whatsapp-widget" id="whatsapp-widget" style="cursor:pointer"
                    src="{{ asset('assets/images/icon/wpp_mini.png') }}">
            </a>
        </div>

    @endif
@endif
<script>
    $(function() {
        $('.floating_button_sellers').hover(function() {
            $('.floating_button_seller:not(:last-child)').css({
                'display': 'block',
                '-webkit-animation': 'fadeIn 0.7s',
                'animation': 'fadeIn 0.7s',
                'width': '80px',
                'margin': '20px auto 0',
            });
        }, function() {
            $('.floating_button_seller:not(:last-child)').css({
                'display': 'none',
                'width': '36px',
                'margin': '20px auto 0'
            });
        });
    });
</script>
<!--End of Whatsapp-->
