{{-- MESSAGE MODAL --}}
<div class="message-modal">
    <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorformLabel">{{ __('Send Message') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <form id="emailreply1">
                                        {{ csrf_field() }}
                                        <ul>
                                            <li>
                                                <input type="text" class="input-field" id="subj1" name="subject"
                                                    placeholder="{{ __('Subject *') }}" required>
                                            </li>
                                            <li>
                                                <textarea class="input-field textarea" name="message" id="msg1" placeholder="{{ __('Your Message') }}" required>
                                                    </textarea>
                                            </li>
                                            <input type="hidden" name="type" value="Ticket">
                                        </ul>
                                        <button class="submit-btn" id="emlsub"
                                            type="submit">{{ __('Send Message') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- MESSAGE MODAL ENDS --}}
@if (Auth::guard('web')->check())
    @if ($productt->user_id != 0)
        {{-- MESSAGE VENDOR MODAL --}}
        <div class="modal" id="vendorform1" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel1"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vendorformLabel1">{{ __('Send Message') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="contact-form">
                                        <form id="emailreply">
                                            {{ csrf_field() }}
                                            <ul>
                                                <li>
                                                    <input type="text" class="input-field" readonly=""
                                                        placeholder="Send To {{ $productt->user->shop_name }}" readonly>
                                                </li>
                                                <li>
                                                    <input type="text" class="input-field" id="subj"
                                                        name="subject" placeholder="{{ __('Subject *') }}" required>
                                                </li>
                                                <li>
                                                    <textarea class="input-field textarea" name="message" id="msg" placeholder="{{ __('Your Message') }}" required>
                                                        </textarea>
                                                </li>
                                                <input type="hidden" name="email"
                                                    value="{{ Auth::guard('web')->user()->email }}">
                                                <input type="hidden" name="name"
                                                    value="{{ Auth::guard('web')->user()->name }}">
                                                <input type="hidden" name="user_id"
                                                    value="{{ Auth::guard('web')->user()->id }}">
                                                <input type="hidden" name="vendor_id"
                                                    value="{{ $productt->user->id }}">
                                            </ul>
                                            <button class="submit-btn" id="emlsub1"
                                                type="submit">{{ __('Send Message') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- MESSAGE VENDOR MODAL ENDS --}}
    @endif
@endif
