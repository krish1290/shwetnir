@if (!empty($notifications_data))
    @foreach ($notifications_data as $notification_data)
        <a class="notification-item account-item @if (isset($notification_data['show_popup'])) 'show-notification-in-popup' @endif"
            href="{{ $notification_data['link'] ?? '#' }}">
            <div class="media align-center">
                <div class="icon-wrap">
                    <i class="notif-icon {{ $notification_data['icon_class'] ?? '' }}"></i>
                </div>
                <div class="media-content ml-3">
                    <h6 class="mb-0 @if (empty($notification_data['read_at'])) strong @endif ">{!! $notification_data['msg'] ?? '' !!}</h6>
                    <p class="m-0 mt-1 font-10 text-muted"> {{ $notification_data['created_at'] }}</p>
                </div>
            </div>
        </a>
    @endforeach
@else
    <a class="notification-item account-item" href="{{ url('/apps/ecommerce/orders') }}">
        <div class="media align-center">

            <div class="media-content ml-3">
                <h6 class="font-13 mb-0 strong"> @lang('lang_v1.no_notifications_found')</h6>

            </div>
        </div>
    </a>
@endif
