<a class="clear">{{ __('Notifications') }}
    @if(count($notifications) > 0)
    <span id="notifications-clear" class="clear-notf-all" data-href="{{ route('admin.notifications-mark-as-read') }}">
        {{ __('Mark All as Read') }}
    </span>
    @endif
</a>
@if(count($notifications) > 0)
<ul>
@foreach($notifications as $notification)
    @if($notification->order_id && !$notification->receipt)
    <!-- Order Notifications -->
    <li style="{{ $notification->is_read ? "opacity: 50%;" : "" }}">
        <a href="{{ route('admin-order-show',$notification->order_id) }}">
            <i class="fas fa-2x fa-newspaper"></i> {{ __('New Order') }}: {{ $notification->order->order_number }}
            <small class="d-block notf-time ">
                @php
                \Carbon\Carbon::setLocale($lang->locale);
                @endphp
                {{ $notification->created_at->diffForHumans() }}
            </small>
        </a>
        @include('admin.notification.includes.clear-notification-btn')
    </li>
    @elseif($notification->user_id)
    <!-- New User Notifications -->
    <li style="{{ $notification->is_read ? "opacity: 50%;" : "" }}">
        <a href="{{ route('admin-user-show',$notification->user_id) }}">
             <i class="fas fa-user"></i> {{ __('New User') }}: {{ $notification->user->name }}
             <small class="d-block notf-time ">
                @php
                \Carbon\Carbon::setLocale($lang->locale);
                @endphp
                 {{ $notification->created_at->diffForHumans() }}
                </small>
        </a>
        @include('admin.notification.includes.clear-notification-btn')
    </li>
    @elseif($notification->product_id)
    <!-- Low Stock Products Notifications -->
    <li style="{{ $notification->is_read ? "opacity: 50%;" : "" }}">
        <a href="{{ $notification->product->id ? route('admin-prod-edit',$notification->product->id) : '' }}"> <i class="icofont-cart"></i> {{ __("Low Stock Product") }}
            <small class="d-block notf-stock">{{mb_strlen($notification->product->name,'utf-8') > 50 ? mb_substr($notification->product->name,0,30,'utf-8') . "..." : $notification->product->name}}</small>
            <small class="d-block notf-stock">{{ __('Stock') }} : {{$notification->product->stock}}</small>
            <small class="d-block notf-time ">
                @php
                \Carbon\Carbon::setLocale($lang->locale);
                @endphp
                {{ $notification->created_at->diffForHumans() }}
            </small>
        </a>
        @include('admin.notification.includes.clear-notification-btn')
    </li>
    @elseif($notification->conversation_id)
    <!-- User Messages to Admin Notifications -->
    <li style="{{ $notification->is_read ? "opacity: 50%;" : "" }}">
        <a href="{{ route('admin-message-show',$notification->conversation_id) }}">
             <i class="fas fa-envelope"></i> {{ __('New message from') }} {{ $notification->conversation->user->name }}
             <small class="d-block notf-stock">{{ $notification->conversation->message }}</small>
             <small class="d-block notf-time ">
                @php
                \Carbon\Carbon::setLocale($lang->locale);
                @endphp
                 {{ $notification->created_at->diffForHumans() }}
                </small>
        </a>
        @include('admin.notification.includes.clear-notification-btn')
    </li>
    @elseif($notification->order_id && $notification->receipt)
    <!-- Bank Deposit Receipts Notifications -->
    <li style="{{ $notification->is_read ? "opacity: 50%;" : "" }}">
        <a href="{{ route('admin-order-receipt',$notification->order_id) }}" >
             <i class="fas fa-2x fa-newspaper"></i> {{ __("A new receipt has been sent") }}
            <small class="d-block notf-time ">
                @php
                \Carbon\Carbon::setLocale($lang->locale);
                @endphp
                {{ $notification->created_at->diffForHumans() }}
            </small>
        </a>
        @include('admin.notification.includes.clear-notification-btn')
    </li>
    @endif
@endforeach

</ul>

@else 

<a class="clear" href="javascript:;">
    {{ __('No New Notifications.') }}
</a>

@endif
<script>
    var locale = "{{$lang->locale}}"
    moment.locale(locale)
        $(document).ready(function() {
         function loadNotifications(){
              return $.ajax({
                    url: '/admin/notifications/notification',
                    method: 'GET',
                    dataType: "json",
                    success: function(data) {
                        var notifications = data.data;
                        var dropdownMenu = $('.dropdownmenu-wrapper');
                        var html = '<ul>'; 
                        $.each(notifications, function(i, notification) {
                            var createdAt = moment(notification.created_at);
                            var timeAgo = createdAt.fromNow()
                            if(notification.order_id && !notification.receipt){
                                html += '<li style="opacity:' + (notification.is_read ? '50%' : '') + '">';
                                html += `<a href="/admin/order/${notification.order_id}/show">`;
                                html += '<i class="fas fa-2x fa-newspaper"></i>';
                                html += " {{ __('New Order') }}: " + notification.order.order_number; 
                                html += '<small class="d-block notf-time">' + timeAgo + '</small>';
                                html += '</a>';
                                html += `<a class="clear-notf" style="float: right;
                                position: relative;
                                display: flex;
                                color: black !important;
                                padding: 0.5rem !important;" href="/admin/notifications/clear/${notification.id}">Limpar</a>`;
                                html += '</li>';
                            }
                            else if(notification.user_id){
                                html += '<li style="opacity:' + (notification.is_read ? '50%' : '') + '">';
                                html += `<a href="/admin/user/${notification.user_id}/show">`;
                                html += '<i class="fas fa-user"></i>';
                                html += " {{ __('New User') }}: " + notification.user.name; 
                                html += '<small class="d-block notf-time">' + timeAgo + '</small>';
                                html += '</a>';
                                html += `<a class="clear-notf" style="float: right;
                                position: relative;
                                display: flex;
                                color: black !important;
                                padding: 0.5rem !important;" href="/admin/notifications/clear/${notification.id}">Limpar</a>`;
                                html += '</li>';
                            }
                            else if(notification.product_id){
                                html += '<li style="opacity:' + (notification.is_read ? '50%' : '') + '">';
                                html += `<a href="/admin/products/edit/${notification.product_id}">`; 
                                html += '<i class="icofont-cart"></i>{{ __("Low Stock Product") }}';
                                var productName = notification.product.name;
                                if(productName.length > 50) {
                                    productName = productName.substring(0, 30) + "...";
                                }
                                html += '<small class="d-block notf-stock">' + productName + '</small>';
                                html += '<small class="d-block notf-stock">' + "{{ __('Stock') }}: " + notification.product.stock +'</small>';
                                html += '<small class="d-block notf-time">' + timeAgo + '</small>';  
                                html += '</a>';
                                html += `<a class="clear-notf" style="float: right;
                                position: relative;
                                display: flex;
                                color: black !important;
                                padding: 0.5rem !important;" href="/admin/notifications/clear/${notification.id}">Limpar</a>`;
                                html += '</li>';

                            }else if(notification.conversation_id){
                                html += '<li style="opacity:' + (notification.is_read ? '50%' : '') + '">';
                                html += `<a href="/admin/message/${notification.conversation_id}">`;
                                html += '<i class="fas fa-envelope"></i>';
                                html += "{{ __('New message from') }}: " + notification.conversation.user.name;
                                html += '<small class="d-block notf-stock">' + notification.conversation.message + '</small>';
                                html += '<small class="d-block notf-time">' + timeAgo + '</small>';
                                html += '</a>';
                                html += `<a class="clear-notf" style="float: right;
                                position: relative;
                                display: flex;
                                color: black !important;
                                padding: 0.5rem !important;" href="/admin/notifications/clear/${notification.id}">Limpar</a>`;
                                html += '</li>';
                                    
                            }else if(notification.order_id && notification.receipt){
                                html += '<li style="opacity:' + (notification.is_read ? '50%' : '') + '">';
                                html += `<a href="/order/${notification.order_id}/receipt">`;
                                html += '<i class="fas fa-2x fa-newspaper"></i>';
                                html += "{{ __('A new receipt has been sent') }}";
                                html += '<small class="d-block notf-time">' + timeAgo + '</small>';
                                html += '</a>';
                                html += `<a class="clear-notf" style="float: right;
                                position: relative;
                                display: flex;
                                color: black !important;
                                padding: 0.5rem !important;" href="/admin/notifications/clear/${notification.id}">Limpar</a>`;
                                html += '</li>';
                            }
                        });
                        html += '</ul>';

                        dropdownMenu.append(html);
                    }
                });
            }
            var isLoading = false;
            $('.dropdown-menu').on('scroll', function() {
                var scrollTop = $(this).scrollTop();
                var innerHeight = $(this).innerHeight();
                var scrollHeight = $(this)[0].scrollHeight;
                if(scrollTop + innerHeight >= scrollHeight -5 && !isLoading) {
                    isLoading = true;
                    loadNotifications().then(function() {
                        isLoading = false;
                    });
                }
            });

        })

</script>