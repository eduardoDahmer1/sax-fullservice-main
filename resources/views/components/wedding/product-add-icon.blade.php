@props(['id', 'icon' => 'gift', 'title' => 'Add To Wedding List'])

<li {{ $attributes->merge(['class']) }}>
    @wedding
        @if (Auth::guard('web')->check())
            <span class="add-to-wedding"
                data-href="{{ route('user.wedding.store', $id) }}"
                data-toggle="tooltip" data-placement="right"
                title="{{ __($title) }}" data-placement="right">
                <i class="fas fa-{{ $icon }}" style="font-size: 1.3rem"></i>
            </span>
        @else
            <span rel-toggle="tooltip" title="{{ __($title) }}"
                data-toggle="modal" id="wish-btn" data-target="#comment-log-reg"
                data-placement="right">
                <i class="fas fa-{{ $icon }}" style="font-size: 1.3rem"></i>
            </span>
        @endif
    @endwedding
</li>