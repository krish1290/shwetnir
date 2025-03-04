@php
    $all_notifications = auth()->user()->notifications;
    $unread_notifications = $all_notifications->where('read_at', null);
    $total_unread = count($unread_notifications);
@endphp


<li class="nav-item dropdown notification-dropdown">
    <a href="javascript:void(0);" class="nav-link dropdown-toggle position-relative load_notifications"
        id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="las la-bell"></i>

        @if (!empty($total_unread))
            <div class="blink">
                <div class="circle"></div>
            </div>
        @endif
    </a>
    <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
        <div class="nav-drop is-notification-dropdown">
            <div class="inner">
                <div class="nav-drop-header">
                    <span class="text-black font-12 strong">{{ $total_unread }}</span>
                    <a class="text-muted font-12" href="#">
                        {{ __('Clear All') }}
                    </a>
                </div>
                <div class="nav-drop-body account-items pb-0" id="notifications_list">

                    {{-- <hr class="account-divider">
                    <div class="text-center">
                        <a class="text-primary strong font-13" href="{{ url('/pages/notifications') }}">
                            {{ __('View All') }}</a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</li>
<input type="hidden" id="notification_page" value="1">
