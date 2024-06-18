@if(auth()->check())
    <ul class="nav navbar-nav navbar-right notification-dropdown">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
               aria-expanded="false">
            <span class="notification-bell"><i class="fa fa-bell"></i>@if(auth()->user()->hasRole('customer') && \App\Models\User::find(auth()->user()->getAuthIdentifier())->unreadNotifications->count())
                    <b>{{\App\Models\User::find(auth()->user()->getAuthIdentifier())->unreadNotifications->count()}}</b>@endif</span>
            </a>
            <ul class="dropdown-menu notify-drop">
                <div class="notify-drop-title">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">@lang('notification.Notifications')</div>
                        <div class="col-md-6 col-sm-6 col-xs-6 text-right"><a href="#" class="rIcon allRead">@lang('notification.Mark all as read')</a></div>
                    </div>
                </div>
                <!-- end notify title -->
                <!-- notify content -->
                <div class="drop-content">
                    @if(auth()->user()->hasRole('customer'))
                        @forelse(auth()->user()->notifications as $notification)
                            <li>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <div class="notify-img"><i class="fa fa-bell"></i></div>
                                </div>
                                <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">

                                    @if ($notification->type == 'App\Notifications\Order\MessageAdded')
                                        <a href="{{route('ch_order_view', [$notification->data['order_id']])}}">@lang('notification.New Message', ['order_id' => $notification->data['order_id']])</a>
                                    @endif

                                    @if ($notification->type == 'App\Notifications\Order\OrderCreated')
                                        <a href="{{route('ch_order_view', [$notification->data['id']])}}">@lang('notification.Order#:order_id has been submitted.', ['order_id' => $notification->data['id']])</a>
                                    @endif

                                    @if($notification->type == 'App\Notifications\Order\OrderUpdated')
                                        @if($notification->data['status'] == 'processing')
                                            <a href="{{route('ch_order_view', [$notification->data['id']])}}">@lang('notification.Order#:order_id is now being processed.', ['order_id' => $notification->data['id']])</a>
                                        @endif

                                        @if($notification->data['status'] == 'completed')
                                            <a href="{{route('ch_order_view', [$notification->data['id']])}}">@lang('notification.Order#:order_id has been completed.', ['order_id' => $notification->data['id']])</a>
                                        @endif

                                        @if($notification->data['status'] == 'refunded')
                                            <a href="{{route('ch_order_view', [$notification->data['id']])}}">@lang('notification.The payment against order#:order_id has been refunded.', ['order_id' => $notification->data['id']])</a>
                                        @endif

                                        @if($notification->data['status'] == 'cancelled')
                                            <a href="{{route('ch_order_view', [$notification->data['id']])}}">@lang('notification.Order#:order_id has been cancelled.', ['order_id' => $notification->data['id']])</a>
                                        @endif

                                        @if($notification->data['status'] == 'failed')
                                            <a href="{{route('ch_order_view', [$notification->data['id']])}}">@lang('notification.Order#:order_id has been failed.', ['order_id' => $notification->data['id']])</a>
                                        @endif
                                    @endif
                                    <hr>
                                    <p class="time" title="{{$notification->created_at}}">{{$notification->created_at->diffForHumans()}}</p>
                                </div>
                            </li>
                        @empty
                            <li>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <div class="notify-img"><i class="fa fa-info-circle"></i></div>
                                </div>
                                <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                                    @lang('notification.No new notification.')
                                </div>
                            </li>
                        @endforelse
                    @else
                        <li>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <div class="notify-img"><i class="fa fa-info-circle"></i></div>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                                @lang('notification.No new notifications.')
                            </div>
                        </li>
                    @endif
                </div>
                <div class="notify-drop-footer text-center">
                    <a href="#" class="clear-notifications"><i class="fa fa-close"></i> @lang('notification.Clear Notifications')</a>
                </div>
            </ul>
        </li>
    </ul>
@endif
