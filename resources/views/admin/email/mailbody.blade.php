{!! $email_body !!}

@if(isset($extraData["view"]))
    @include($extraData["view"], $extraData)
@endif