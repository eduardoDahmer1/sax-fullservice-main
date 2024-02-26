<tr>
    <th>{{ __("Title") }}</th>
    <th>{{ __("Details") }}</th>
    <th>{{ __("Date") }}</th>
    <th>{{ __("Time") }}</th>
    <th>{{ __("Action") }}</th>
</tr>
@foreach($order->tracks as $track)

<tr data-id="{{ $track->id }}">
    <td width="30%" class="t-title">
        <span data-locale="{{$lang->locale}}">{{ $track->title }}</span>
        @foreach($locales as $loc)
            @if($loc->locale === $lang->locale)
                @continue
            @endif
            <span data-locale="{{$loc->locale}}" class="d-none">{{$track->translate($loc->locale)->title ?? $track->title}}</span>
        @endforeach
    </td>
    <td width="30%" class="t-text">
        <span data-locale="{{$lang->locale}}">{{$track->text ?? ''}}</span>
        @foreach($locales as $loc)
            @if($loc->locale === $lang->locale)
                @continue
            @endif
            <span data-locale="{{$loc->locale}}" class="d-none">{{$track->translate($loc->locale)->text ?? $track->text}}</span>
        @endforeach
    </td>
    <td>{{  date('Y-m-d',strtotime($track->created_at)) }}</td>
    <td>{{  date('h:i:s:a',strtotime($track->created_at)) }}</td>
    <td>
        <div class="action-list">
            <a data-href="{{ route('admin-order-track-update',$track->id) }}" class="track-edit"> <i
                    class="fas fa-edit"></i>{{__('Edit')}}</a>
            <a href="javascript:;" data-href="{{ route('admin-order-track-delete',$track->id) }}"
                class="track-delete"><i class="fas fa-trash-alt"></i></a>
        </div>
    </td>
</tr>
@endforeach