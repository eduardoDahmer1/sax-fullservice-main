<div class="info-meta-2">
    <ul>
        @if($productt->type == 'License')
            @if($productt->platform != null)
            <li>
                <p>{{ __("Platform") }}: <b>{{ $productt->platform }}</b></p>
            </li>
            @endif
            @if($productt->region != null)
            <li>
                <p>{{ __("Region") }}: <b>{{ $productt->region }}</b></p>
            </li>
            @endif
            @if($productt->licence_type != null)
            <li>
                <p>{{ __("License Type") }}: <b>{{ $productt->licence_type }}</b></p>
            </li>
            @endif
        @endif
    </ul>
</div>
